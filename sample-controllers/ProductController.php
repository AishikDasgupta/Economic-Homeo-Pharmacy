<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'primaryImage']);
        
        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }
        
        // Filter by featured status if provided
        if ($request->has('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }
        
        // Filter by active status if provided
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }
        
        // Search by name or description if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
        
        // Sort products
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Paginate results
        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);
        
        return response()->json($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products',
            'stock_quantity' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image_index' => 'nullable|integer|min:0',
            'benefits' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'usage_instructions' => 'nullable|string',
            'precautions' => 'nullable|string',
        ]);
        
        // Create the product
        $product = new Product([
            'name' => $request->name,
            'description' => $request->description,
            'short_description' => $request->short_description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
            'is_featured' => $request->boolean('is_featured', false),
            'is_active' => $request->boolean('is_active', true),
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
        ]);
        
        $product->save();
        
        // Attach categories
        if ($request->has('categories')) {
            $product->categories()->attach($request->categories);
        }
        
        // Handle product images
        if ($request->hasFile('images')) {
            $primaryImageIndex = $request->input('primary_image_index', 0);
            $images = $request->file('images');
            
            foreach ($images as $index => $image) {
                $path = $image->store('products', 'public');
                
                $productImage = new ProductImage([
                    'image_path' => $path,
                    'is_primary' => $index === (int) $primaryImageIndex,
                    'alt_text' => $request->name,
                ]);
                
                $product->images()->save($productImage);
            }
        }
        
        // Handle product details
        if ($request->filled('benefits') || $request->filled('ingredients') || 
            $request->filled('usage_instructions') || $request->filled('precautions')) {
            $product->details()->create([
                'benefits' => $request->benefits,
                'ingredients' => $request->ingredients,
                'usage_instructions' => $request->usage_instructions,
                'precautions' => $request->precautions,
            ]);
        }
        
        // Load relationships for the response
        $product->load(['categories', 'images', 'details']);
        
        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['categories', 'images', 'details']);
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'sometimes|required|string|max:100|unique:products,sku,' . $product->id,
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'benefits' => 'nullable|string',
            'ingredients' => 'nullable|string',
            'usage_instructions' => 'nullable|string',
            'precautions' => 'nullable|string',
        ]);
        
        // Update the product
        $product->fill($request->only([
            'name', 'description', 'short_description', 'price', 'sale_price',
            'sku', 'stock_quantity', 'is_featured', 'is_active'
        ]));
        
        // Update slug if name changed
        if ($request->has('name') && $product->isDirty('name')) {
            $product->slug = Str::slug($request->name) . '-' . Str::random(5);
        }
        
        $product->save();
        
        // Sync categories if provided
        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }
        
        // Update product details if provided
        if ($request->filled('benefits') || $request->filled('ingredients') || 
            $request->filled('usage_instructions') || $request->filled('precautions')) {
            
            $details = $product->details ?? $product->details()->create([]);
            
            $details->fill([
                'benefits' => $request->benefits,
                'ingredients' => $request->ingredients,
                'usage_instructions' => $request->usage_instructions,
                'precautions' => $request->precautions,
            ]);
            
            $details->save();
        }
        
        // Load relationships for the response
        $product->load(['categories', 'images', 'details']);
        
        return response()->json($product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete product images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        
        // Delete product details
        if ($product->details) {
            $product->details->delete();
        }
        
        // Detach categories
        $product->categories()->detach();
        
        // Delete the product
        $product->delete();
        
        return response()->json(null, 204);
    }

    /**
     * Upload additional images for a product.
     */
    public function uploadImages(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image_index' => 'nullable|integer|min:0',
        ]);
        
        $images = $request->file('images');
        $primaryImageIndex = $request->input('primary_image_index');
        
        // If setting a new primary image, unset the current primary image
        if ($primaryImageIndex !== null) {
            $product->images()->update(['is_primary' => false]);
        }
        
        $uploadedImages = [];
        
        foreach ($images as $index => $image) {
            $path = $image->store('products', 'public');
            
            $productImage = new ProductImage([
                'image_path' => $path,
                'is_primary' => $primaryImageIndex !== null && $index === (int) $primaryImageIndex,
                'alt_text' => $product->name,
            ]);
            
            $product->images()->save($productImage);
            $uploadedImages[] = $productImage;
        }
        
        return response()->json($uploadedImages, 201);
    }

    /**
     * Delete a product image.
     */
    public function deleteImage(ProductImage $image)
    {
        $product = $image->product;
        
        // Check if this is the primary image
        $isPrimary = $image->is_primary;
        
        // Delete the image file from storage
        Storage::disk('public')->delete($image->image_path);
        
        // Delete the image record
        $image->delete();
        
        // If this was the primary image, set another image as primary if available
        if ($isPrimary) {
            $newPrimaryImage = $product->images()->first();
            if ($newPrimaryImage) {
                $newPrimaryImage->is_primary = true;
                $newPrimaryImage->save();
            }
        }
        
        return response()->json(null, 204);
    }

    /**
     * Set a product image as primary.
     */
    public function setPrimaryImage(ProductImage $image)
    {
        $product = $image->product;
        
        // Unset current primary image
        $product->images()->update(['is_primary' => false]);
        
        // Set new primary image
        $image->is_primary = true;
        $image->save();
        
        return response()->json($image);
    }

    /**
     * Get featured products.
     */
    public function featured(Request $request)
    {
        $limit = $request->get('limit', 8);
        $products = Product::with(['categories', 'primaryImage'])
            ->active()
            ->featured()
            ->latest()
            ->take($limit)
            ->get();
        
        return response()->json($products);
    }

    /**
     * Get products by category.
     */
    public function byCategory(Request $request, $categoryId)
    {
        $query = Product::with(['categories', 'primaryImage'])
            ->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            })
            ->active();
        
        // Sort products
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        // Paginate results
        $perPage = $request->get('per_page', 15);
        $products = $query->paginate($perPage);
        
        return response()->json($products);
    }
}