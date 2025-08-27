<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images' => function($query) {
            $query->where('is_primary', true)->orWhereRaw('id = (SELECT MIN(id) FROM product_images WHERE product_id = products.id)');
        }]);

        // Apply filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('active') && $request->active !== '') {
            $query->where('active', $request->active);
        }

        if ($request->has('featured') && $request->featured !== '') {
            $query->where('featured', $request->featured);
        }

        // Pagination
        $perPage = $request->per_page ?? 10;
        $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku',
            'stock_quantity' => 'required|integer|min:0',
            'active' => 'boolean',
            'featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'benefits' => 'nullable|string',
            'usage' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'dosage' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'precautions' => 'nullable|string',
            'storage_info' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Create product
            $product = new Product();
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->sku = $request->sku;
            $product->stock_quantity = $request->stock_quantity;
            $product->active = $request->active ?? false;
            $product->featured = $request->featured ?? false;
            $product->save();

            // Create product details
            $productDetail = new ProductDetail();
            $productDetail->product_id = $product->id;
            $productDetail->benefits = $request->benefits;
            $productDetail->usage = $request->usage;
            $productDetail->ingredients = $request->ingredients;
            $productDetail->dosage = $request->dosage;
            $productDetail->side_effects = $request->side_effects;
            $productDetail->precautions = $request->precautions;
            $productDetail->storage_info = $request->storage_info;
            $productDetail->additional_info = $request->additional_info;
            $productDetail->save();

            // Handle images
            if ($request->hasFile('images')) {
                $isPrimary = true; // First image is primary
                foreach ($request->file('images') as $imageFile) {
                    $path = $imageFile->store('products', 'public');
                    
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image_path = $path;
                    $productImage->alt_text = $product->name;
                    $productImage->is_primary = $isPrimary;
                    $productImage->sort_order = ProductImage::where('product_id', $product->id)->count() + 1;
                    $productImage->save();
                    
                    $isPrimary = false; // Only first image is primary
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product->load(['category', 'images', 'productDetail'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['category', 'images', 'productDetail'])->findOrFail($id);
        return response()->json(['data' => $product]);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Find product
        $product = Product::findOrFail($id);

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $id,
            'stock_quantity' => 'required|integer|min:0',
            'active' => 'boolean',
            'featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'benefits' => 'nullable|string',
            'usage' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'dosage' => 'nullable|string',
            'side_effects' => 'nullable|string',
            'precautions' => 'nullable|string',
            'storage_info' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            // Update product
            $product->name = $request->name;
            // Only update slug if name has changed
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($request->name);
            }
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
            $product->sku = $request->sku;
            $product->stock_quantity = $request->stock_quantity;
            $product->active = $request->active ?? false;
            $product->featured = $request->featured ?? false;
            $product->save();

            // Update or create product details
            $productDetail = ProductDetail::firstOrNew(['product_id' => $product->id]);
            $productDetail->benefits = $request->benefits;
            $productDetail->usage = $request->usage;
            $productDetail->ingredients = $request->ingredients;
            $productDetail->dosage = $request->dosage;
            $productDetail->side_effects = $request->side_effects;
            $productDetail->precautions = $request->precautions;
            $productDetail->storage_info = $request->storage_info;
            $productDetail->additional_info = $request->additional_info;
            $productDetail->save();

            // Handle new images
            if ($request->hasFile('images')) {
                $currentImageCount = ProductImage::where('product_id', $product->id)->count();
                $newImageCount = count($request->file('images'));
                
                // Check if total images would exceed 5
                if ($currentImageCount + $newImageCount > 5) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Maximum 5 images allowed per product. You already have ' . $currentImageCount . ' images.'
                    ], 422);
                }
                
                // If no primary image exists, make first new image primary
                $hasPrimary = ProductImage::where('product_id', $product->id)
                                        ->where('is_primary', true)
                                        ->exists();
                $isPrimary = !$hasPrimary;
                
                foreach ($request->file('images') as $imageFile) {
                    $path = $imageFile->store('products', 'public');
                    
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image_path = $path;
                    $productImage->alt_text = $product->name;
                    $productImage->is_primary = $isPrimary;
                    $productImage->sort_order = ProductImage::where('product_id', $product->id)->count() + 1;
                    $productImage->save();
                    
                    $isPrimary = false; // Only first image is primary if no primary exists
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product->load(['category', 'images', 'productDetail'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            
            // Delete product images from storage
            foreach ($product->images as $image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            }
            
            // Delete product details
            if ($product->productDetail) {
                $product->productDetail->delete();
            }
            
            // Delete product
            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle product status (active/inactive).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->active = !$product->active;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product status updated successfully',
                'data' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle product featured status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleFeatured($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->featured = !$product->featured;
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Product featured status updated successfully',
                'data' => $product
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product featured status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process bulk actions on products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:activate,deactivate,feature,unfeature,delete',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $action = $request->action;
            $productIds = $request->product_ids;
            $count = count($productIds);

            switch ($action) {
                case 'activate':
                    Product::whereIn('id', $productIds)->update(['active' => true]);
                    $message = "{$count} products activated successfully";
                    break;
                    
                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['active' => false]);
                    $message = "{$count} products deactivated successfully";
                    break;
                    
                case 'feature':
                    Product::whereIn('id', $productIds)->update(['featured' => true]);
                    $message = "{$count} products marked as featured successfully";
                    break;
                    
                case 'unfeature':
                    Product::whereIn('id', $productIds)->update(['featured' => false]);
                    $message = "{$count} products removed from featured successfully";
                    break;
                    
                case 'delete':
                    // Get all products with their images
                    $products = Product::with('images')->whereIn('id', $productIds)->get();
                    
                    foreach ($products as $product) {
                        // Delete product images from storage
                        foreach ($product->images as $image) {
                            if (Storage::disk('public')->exists($image->image_path)) {
                                Storage::disk('public')->delete($image->image_path);
                            }
                            $image->delete();
                        }
                        
                        // Delete product details
                        if ($product->productDetail) {
                            $product->productDetail->delete();
                        }
                    }
                    
                    // Delete products
                    Product::whereIn('id', $productIds)->delete();
                    $message = "{$count} products deleted successfully";
                    break;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process bulk action',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get product count for dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductCount()
    {
        try {
            $totalProducts = Product::count();
            $activeProducts = Product::where('active', true)->count();
            $featuredProducts = Product::where('featured', true)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $totalProducts,
                    'active' => $activeProducts,
                    'featured' => $featuredProducts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get product count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}