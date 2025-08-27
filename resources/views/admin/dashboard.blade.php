@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="product-count">Loading...</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="category-count">Loading...</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Featured Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="featured-count">Loading...</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Products Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Products</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="active-count">Loading...</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Recent Products -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Products</h6>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="recent-products">
                                <tr>
                                    <td colspan="4" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Categories -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Categories</h6>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="recent-categories">
                                <tr>
                                    <td colspan="4" class="text-center">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
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
        // Get token from localStorage
        const token = localStorage.getItem('admin_token');
        
        if (!token) {
            window.location.href = '{{ route("admin.login") }}';
            return;
        }
        
        // Fetch dashboard data
        fetchDashboardData();
        
        function fetchDashboardData() {
            // Fetch product count
            fetch('/api/admin/products/count', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('product-count').textContent = data.total || 0;
                document.getElementById('featured-count').textContent = data.featured || 0;
                document.getElementById('active-count').textContent = data.active || 0;
            })
            .catch(error => {
                console.error('Error fetching product counts:', error);
                document.getElementById('product-count').textContent = 'Error';
                document.getElementById('featured-count').textContent = 'Error';
                document.getElementById('active-count').textContent = 'Error';
            });
            
            // Fetch category count
            fetch('/api/admin/categories/count', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('category-count').textContent = data.total || 0;
            })
            .catch(error => {
                console.error('Error fetching category count:', error);
                document.getElementById('category-count').textContent = 'Error';
            });
            
            // Fetch recent products
            fetch('/api/admin/products?per_page=5', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const recentProductsTable = document.getElementById('recent-products');
                recentProductsTable.innerHTML = '';
                
                if (data.data && data.data.length > 0) {
                    data.data.forEach(product => {
                        recentProductsTable.innerHTML += `
                            <tr>
                                <td>${product.name}</td>
                                <td>₹${product.price}</td>
                                <td>
                                    <span class="badge badge-${product.active ? 'success' : 'danger'}">
                                        ${product.active ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/products/${product.id}/edit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    recentProductsTable.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center">No products found</td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching recent products:', error);
                document.getElementById('recent-products').innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center">Error loading products</td>
                    </tr>
                `;
            });
            
            // Fetch recent categories
            fetch('/api/admin/categories?per_page=5', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const recentCategoriesTable = document.getElementById('recent-categories');
                recentCategoriesTable.innerHTML = '';
                
                if (data.data && data.data.length > 0) {
                    data.data.forEach(category => {
                        recentCategoriesTable.innerHTML += `
                            <tr>
                                <td>${category.name}</td>
                                <td>${category.products_count || 0}</td>
                                <td>
                                    <span class="badge badge-${category.active ? 'success' : 'danger'}">
                                        ${category.active ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/categories/${category.id}/edit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    recentCategoriesTable.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center">No categories found</td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching recent categories:', error);
                document.getElementById('recent-categories').innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center">Error loading categories</td>
                    </tr>
                `;
            });
        }
    });
</script>
@endsection