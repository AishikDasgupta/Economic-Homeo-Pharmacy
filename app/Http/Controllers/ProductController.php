<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products for public view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images' => function($query) {
            $query->where('is_primary', true)->orWhereRaw('id = (SELECT MIN(id) FROM product_images WHERE product_id = products.id)');
        }])->active();

        // Apply filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $allowedSortFields = ['name', 'price', 'created_at'];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Display the specified product for public view.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'productDetail'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Get related products from the same category
        $relatedProducts = Product::with(['category', 'images' => function($query) {
                $query->where('is_primary', true)->orWhereRaw('id = (SELECT MIN(id) FROM product_images WHERE product_id = products.id)');
            }])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->limit(4)
            ->get();

        return response()->json([
            'product' => $product,
            'related_products' => $relatedProducts
        ]);
    }

    /**
     * Get featured products for homepage.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFeaturedProducts()
    {
        $featuredProducts = Product::with(['category', 'images' => function($query) {
                $query->where('is_primary', true)->orWhereRaw('id = (SELECT MIN(id) FROM product_images WHERE product_id = products.id)');
            }])
            ->active()
            ->featured()
            ->limit(8)
            ->get();

        return response()->json($featuredProducts);
    }

    /**
     * Get products by category.
     *
     * @param  string  $slug
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getProductsByCategory($slug, Request $request)
    {
        $category = Category::where('slug', $slug)->active()->firstOrFail();

        $query = Product::with(['category', 'images' => function($query) {
                $query->where('is_primary', true)->orWhereRaw('id = (SELECT MIN(id) FROM product_images WHERE product_id = products.id)');
            }])
            ->where('category_id', $category->id)
            ->active();

        // Apply filters
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $allowedSortFields = ['name', 'price', 'created_at'];
        
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage);

        return response()->json([
            'category' => $category,
            'products' => $products
        ]);
    }
}