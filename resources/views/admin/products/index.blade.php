@extends('admin.layouts.app')

@section('title', 'Products')

@section('styles')
<style>
    .product-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    .featured-star {
        color: #f8c200;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        line-height: 32px;
        text-align: center;
        margin-right: 5px;
    }
    .filters-card {
        margin-bottom: 1.5rem;
    }
    .pagination-info {
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add New Product
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow filters-card">
        <div class="card-body">
            <form id="filters-form" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" placeholder="Search by name or SKU">
                </div>
                <div class="col-md-2">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category">
                        <option value="">All Categories</option>
                        <!-- Categories will be loaded dynamically -->
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="featured" class="form-label">Featured</label>
                    <select class="form-control" id="featured">
                        <option value="">All Products</option>
                        <option value="1">Featured Only</option>
                        <option value="0">Non-Featured Only</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <button type="button" id="reset-filters" class="btn btn-secondary">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Products</h6>
            <div class="bulk-actions">
                <select class="form-control form-control-sm d-inline-block w-auto me-2" id="bulk-action">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="feature">Mark as Featured</option>
                    <option value="unfeature">Remove from Featured</option>
                    <option value="delete">Delete</option>
                </select>
                <button class="btn btn-sm btn-primary" id="apply-bulk-action" disabled>
                    Apply
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="40px">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="select-all">
                                </div>
                            </th>
                            <th width="70px">Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th width="80px">Status</th>
                            <th width="60px">Featured</th>
                            <th width="120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="products-table-body">
                        <!-- Products will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="pagination-info" id="pagination-info"></div>
                </div>
                <div class="col-md-6">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this product? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkDeleteModalLabel">Confirm Bulk Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the selected products? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-delete">Delete</button>
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
        
        // Variables for pagination
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 10;
        
        // Variables for filters
        let searchQuery = '';
        let categoryFilter = '';
        let statusFilter = '';
        let featuredFilter = '';
        
        // Load categories for filter
        loadCategories();
        
        // Initial products load
        loadProducts();
        
        // Handle filter form submission
        document.getElementById('filters-form').addEventListener('submit', function(e) {
            e.preventDefault();
            searchQuery = document.getElementById('search').value;
            categoryFilter = document.getElementById('category').value;
            statusFilter = document.getElementById('status').value;
            featuredFilter = document.getElementById('featured').value;
            currentPage = 1; // Reset to first page when filtering
            loadProducts();
        });
        
        // Handle reset filters
        document.getElementById('reset-filters').addEventListener('click', function() {
            document.getElementById('search').value = '';
            document.getElementById('category').value = '';
            document.getElementById('status').value = '';
            document.getElementById('featured').value = '';
            searchQuery = '';
            categoryFilter = '';
            statusFilter = '';
            featuredFilter = '';
            currentPage = 1;
            loadProducts();
        });
        
        // Handle select all checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
        
        // Handle bulk action button
        document.getElementById('apply-bulk-action').addEventListener('click', function() {
            const action = document.getElementById('bulk-action').value;
            if (!action) return;
            
            const selectedProducts = getSelectedProducts();
            if (selectedProducts.length === 0) return;
            
            if (action === 'delete') {
                // Show confirmation modal for delete
                const bulkDeleteModal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
                bulkDeleteModal.show();
            } else {
                // Process other bulk actions
                processBulkAction(action, selectedProducts);
            }
        });
        
        // Handle bulk delete confirmation
        document.getElementById('confirm-bulk-delete').addEventListener('click', function() {
            const selectedProducts = getSelectedProducts();
            processBulkAction('delete', selectedProducts);
            const bulkDeleteModal = bootstrap.Modal.getInstance(document.getElementById('bulkDeleteModal'));
            bulkDeleteModal.hide();
        });
        
        // Function to load categories
        function loadCategories() {
            fetch('/api/admin/categories', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const categorySelect = document.getElementById('category');
                
                if (data.data && data.data.length > 0) {
                    data.data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        categorySelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading categories:', error);
            });
        }
        
        // Function to load products
        function loadProducts() {
            // Show loading state
            document.getElementById('products-table-body').innerHTML = `
                <tr>
                    <td colspan="10" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </td>
                </tr>
            `;
            
            // Build query parameters
            let queryParams = `page=${currentPage}&per_page=${perPage}`;
            if (searchQuery) queryParams += `&search=${encodeURIComponent(searchQuery)}`;
            if (categoryFilter) queryParams += `&category_id=${categoryFilter}`;
            if (statusFilter !== '') queryParams += `&active=${statusFilter}`;
            if (featuredFilter !== '') queryParams += `&featured=${featuredFilter}`;
            
            fetch(`/api/admin/products?${queryParams}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                renderProducts(data);
                renderPagination(data);
            })
            .catch(error => {
                console.error('Error loading products:', error);
                document.getElementById('products-table-body').innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center text-danger">
                            Error loading products. Please try again.
                        </td>
                    </tr>
                `;
            });
        }
        
        // Function to render products
        function renderProducts(data) {
            const tableBody = document.getElementById('products-table-body');
            tableBody.innerHTML = '';
            
            if (!data.data || data.data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" class="text-center">
                            No products found.
                        </td>
                    </tr>
                `;
                return;
            }
            
            data.data.forEach(product => {
                const row = document.createElement('tr');
                
                // Default image if no images
                const imageSrc = product.images && product.images.length > 0 
                    ? product.images[0].image_url 
                    : '/assets/img/no-image.jpg';
                
                // Status badge class
                const statusClass = product.active ? 'bg-success' : 'bg-secondary';
                const statusText = product.active ? 'Active' : 'Inactive';
                
                // Featured star
                const featuredIcon = product.featured 
                    ? '<i class="fas fa-star featured-star"></i>' 
                    : '<i class="far fa-star"></i>';
                
                row.innerHTML = `
                    <td>
                        <div class="form-check">
                            <input class="form-check-input product-checkbox" type="checkbox" value="${product.id}">
                        </div>
                    </td>
                    <td>
                        <img src="${imageSrc}" alt="${product.name}" class="product-thumbnail">
                    </td>
                    <td>${product.name}</td>
                    <td>${product.sku}</td>
                    <td>${product.category ? product.category.name : 'N/A'}</td>
                    <td>₹${parseFloat(product.price).toFixed(2)}</td>
                    <td>${product.stock_quantity}</td>
                    <td>
                        <span class="badge ${statusClass} status-badge">${statusText}</span>
                    </td>
                    <td class="text-center">
                        ${featuredIcon}
                    </td>
                    <td>
                        <a href="{{ route('admin.products.edit') }}/${product.id}" class="btn btn-sm btn-primary action-btn" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger action-btn delete-product" data-id="${product.id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
            
            // Add event listeners to checkboxes
            document.querySelectorAll('.product-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActionButton);
            });
            
            // Add event listeners to delete buttons
            document.querySelectorAll('.delete-product').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    showDeleteConfirmation(productId);
                });
            });
        }
        
        // Function to render pagination
        function renderPagination(data) {
            const paginationInfo = document.getElementById('pagination-info');
            const pagination = document.getElementById('pagination');
            
            // Update pagination info
            const from = data.meta.from || 0;
            const to = data.meta.to || 0;
            const total = data.meta.total || 0;
            
            paginationInfo.textContent = `Showing ${from} to ${to} of ${total} products`;
            
            // Update pagination links
            pagination.innerHTML = '';
            
            // Calculate total pages
            totalPages = data.meta.last_page || 1;
            currentPage = data.meta.current_page || 1;
            
            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>`;
            pagination.appendChild(prevLi);
            
            // Page numbers
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const pageLi = document.createElement('li');
                pageLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
                pageLi.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                pagination.appendChild(pageLi);
            }
            
            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>`;
            pagination.appendChild(nextLi);
            
            // Add event listeners to pagination links
            document.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));
                    if (page >= 1 && page <= totalPages) {
                        currentPage = page;
                        loadProducts();
                    }
                });
            });
        }
        
        // Function to show delete confirmation
        function showDeleteConfirmation(productId) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
            
            document.getElementById('confirm-delete').onclick = function() {
                deleteProduct(productId);
                deleteModal.hide();
            };
        }
        
        // Function to delete product
        function deleteProduct(productId) {
            fetch(`/api/admin/products/${productId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', 'Product deleted successfully.');
                    // Reload products
                    loadProducts();
                } else {
                    throw new Error(data.message || 'Failed to delete product.');
                }
            })
            .catch(error => {
                console.error('Error deleting product:', error);
                showAlert('danger', error.message || 'An error occurred while deleting the product.');
            });
        }
        
        // Function to get selected products
        function getSelectedProducts() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            return Array.from(checkboxes).map(checkbox => checkbox.value);
        }
        
        // Function to update bulk action button state
        function updateBulkActionButton() {
            const selectedCount = document.querySelectorAll('.product-checkbox:checked').length;
            const bulkActionButton = document.getElementById('apply-bulk-action');
            bulkActionButton.disabled = selectedCount === 0;
        }
        
        // Function to process bulk actions
        function processBulkAction(action, productIds) {
            if (productIds.length === 0) return;
            
            let endpoint = '/api/admin/products/bulk-action';
            let method = 'POST';
            let actionData = {
                action: action,
                product_ids: productIds
            };
            
            fetch(endpoint, {
                method: method,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(actionData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    let actionText = '';
                    switch (action) {
                        case 'activate': actionText = 'activated'; break;
                        case 'deactivate': actionText = 'deactivated'; break;
                        case 'feature': actionText = 'marked as featured'; break;
                        case 'unfeature': actionText = 'removed from featured'; break;
                        case 'delete': actionText = 'deleted'; break;
                    }
                    showAlert('success', `Selected products ${actionText} successfully.`);
                    
                    // Reset select all checkbox
                    document.getElementById('select-all').checked = false;
                    
                    // Reload products
                    loadProducts();
                } else {
                    throw new Error(data.message || `Failed to ${action} products.`);
                }
            })
            .catch(error => {
                console.error(`Error processing bulk action ${action}:`, error);
                showAlert('danger', error.message || 'An error occurred while processing the bulk action.');
            });
        }
        
        // Function to show alert
        function showAlert(type, message) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert alert at the top of the content container
            const contentContainer = document.querySelector('.container-fluid');
            contentContainer.insertBefore(alertDiv, contentContainer.firstChild);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 150);
            }, 5000);
        }
    });
</script>
@endsection