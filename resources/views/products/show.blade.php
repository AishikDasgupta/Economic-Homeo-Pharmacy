@extends('layouts.app')

@section('title', 'Product Details - Economic Homeo Pharmacy')

@section('content')
<div class="container py-5" id="product-detail-container">
    <div class="text-center py-5" id="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading product details...</p>
    </div>

    <div id="product-not-found" class="text-center py-5" style="display: none;">
        <div class="alert alert-warning">
            <h4>Product Not Found</h4>
            <p>The product you are looking for does not exist or has been removed.</p>
            <a href="/products" class="btn btn-primary mt-3">Browse All Products</a>
        </div>
    </div>

    <div id="product-content" style="display: none;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item"><a href="#" id="category-link"></a></li>
                <li class="breadcrumb-item active" aria-current="page" id="product-name-breadcrumb"></li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Images -->
            <div class="col-md-6 mb-4">
                <div class="product-image-container">
                    <img id="main-product-image" src="" alt="" class="img-fluid rounded main-image">
                </div>
                <div class="row mt-3" id="product-thumbnails">
                    <!-- Thumbnails will be loaded here -->
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <h1 id="product-name" class="mb-2"></h1>
                <p class="text-muted mb-2">Category: <span id="product-category"></span></p>
                <p class="text-muted mb-2">SKU: <span id="product-sku"></span></p>
                
                <div class="d-flex align-items-center mb-3">
                    <h2 id="product-price" class="text-primary mb-0 me-3"></h2>
                    <span id="product-stock-status" class="badge bg-success">In Stock</span>
                </div>
                
                <div class="mb-4" id="product-description"></div>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="input-group me-3" style="width: 130px;">
                        <button class="btn btn-outline-secondary" type="button" id="decrease-quantity">-</button>
                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1">
                        <button class="btn btn-outline-secondary" type="button" id="increase-quantity">+</button>
                    </div>
                    <button id="add-to-cart" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Details Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="benefits-tab" data-bs-toggle="tab" data-bs-target="#benefits" type="button" role="tab" aria-controls="benefits" aria-selected="true">Benefits</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="usage-tab" data-bs-toggle="tab" data-bs-target="#usage" type="button" role="tab" aria-controls="usage" aria-selected="false">Usage</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab" aria-controls="ingredients" aria-selected="false">Ingredients</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="dosage-tab" data-bs-toggle="tab" data-bs-target="#dosage" type="button" role="tab" aria-controls="dosage" aria-selected="false">Dosage</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="precautions-tab" data-bs-toggle="tab" data-bs-target="#precautions" type="button" role="tab" aria-controls="precautions" aria-selected="false">Precautions</button>
                    </li>
                </ul>
                <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabsContent">
                    <div class="tab-pane fade show active" id="benefits" role="tabpanel" aria-labelledby="benefits-tab"></div>
                    <div class="tab-pane fade" id="usage" role="tabpanel" aria-labelledby="usage-tab"></div>
                    <div class="tab-pane fade" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab"></div>
                    <div class="tab-pane fade" id="dosage" role="tabpanel" aria-labelledby="dosage-tab"></div>
                    <div class="tab-pane fade" id="precautions" role="tabpanel" aria-labelledby="precautions-tab"></div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Related Products</h3>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="related-products">
                    <!-- Related products will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get product slug from URL
        const pathParts = window.location.pathname.split('/');
        const productSlug = pathParts[pathParts.length - 1];
        
        // Load product details
        loadProductDetails(productSlug);
        
        // Event listeners for quantity buttons
        document.getElementById('decrease-quantity').addEventListener('click', function() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        document.getElementById('increase-quantity').addEventListener('click', function() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value);
            const maxStock = parseInt(quantityInput.getAttribute('max') || 100);
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
            }
        });
        
        // Add to cart button event listener
        document.getElementById('add-to-cart').addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantity = document.getElementById('quantity').value;
            
            // Here you would implement your cart functionality
            // For now, just show an alert
            alert(`Added ${quantity} item(s) to cart`);
        });
        
        // Function to load product details
        function loadProductDetails(slug) {
            fetch(`/api/products/${slug}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Product not found');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading indicator
                    document.getElementById('loading').style.display = 'none';
                    
                    // Show product content
                    document.getElementById('product-content').style.display = 'block';
                    
                    const product = data.product;
                    
                    // Update page title
                    document.title = `${product.name} - Economic Homeo Pharmacy`;
                    
                    // Update breadcrumb
                    document.getElementById('product-name-breadcrumb').textContent = product.name;
                    document.getElementById('category-link').textContent = product.category.name;
                    document.getElementById('category-link').href = `/products/category/${product.category.slug}`;
                    
                    // Update product details
                    document.getElementById('product-name').textContent = product.name;
                    document.getElementById('product-category').textContent = product.category.name;
                    document.getElementById('product-sku').textContent = product.sku;
                    document.getElementById('product-price').textContent = `₹${parseFloat(product.price).toFixed(2)}`;
                    document.getElementById('product-description').innerHTML = product.description;
                    
                    // Update stock status
                    const stockStatusElement = document.getElementById('product-stock-status');
                    if (product.stock_quantity > 0) {
                        stockStatusElement.textContent = 'In Stock';
                        stockStatusElement.className = 'badge bg-success';
                        document.getElementById('quantity').setAttribute('max', product.stock_quantity);
                        document.getElementById('add-to-cart').disabled = false;
                    } else {
                        stockStatusElement.textContent = 'Out of Stock';
                        stockStatusElement.className = 'badge bg-danger';
                        document.getElementById('add-to-cart').disabled = true;
                    }
                    
                    // Set product ID for add to cart button
                    document.getElementById('add-to-cart').setAttribute('data-product-id', product.id);
                    
                    // Load product images
                    loadProductImages(product.images);
                    
                    // Load product details tabs
                    if (product.productDetail) {
                        const detail = product.productDetail;
                        document.getElementById('benefits').innerHTML = detail.benefits || '<p>No information available</p>';
                        document.getElementById('usage').innerHTML = detail.usage || '<p>No information available</p>';
                        document.getElementById('ingredients').innerHTML = detail.ingredients || '<p>No information available</p>';
                        document.getElementById('dosage').innerHTML = detail.dosage || '<p>No information available</p>';
                        document.getElementById('precautions').innerHTML = detail.precautions || '<p>No information available</p>';
                    }
                    
                    // Load related products
                    loadRelatedProducts(data.related_products);
                })
                .catch(error => {
                    console.error('Error loading product details:', error);
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('product-not-found').style.display = 'block';
                });
        }
        
        // Function to load product images
        function loadProductImages(images) {
            if (!images || images.length === 0) {
                // Use placeholder image if no images available
                document.getElementById('main-product-image').src = '/images/placeholder.jpg';
                document.getElementById('main-product-image').alt = 'Product image';
                return;
            }
            
            // Set main image to first image (which should be primary)
            document.getElementById('main-product-image').src = `/storage/${images[0].image_path}`;
            document.getElementById('main-product-image').alt = images[0].alt_text;
            
            // Create thumbnails
            const thumbnailsContainer = document.getElementById('product-thumbnails');
            thumbnailsContainer.innerHTML = '';
            
            images.forEach((image, index) => {
                const col = document.createElement('div');
                col.className = 'col-3';
                
                const thumbnail = document.createElement('img');
                thumbnail.src = `/storage/${image.image_path}`;
                thumbnail.alt = image.alt_text;
                thumbnail.className = `img-thumbnail ${index === 0 ? 'active' : ''}`;
                thumbnail.style.cursor = 'pointer';
                
                thumbnail.addEventListener('click', function() {
                    // Update main image
                    document.getElementById('main-product-image').src = this.src;
                    document.getElementById('main-product-image').alt = this.alt;
                    
                    // Update active thumbnail
                    document.querySelectorAll('#product-thumbnails .img-thumbnail').forEach(thumb => {
                        thumb.classList.remove('active');
                    });
                    this.classList.add('active');
                });
                
                col.appendChild(thumbnail);
                thumbnailsContainer.appendChild(col);
            });
        }
        
        // Function to load related products
        function loadRelatedProducts(relatedProducts) {
            const relatedProductsContainer = document.getElementById('related-products');
            relatedProductsContainer.innerHTML = '';
            
            if (!relatedProducts || relatedProducts.length === 0) {
                relatedProductsContainer.innerHTML = '<div class="col-12"><p>No related products found</p></div>';
                return;
            }
            
            relatedProducts.forEach(product => {
                const col = document.createElement('div');
                col.className = 'col';
                
                // Get product image or use placeholder
                let imageUrl = '/images/placeholder.jpg';
                let imageAlt = 'Product image';
                if (product.images && product.images.length > 0) {
                    imageUrl = `/storage/${product.images[0].image_path}`;
                    imageAlt = product.images[0].alt_text || product.name;
                }
                
                col.innerHTML = `
                    <div class="card h-100 product-card">
                        <img src="${imageUrl}" class="card-img-top" alt="${imageAlt}">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text product-price">₹${parseFloat(product.price).toFixed(2)}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="/products/${product.slug}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                `;
                
                relatedProductsContainer.appendChild(col);
            });
        }
    });
</script>
@endsection

@section('styles')
<style>
    .main-image {
        width: 100%;
        height: 400px;
        object-fit: contain;
        background-color: #f8f9fa;
    }
    
    .img-thumbnail.active {
        border-color: #0d6efd;
    }
    
    #product-thumbnails img {
        height: 80px;
        object-fit: cover;
    }
    
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .product-card .card-img-top {
        height: 180px;
        object-fit: cover;
    }
    
    .product-price {
        font-weight: bold;
        color: #28a745;
        font-size: 1.2rem;
    }
</style>
@endsection