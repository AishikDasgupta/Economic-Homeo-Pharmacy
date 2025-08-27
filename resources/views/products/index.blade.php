@extends('layouts.app')

@section('title', 'Products - Economic Homeo Pharmacy')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Our Products</h1>
            <p class="text-muted">Browse our wide range of homeopathic medicines</p>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" id="search-input" class="form-control" placeholder="Search products...">
                <button class="btn btn-primary" id="search-button" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <!-- Categories Filter -->
                    <h6 class="font-weight-bold">Categories</h6>
                    <div id="category-filters" class="mb-4">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Price Range Filter -->
                    <h6 class="font-weight-bold">Price Range</h6>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>₹<span id="min-price-display">0</span></span>
                            <span>₹<span id="max-price-display">5000</span></span>
                        </div>
                        <div class="range-slider">
                            <input type="range" class="form-range" id="min-price" min="0" max="5000" step="100" value="0">
                            <input type="range" class="form-range" id="max-price" min="0" max="5000" step="100" value="5000">
                        </div>
                    </div>

                    <!-- Sort By Filter -->
                    <h6 class="font-weight-bold">Sort By</h6>
                    <div class="mb-4">
                        <select id="sort-by" class="form-select">
                            <option value="created_at-desc">Newest First</option>
                            <option value="created_at-asc">Oldest First</option>
                            <option value="price-asc">Price: Low to High</option>
                            <option value="price-desc">Price: High to Low</option>
                            <option value="name-asc">Name: A to Z</option>
                            <option value="name-desc">Name: Z to A</option>
                        </select>
                    </div>

                    <button id="apply-filters" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div id="products-container" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <!-- Products will be loaded here -->
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading products...</p>
                </div>
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="d-flex justify-content-center mt-5">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let currentPage = 1;
        let searchQuery = '';
        let categoryId = '';
        let minPrice = 0;
        let maxPrice = 5000;
        let sortBy = 'created_at';
        let sortOrder = 'desc';

        // Load categories
        loadCategories();

        // Load initial products
        loadProducts();

        // Event listeners
        document.getElementById('search-button').addEventListener('click', function() {
            searchQuery = document.getElementById('search-input').value;
            currentPage = 1;
            loadProducts();
        });

        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchQuery = document.getElementById('search-input').value;
                currentPage = 1;
                loadProducts();
            }
        });

        document.getElementById('min-price').addEventListener('input', function() {
            minPrice = this.value;
            document.getElementById('min-price-display').textContent = minPrice;
        });

        document.getElementById('max-price').addEventListener('input', function() {
            maxPrice = this.value;
            document.getElementById('max-price-display').textContent = maxPrice;
        });

        document.getElementById('sort-by').addEventListener('change', function() {
            const sortValue = this.value.split('-');
            sortBy = sortValue[0];
            sortOrder = sortValue[1];
        });

        document.getElementById('apply-filters').addEventListener('click', function() {
            currentPage = 1;
            loadProducts();
        });

        // Load categories function
        function loadCategories() {
            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    const categoryFiltersContainer = document.getElementById('category-filters');
                    categoryFiltersContainer.innerHTML = '';

                    // Add "All Categories" option
                    const allCategoriesDiv = document.createElement('div');
                    allCategoriesDiv.className = 'form-check';
                    allCategoriesDiv.innerHTML = `
                        <input class="form-check-input category-filter" type="radio" name="category" id="category-all" value="" checked>
                        <label class="form-check-label" for="category-all">All Categories</label>
                    `;
                    categoryFiltersContainer.appendChild(allCategoriesDiv);

                    // Add each category
                    data.forEach(category => {
                        const categoryDiv = document.createElement('div');
                        categoryDiv.className = 'form-check';
                        categoryDiv.innerHTML = `
                            <input class="form-check-input category-filter" type="radio" name="category" id="category-${category.id}" value="${category.id}">
                            <label class="form-check-label" for="category-${category.id}">${category.name}</label>
                        `;
                        categoryFiltersContainer.appendChild(categoryDiv);
                    });

                    // Add event listeners to category filters
                    document.querySelectorAll('.category-filter').forEach(filter => {
                        filter.addEventListener('change', function() {
                            categoryId = this.value;
                        });
                    });
                })
                .catch(error => {
                    console.error('Error loading categories:', error);
                    document.getElementById('category-filters').innerHTML = '<p class="text-danger">Failed to load categories</p>';
                });
        }

        // Load products function
        function loadProducts() {
            const productsContainer = document.getElementById('products-container');
            productsContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading products...</p>
                </div>
            `;

            // Build query parameters
            const params = new URLSearchParams();
            params.append('page', currentPage);
            if (searchQuery) params.append('search', searchQuery);
            if (categoryId) params.append('category_id', categoryId);
            params.append('min_price', minPrice);
            params.append('max_price', maxPrice);
            params.append('sort_by', sortBy);
            params.append('sort_order', sortOrder);

            fetch(`/api/products?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    productsContainer.innerHTML = '';

                    if (data.data.length === 0) {
                        productsContainer.innerHTML = `
                            <div class="col-12 text-center py-5">
                                <p>No products found. Try adjusting your filters.</p>
                            </div>
                        `;
                        return;
                    }

                    // Render products
                    data.data.forEach(product => {
                        const productCard = document.createElement('div');
                        productCard.className = 'col';
                        
                        // Get product image or use placeholder
                        let imageUrl = '/images/placeholder.jpg';
                        let imageAlt = 'Product image';
                        if (product.images && product.images.length > 0) {
                            imageUrl = `/storage/${product.images[0].image_path}`;
                            imageAlt = product.images[0].alt_text || product.name;
                        }

                        productCard.innerHTML = `
                            <div class="card h-100 product-card">
                                <img src="${imageUrl}" class="card-img-top" alt="${imageAlt}">
                                <div class="card-body">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text text-muted small">${product.category.name}</p>
                                    <p class="card-text product-price">₹${parseFloat(product.price).toFixed(2)}</p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="/products/${product.slug}" class="btn btn-primary w-100">View Details</a>
                                </div>
                            </div>
                        `;
                        productsContainer.appendChild(productCard);
                    });

                    // Render pagination
                    renderPagination(data);
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    productsContainer.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <p class="text-danger">Failed to load products. Please try again later.</p>
                        </div>
                    `;
                });
        }

        // Render pagination function
        function renderPagination(data) {
            const paginationContainer = document.getElementById('pagination-container');
            paginationContainer.innerHTML = '';

            if (data.last_page <= 1) return;

            const pagination = document.createElement('nav');
            pagination.setAttribute('aria-label', 'Product pagination');

            const paginationList = document.createElement('ul');
            paginationList.className = 'pagination';

            // Previous button
            const previousLi = document.createElement('li');
            previousLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
            previousLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Previous" ${data.current_page === 1 ? 'tabindex="-1" aria-disabled="true"' : ''}>
                    <span aria-hidden="true">&laquo;</span>
                </a>
            `;
            if (data.current_page > 1) {
                previousLi.querySelector('a').addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = data.current_page - 1;
                    loadProducts();
                });
            }
            paginationList.appendChild(previousLi);

            // Page numbers
            const startPage = Math.max(1, data.current_page - 2);
            const endPage = Math.min(data.last_page, data.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === data.current_page ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageLi.querySelector('a').addEventListener('click', function(e) {
                    e.preventDefault();
                    if (i !== data.current_page) {
                        currentPage = i;
                        loadProducts();
                    }
                });
                paginationList.appendChild(pageLi);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
            nextLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Next" ${data.current_page === data.last_page ? 'tabindex="-1" aria-disabled="true"' : ''}>
                    <span aria-hidden="true">&raquo;</span>
                </a>
            `;
            if (data.current_page < data.last_page) {
                nextLi.querySelector('a').addEventListener('click', function(e) {
                    e.preventDefault();
                    currentPage = data.current_page + 1;
                    loadProducts();
                });
            }
            paginationList.appendChild(nextLi);

            pagination.appendChild(paginationList);
            paginationContainer.appendChild(pagination);
        }
    });
</script>
@endsection

@section('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .product-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    
    .product-price {
        font-weight: bold;
        color: #28a745;
        font-size: 1.2rem;
    }
    
    .range-slider {
        position: relative;
        width: 100%;
        height: 30px;
    }
    
    .range-slider input[type="range"] {
        position: absolute;
        width: 100%;
        pointer-events: none;
        -webkit-appearance: none;
        z-index: 2;
        height: 10px;
        opacity: 0;
    }
    
    .range-slider input[type="range"]::-webkit-slider-thumb {
        pointer-events: all;
        width: 20px;
        height: 20px;
        border-radius: 0;
        border: 0 none;
        background-color: red;
        -webkit-appearance: none;
    }
</style>
@endsection