@extends('layouts.app')

@section('title', 'Economic Homeo Pharmacy - Quality Homeopathic Medicines')

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">Quality Homeopathic Medicines</h1>
                <p class="lead">Trusted remedies for natural healing and wellness</p>
                <a href="/products" class="btn btn-light btn-lg mt-3">Explore Products</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="/images/hero-image.jpg" alt="Homeopathic Medicines" class="img-fluid rounded hero-image">
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<section class="featured-products py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Featured Products</h2>
                <p class="text-muted">Our most popular homeopathic remedies</p>
            </div>
        </div>
        
        <div class="text-center py-3" id="featured-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading featured products...</p>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="featured-products">
            <!-- Featured products will be loaded here -->
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="/products" class="btn btn-outline-primary">View All Products</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">Product Categories</h2>
                <p class="text-muted">Browse our extensive range of homeopathic products</p>
            </div>
        </div>
        
        <div class="text-center py-3" id="categories-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading categories...</p>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="categories-grid">
            <!-- Categories will be loaded here -->
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="/images/about-image.jpg" alt="About Economic Homeo Pharmacy" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h2 class="section-title">About Economic Homeo Pharmacy</h2>
                <p>Economic Homeo Pharmacy has been a trusted name in homeopathic medicine for over 25 years. We are committed to providing high-quality, effective remedies that support natural healing and wellness.</p>
                <p>Our products are carefully sourced and prepared according to traditional homeopathic principles, ensuring the highest standards of quality and efficacy.</p>
                <div class="d-flex mt-4">
                    <div class="me-4">
                        <h4 class="fw-bold text-primary">25+</h4>
                        <p class="text-muted">Years of Experience</p>
                    </div>
                    <div class="me-4">
                        <h4 class="fw-bold text-primary">1000+</h4>
                        <p class="text-muted">Products</p>
                    </div>
                    <div>
                        <h4 class="fw-bold text-primary">10000+</h4>
                        <p class="text-muted">Happy Customers</p>
                    </div>
                </div>
                <a href="/about" class="btn btn-outline-primary mt-3">Learn More</a>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title">What Our Customers Say</h2>
                <p class="text-muted">Read testimonials from our satisfied customers</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 testimonial-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">
                                <img src="/images/avatar-1.jpg" alt="Customer Avatar" class="rounded-circle">
                            </div>
                            <div>
                                <h5 class="mb-0">Rajesh Kumar</h5>
                                <p class="text-muted mb-0">Delhi</p>
                            </div>
                        </div>
                        <p class="card-text">"I've been using homeopathic remedies from Economic Homeo Pharmacy for years. Their products are effective and affordable. Highly recommended!"</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 testimonial-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">
                                <img src="/images/avatar-2.jpg" alt="Customer Avatar" class="rounded-circle">
                            </div>
                            <div>
                                <h5 class="mb-0">Priya Sharma</h5>
                                <p class="text-muted mb-0">Mumbai</p>
                            </div>
                        </div>
                        <p class="card-text">"The quality of medicines from Economic Homeo Pharmacy is exceptional. My family has experienced great results with their remedies for various health issues."</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 testimonial-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="testimonial-avatar me-3">
                                <img src="/images/avatar-3.jpg" alt="Customer Avatar" class="rounded-circle">
                            </div>
                            <div>
                                <h5 class="mb-0">Arun Patel</h5>
                                <p class="text-muted mb-0">Bangalore</p>
                            </div>
                        </div>
                        <p class="card-text">"I appreciate the detailed product information and fast delivery. The customer service team is very knowledgeable and helpful. Will continue to shop here!"</p>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="section-title">Subscribe to Our Newsletter</h2>
                <p>Stay updated with our latest products, offers, and health tips</p>
                <form class="mt-4">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email address" aria-label="Email address" aria-describedby="subscribe-button">
                        <button class="btn btn-light" type="button" id="subscribe-button">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load featured products
        loadFeaturedProducts();
        
        // Load categories
        loadCategories();
        
        // Newsletter subscription
        document.getElementById('subscribe-button').addEventListener('click', function() {
            const emailInput = this.previousElementSibling;
            const email = emailInput.value.trim();
            
            if (email && validateEmail(email)) {
                // Here you would implement your newsletter subscription logic
                // For now, just show an alert
                alert(`Thank you for subscribing with ${email}!`);
                emailInput.value = '';
            } else {
                alert('Please enter a valid email address.');
            }
        });
        
        // Function to load featured products
        function loadFeaturedProducts() {
            fetch('/api/products/featured')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load featured products');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading indicator
                    document.getElementById('featured-loading').style.display = 'none';
                    
                    const featuredProductsContainer = document.getElementById('featured-products');
                    
                    if (!data.products || data.products.length === 0) {
                        featuredProductsContainer.innerHTML = '<div class="col-12 text-center"><p>No featured products available at the moment.</p></div>';
                        return;
                    }
                    
                    // Render featured products
                    data.products.forEach(product => {
                        // Get product image or use placeholder
                        let imageUrl = '/images/placeholder.jpg';
                        let imageAlt = 'Product image';
                        if (product.images && product.images.length > 0) {
                            imageUrl = `/storage/${product.images[0].image_path}`;
                            imageAlt = product.images[0].alt_text || product.name;
                        }
                        
                        const productCard = document.createElement('div');
                        productCard.className = 'col';
                        productCard.innerHTML = `
                            <div class="card h-100 product-card">
                                <div class="featured-badge">Featured</div>
                                <img src="${imageUrl}" class="card-img-top" alt="${imageAlt}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text product-price">₹${parseFloat(product.price).toFixed(2)}</p>
                                    <p class="card-text small text-muted">${product.description.substring(0, 80)}${product.description.length > 80 ? '...' : ''}</p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="/products/${product.slug}" class="btn btn-primary w-100">View Details</a>
                                </div>
                            </div>
                        `;
                        
                        featuredProductsContainer.appendChild(productCard);
                    });
                })
                .catch(error => {
                    console.error('Error loading featured products:', error);
                    document.getElementById('featured-loading').style.display = 'none';
                    document.getElementById('featured-products').innerHTML = '<div class="col-12 text-center"><p>Failed to load featured products. Please try again later.</p></div>';
                });
        }
        
        // Function to load categories
        function loadCategories() {
            fetch('/api/categories/active')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load categories');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading indicator
                    document.getElementById('categories-loading').style.display = 'none';
                    
                    const categoriesGrid = document.getElementById('categories-grid');
                    
                    if (!data.categories || data.categories.length === 0) {
                        categoriesGrid.innerHTML = '<div class="col-12 text-center"><p>No categories available at the moment.</p></div>';
                        return;
                    }
                    
                    // Render categories
                    data.categories.forEach(category => {
                        const categoryCard = document.createElement('div');
                        categoryCard.className = 'col';
                        categoryCard.innerHTML = `
                            <div class="card h-100 category-card">
                                <div class="card-body text-center">
                                    <div class="category-icon mb-3">
                                        <i class="fas fa-leaf fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">${category.name}</h5>
                                    <p class="card-text text-muted">${category.description ? category.description.substring(0, 100) + (category.description.length > 100 ? '...' : '') : 'Browse our selection of products in this category.'}</p>
                                    <p class="text-primary">${category.product_count} Products</p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="/products/category/${category.slug}" class="btn btn-outline-primary w-100">Browse Category</a>
                                </div>
                            </div>
                        `;
                        
                        categoriesGrid.appendChild(categoryCard);
                    });
                })
                .catch(error => {
                    console.error('Error loading categories:', error);
                    document.getElementById('categories-loading').style.display = 'none';
                    document.getElementById('categories-grid').innerHTML = '<div class="col-12 text-center"><p>Failed to load categories. Please try again later.</p></div>';
                });
        }
        
        // Function to validate email
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
</script>
@endsection

@section('styles')
<style>
    .hero-image {
        max-height: 400px;
        object-fit: cover;
    }
    
    .section-title {
        position: relative;
        margin-bottom: 1.5rem;
        font-weight: 700;
    }
    
    .section-title:after {
        content: '';
        display: block;
        width: 50px;
        height: 3px;
        background-color: #0d6efd;
        margin: 15px auto 0;
    }
    
    .product-card, .category-card, .testimonial-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .product-card:hover, .category-card:hover, .testimonial-card:hover {
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
    
    .featured-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ffc107;
        color: #212529;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 0.8rem;
        font-weight: bold;
        z-index: 1;
    }
    
    .category-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: rgba(13, 110, 253, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .testimonial-avatar img {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
    
    .about-section img {
        max-height: 400px;
        object-fit: cover;
    }
</style>
@endsection