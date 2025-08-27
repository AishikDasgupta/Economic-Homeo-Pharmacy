@extends('layouts.app')

@section('title', 'Products by Category - Economic Homeo Pharmacy')

@section('content')
<div class="container py-5">
    <div class="text-center py-5" id="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading category products...</p>
    </div>

    <div id="category-not-found" class="text-center py-5" style="display: none;">
        <div class="alert alert-warning">
            <h4>Category Not Found</h4>
            <p>The category you are looking for does not exist or has been removed.</p>
            <a href="/products" class="btn btn-primary mt-3">Browse All Products</a>
        </div>
    </div>

    <div id="category-content" style="display: none;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/products">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page" id="category-name-breadcrumb"></li>
            </ol>
        </nav>

        <div class="row mb-4">
            <div class="col-md-8">
                <h1 id="category-name" class="mb-2"></h1>
                <p id="category-description" class="text-muted"></p>
            </div>
            <div class="col-md-4 text-md-end">
                <p class="mb-0"><span id="product-count">0</span> products found</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Filters</h5>
                    </div>
                    <div class="card-body">
                        <!-- Price Range Filter -->
                        <div class="mb-4">
                            <h6>Price Range</h6>
                            <div class="d-flex align-items-center">
                                <input type="number" id="min-price" class="form-control form-control-sm me-2" placeholder="Min">
                                <span>-</span>
                                <input type="number" id="max-price" class="form-control form-control-sm ms-2" placeholder="Max">
                            </div>
                            <button id="apply-price-filter" class="btn btn-sm btn-outline-primary mt-2">Apply</button>
                        </div>

                        <!-- Sort Options -->
                        <div>
                            <h6>Sort By</h6>
                            <select id="sort-options" class="form-select form-select-sm">
                                <option value="name_asc">Name (A-Z)</option>
                                <option value="name_desc">Name (Z-A)</option>
                                <option value="price_asc">Price (Low to High)</option>
                                <option value="price_desc">Price (High to Low)</option>
                                <option value="newest">Newest First</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-md-9">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="products-grid">
                    <!-- Products will be loaded here -->
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Product pagination">
                        <ul class="pagination" id="pagination">
                            <!-- Pagination will be loaded here -->
                        </ul>
                    </nav>
                </div>

                <!-- No Products Message -->
                <div id="no-products" class="text-center py-5" style="display: none;">
                    <div class="alert alert-info">
                        <h4>No Products Found</h4>
                        <p>There are no products matching your criteria in this category.</p>
                        <button id="reset-filters" class="btn btn-primary mt-3">Reset Filters</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get category slug from URL
        const pathParts = window.location.pathname.split('/');
        const categorySlug = pathParts[pathParts.length - 1];
        
        // Initialize filters
        let currentPage = 1;
        let minPrice = '';
        let maxPrice = '';
        let sortBy = 'name_asc';
        
        // Load category details and products
        loadCategoryDetails(categorySlug);
        
        // Event listeners for filters
        document.getElementById('apply-price-filter').addEventListener('click', function() {
            minPrice = document.getElementById('min-price').value;
            maxPrice = document.getElementById('max-price').value;
            currentPage = 1;
            loadCategoryProducts(categorySlug, currentPage, minPrice, maxPrice, sortBy);
        });
        
        document.getElementById('sort-options').addEventListener('change', function() {
            sortBy = this.value;
            currentPage = 1;
            loadCategoryProducts(categorySlug, currentPage, minPrice, maxPrice, sortBy);
        });
        
        document.getElementById('reset-filters').addEventListener('click', function() {
            document.getElementById('min-price').value = '';
            document.getElementById('max-price').value = '';
            document.getElementById('sort-options').value = 'name_asc';
            minPrice = '';
            maxPrice = '';
            sortBy = 'name_asc';
            currentPage = 1;
            loadCategoryProducts(categorySlug, currentPage, minPrice, maxPrice, sortBy);
        });
        
        // Function to load category details
        function loadCategoryDetails(slug) {
            fetch(`/api/categories/${slug}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Category not found');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update page title
                    document.title = `${data.category.name} - Economic Homeo Pharmacy`;
                    
                    // Update category details
                    document.getElementById('category-name').textContent = data.category.name;
                    document.getElementById('category-name-breadcrumb').textContent = data.category.name;
                    document.getElementById('category-description').textContent = data.category.description || 'Browse our selection of products in this category.';
                    
                    // Show category content
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('category-content').style.display = 'block';
                    
                    // Load category products
                    loadCategoryProducts(slug, currentPage, minPrice, maxPrice, sortBy);
                })
                .catch(error => {
                    console.error('Error loading category details:', error);
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('category-not-found').style.display = 'block';
                });
        }
        
        // Function to load category products
        function loadCategoryProducts(slug, page, minPrice, maxPrice, sortBy) {
            // Build query parameters
            let queryParams = `page=${page}`;
            if (minPrice) queryParams += `&min_price=${minPrice}`;
            if (maxPrice) queryParams += `&max_price=${maxPrice}`;
            if (sortBy) {
                const [sortField, sortDirection] = sortBy.split('_');
                queryParams += `&sort_by=${sortField}&sort_direction=${sortDirection || 'asc'}`;
            }
            
            fetch(`/api/products/category/${slug}?${queryParams}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load products');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update product count
                    document.getElementById('product-count').textContent = data.meta.total;
                    
                    // Display products or no products message
                    if (data.data.length === 0) {
                        document.getElementById('products-grid').style.display = 'none';
                        document.getElementById('pagination').style.display = 'none';
                        document.getElementById('no-products').style.display = 'block';
                    } else {
                        document.getElementById('products-grid').style.display = 'flex';
                        document.getElementById('pagination').style.display = 'flex';
                        document.getElementById('no-products').style.display = 'none';
                        
                        // Render products
                        renderProducts(data.data);
                        
                        // Render pagination
                        renderPagination(data.meta, data.links);
                    }
                })
                .catch(error => {
                    console.error('Error loading category products:', error);
                    document.getElementById('products-grid').innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <p>Failed to load products. Please try again later.</p>
                            </div>
                        </div>
                    `;
                });
        }
        
        // Function to render products
        function renderProducts(products) {
            const productsGrid = document.getElementById('products-grid');
            productsGrid.innerHTML = '';
            
            products.forEach(product => {
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
                        <img src="${imageUrl}" class="card-img-top" alt="${imageAlt}">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text product-price">₹${parseFloat(product.price).toFixed(2)}</p>
                            <p class="card-text small text-muted">${product.description.substring(0, 100)}${product.description.length > 100 ? '...' : ''}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="/products/${product.slug}" class="btn btn-primary w-100">View Details</a>
                        </div>
                    </div>
                `;
                
                productsGrid.appendChild(productCard);
            });
        }
        
        // Function to render pagination
        function renderPagination(meta, links) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            
            // Previous page link
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${meta.current_page === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" data-page="${meta.current_page - 1}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>`;
            pagination.appendChild(prevLi);
            
            // Page links
            for (let i = 1; i <= meta.last_page; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === meta.current_page ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                pagination.appendChild(pageLi);
            }
            
            // Next page link
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${meta.current_page === meta.last_page ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" data-page="${meta.current_page + 1}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>`;
            pagination.appendChild(nextLi);
            
            // Add event listeners to pagination links
            document.querySelectorAll('#pagination .page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));
                    if (page >= 1 && page <= meta.last_page) {
                        currentPage = page;
                        loadCategoryProducts(categorySlug, currentPage, minPrice, maxPrice, sortBy);
                        // Scroll to top of products
                        document.getElementById('products-grid').scrollIntoView({ behavior: 'smooth' });
                    }
                });
            });
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