@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Products</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row">
                <!-- Search -->
                <div class="col-md-3 mb-3">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU">
                </div>
                
                <!-- Category Filter -->
                <div class="col-md-3 mb-3">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category_id">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div class="col-md-2 mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="active">
                        <option value="">All Status</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <!-- Featured Filter -->
                <div class="col-md-2 mb-3">
                    <label for="featured">Featured</label>
                    <select class="form-control" id="featured" name="featured">
                        <option value="">All Products</option>
                        <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                        <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                    </select>
                </div>
                
                <!-- Submit Button -->
                <div class="col-md-2 mb-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary mr-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Products</h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="bulkActionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bulk Actions
                </button>
                <div class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                    <a class="dropdown-item bulk-action" href="#" data-action="activate">Activate Selected</a>
                    <a class="dropdown-item bulk-action" href="#" data-action="deactivate">Deactivate Selected</a>
                    <a class="dropdown-item bulk-action" href="#" data-action="feature">Feature Selected</a>
                    <a class="dropdown-item bulk-action" href="#" data-action="unfeature">Unfeature Selected</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item bulk-action text-danger" href="#" data-action="delete">Delete Selected</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="20">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th width="80">Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Categories</th>
                            <th width="100">Status</th>
                            <th width="100">Featured</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                </td>
                                <td>
                                    @if($product->primaryImage)
                                        <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" alt="{{ $product->name }}" class="img-thumbnail" width="60">
                                    @else
                                        <img src="{{ asset('img/no-image.png') }}" alt="No Image" class="img-thumbnail" width="60">
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>
                                    @if($product->sale_price)
                                        <span class="text-danger">${{ $product->sale_price }}</span>
                                        <small class="text-muted"><del>${{ $product->price }}</del></small>
                                    @else
                                        ${{ $product->price }}
                                    @endif
                                </td>
                                <td>
                                    @if($product->stock_quantity > 10)
                                        <span class="badge badge-success">{{ $product->stock_quantity }}</span>
                                    @elseif($product->stock_quantity > 0)
                                        <span class="badge badge-warning">{{ $product->stock_quantity }}</span>
                                    @else
                                        <span class="badge badge-danger">Out of stock</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach($product->categories as $category)
                                        <span class="badge badge-info">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input status-toggle" id="status_{{ $product->id }}" data-id="{{ $product->id }}" {{ $product->is_active ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="status_{{ $product->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input featured-toggle" id="featured_{{ $product->id }}" data-id="{{ $product->id }}" {{ $product->is_featured ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="featured_{{ $product->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-product" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the product: <span id="product-name"></span>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="delete-product-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Confirmation Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" role="dialog" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="bulk-action-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="bulk-action-form" method="POST">
                    @csrf
                    <input type="hidden" name="action" id="bulk-action-type">
                    <input type="hidden" name="product_ids" id="bulk-action-ids">
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Delete Product
        $('.delete-product').on('click', function() {
            const productId = $(this).data('id');
            const productName = $(this).data('name');
            
            $('#product-name').text(productName);
            $('#delete-product-form').attr('action', `/admin/products/${productId}`);
            $('#deleteProductModal').modal('show');
        });
        
        // Toggle Status
        $('.status-toggle').on('change', function() {
            const productId = $(this).data('id');
            const isActive = $(this).prop('checked');
            
            $.ajax({
                url: `/admin/products/${productId}/toggle-status`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: isActive
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                    // Revert the toggle if there was an error
                    $(this).prop('checked', !isActive);
                }
            });
        });
        
        // Toggle Featured
        $('.featured-toggle').on('change', function() {
            const productId = $(this).data('id');
            const isFeatured = $(this).prop('checked');
            
            $.ajax({
                url: `/admin/products/${productId}/toggle-featured`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_featured: isFeatured
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                    // Revert the toggle if there was an error
                    $(this).prop('checked', !isFeatured);
                }
            });
        });
        
        // Select All Checkbox
        $('#select-all').on('change', function() {
            $('.product-checkbox').prop('checked', $(this).prop('checked'));
        });
        
        // Bulk Actions
        $('.bulk-action').on('click', function(e) {
            e.preventDefault();
            
            const action = $(this).data('action');
            const selectedProducts = $('.product-checkbox:checked');
            
            if (selectedProducts.length === 0) {
                toastr.warning('Please select at least one product.');
                return;
            }
            
            const productIds = [];
            selectedProducts.each(function() {
                productIds.push($(this).val());
            });
            
            let actionMessage = '';
            switch(action) {
                case 'activate':
                    actionMessage = 'Are you sure you want to activate the selected products?';
                    break;
                case 'deactivate':
                    actionMessage = 'Are you sure you want to deactivate the selected products?';
                    break;
                case 'feature':
                    actionMessage = 'Are you sure you want to mark the selected products as featured?';
                    break;
                case 'unfeature':
                    actionMessage = 'Are you sure you want to remove the selected products from featured?';
                    break;
                case 'delete':
                    actionMessage = 'Are you sure you want to delete the selected products? This action cannot be undone.';
                    break;
            }
            
            $('#bulk-action-message').text(actionMessage);
            $('#bulk-action-type').val(action);
            $('#bulk-action-ids').val(productIds.join(','));
            $('#bulk-action-form').attr('action', '/admin/products/bulk-action');
            $('#bulkActionModal').modal('show');
        });
    });
</script>
@endsection