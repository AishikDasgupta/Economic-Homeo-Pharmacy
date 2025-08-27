<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\ProductImage;
use App\Models\ProductDetail;

class FrontendIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the home page loads correctly.
     *
     * @return void
     */
    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    /**
     * Test the products page loads correctly.
     *
     * @return void
     */
    public function test_products_page_loads_successfully()
    {
        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
    }

    /**
     * Test the product detail page loads correctly.
     *
     * @return void
     */
    public function test_product_detail_page_loads_successfully()
    {
        // Create a category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'active' => true
        ]);

        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'This is a test product',
            'category_id' => $category->id,
            'price' => 100.00,
            'sku' => 'TEST-SKU-001',
            'stock_quantity' => 10,
            'active' => true,
            'featured' => true
        ]);

        // Create product images
        ProductImage::factory()->create([
            'product_id' => $product->id,
            'image_path' => 'products/test-image.jpg',
            'alt_text' => 'Test Product Image',
            'is_primary' => true,
            'sort_order' => 1
        ]);

        // Create product details
        ProductDetail::factory()->create([
            'product_id' => $product->id,
            'benefits' => 'Test benefits',
            'usage' => 'Test usage',
            'ingredients' => 'Test ingredients',
            'dosage' => 'Test dosage',
            'side_effects' => 'Test side effects',
            'precautions' => 'Test precautions',
            'storage' => 'Test storage',
            'additional_info' => 'Test additional info'
        ]);

        $response = $this->get('/products/test-product');

        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertViewHas('slug', 'test-product');
    }

    /**
     * Test the category page loads correctly.
     *
     * @return void
     */
    public function test_category_page_loads_successfully()
    {
        // Create a category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'active' => true
        ]);

        $response = $this->get('/products/category/test-category');

        $response->assertStatus(200);
        $response->assertViewIs('products.category');
        $response->assertViewHas('slug', 'test-category');
    }

    /**
     * Test the API endpoint for featured products.
     *
     * @return void
     */
    public function test_featured_products_api_endpoint()
    {
        // Create a category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'active' => true
        ]);

        // Create featured products
        $featuredProducts = Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'active' => true,
            'featured' => true
        ]);

        // Create non-featured products
        $nonFeaturedProducts = Product::factory()->count(2)->create([
            'category_id' => $category->id,
            'active' => true,
            'featured' => false
        ]);

        $response = $this->getJson('/api/products/featured');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'products');
        $response->assertJsonStructure([
            'products' => [
                '*' => [
                    'id', 'name', 'slug', 'description', 'price', 'sku', 'stock_quantity',
                    'active', 'featured', 'created_at', 'updated_at'
                ]
            ]
        ]);
    }

    /**
     * Test the API endpoint for active categories.
     *
     * @return void
     */
    public function test_active_categories_api_endpoint()
    {
        // Create active categories
        $activeCategories = Category::factory()->count(3)->create([
            'active' => true
        ]);

        // Create inactive categories
        $inactiveCategories = Category::factory()->count(2)->create([
            'active' => false
        ]);

        $response = $this->getJson('/api/categories/active');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'categories');
        $response->assertJsonStructure([
            'categories' => [
                '*' => [
                    'id', 'name', 'slug', 'description', 'active', 'created_at', 'updated_at', 'product_count'
                ]
            ]
        ]);
    }

    /**
     * Test the API endpoint for products by category.
     *
     * @return void
     */
    public function test_products_by_category_api_endpoint()
    {
        // Create a category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'active' => true
        ]);

        // Create products in this category
        $products = Product::factory()->count(5)->create([
            'category_id' => $category->id,
            'active' => true
        ]);

        $response = $this->getJson('/api/products/category/test-category');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 'name', 'slug', 'description', 'price', 'sku', 'stock_quantity',
                    'active', 'featured', 'created_at', 'updated_at'
                ]
            ],
            'links',
            'meta'
        ]);
    }

    /**
     * Test the API endpoint for product details.
     *
     * @return void
     */
    public function test_product_details_api_endpoint()
    {
        // Create a category
        $category = Category::factory()->create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'active' => true
        ]);

        // Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'This is a test product',
            'category_id' => $category->id,
            'price' => 100.00,
            'sku' => 'TEST-SKU-001',
            'stock_quantity' => 10,
            'active' => true,
            'featured' => true
        ]);

        // Create product images
        ProductImage::factory()->create([
            'product_id' => $product->id,
            'image_path' => 'products/test-image.jpg',
            'alt_text' => 'Test Product Image',
            'is_primary' => true,
            'sort_order' => 1
        ]);

        // Create product details
        ProductDetail::factory()->create([
            'product_id' => $product->id,
            'benefits' => 'Test benefits',
            'usage' => 'Test usage',
            'ingredients' => 'Test ingredients',
            'dosage' => 'Test dosage',
            'side_effects' => 'Test side effects',
            'precautions' => 'Test precautions',
            'storage' => 'Test storage',
            'additional_info' => 'Test additional info'
        ]);

        $response = $this->getJson('/api/products/test-product');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'product' => [
                'id', 'name', 'slug', 'description', 'price', 'sku', 'stock_quantity',
                'active', 'featured', 'created_at', 'updated_at',
                'category' => ['id', 'name', 'slug'],
                'images' => [
                    '*' => ['id', 'image_path', 'alt_text', 'is_primary', 'sort_order']
                ],
                'productDetail' => [
                    'benefits', 'usage', 'ingredients', 'dosage', 'side_effects',
                    'precautions', 'storage', 'additional_info'
                ]
            ],
            'related_products'
        ]);
    }
}