@extends('admin.layouts.app')

@section('title', 'Edit Product')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .custom-file-label::after {
        content: "Browse";
    }
    .product-image-preview {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 10px;
        margin-bottom: 10px;
        position: relative;
    }
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
    }
    .image-delete-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #dc3545;
    }
    .image-primary-badge {
        position: absolute;
        bottom: 5px;
        left: 5px;
        background-color: rgba(25, 135, 84, 0.8);
        color: white;
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 3px;
    }
    .set-primary-btn {
        position: absolute;
        bottom: 5px;
        left: 5px;
        background-color: rgba(255, 255, 255, 0.8);
        font-size: 10px;
        padding: 2px 5px;
        border-radius: 3px;
        cursor: pointer;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
        border-bottom: 3px solid var(--primary);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
        <div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
            <a href="#" id="view-product-btn" target="_blank" class="btn btn-info">
                <i class="fas fa-eye"></i> View Product
            </a>
        </div>
    </div>

    <!-- Alert for success/error messages -->
    <div class="alert alert-success" id="success-message" style="display: none;"></div>
    <div class="alert alert-danger" id="error-message" style="display: none;"></div>

    <!-- Product Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="product-form" enctype="multipart/form-data">
                <input type="hidden" id="product-id">
                
                <!-- Nav tabs -->
                <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="general-tab" data-bs-toggle="tab" href="#general" role="tab">General</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="images-tab" data-bs-toggle="tab" href="#images" role="tab">Images</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="details-tab" data-bs-toggle="tab" href="#details" role="tab">Product Details</a>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content" id="productTabsContent">
                    <!-- General Tab -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control summernote" id="description" name="description" rows="5"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <!-- Categories will be loaded dynamically -->
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="price">Price (₹) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="sku">SKU <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="sku" name="sku" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="stock_quantity">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" required>
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="active" name="active">
                                        <label class="custom-control-label" for="active">Active</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="featured" name="featured">
                                        <label class="custom-control-label" for="featured">Featured Product</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Images Tab -->
                    <div class="tab-pane fade" id="images" role="tabpanel">
                        <div class="form-group">
                            <label for="product_images">Add More Images</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="product_images" name="images[]" multiple accept="image/*">
                                <label class="custom-file-label" for="product_images">Choose files</label>
                            </div>
                            <small class="form-text text-muted">You can select multiple images. Maximum 5 images allowed in total.</small>
                        </div>
                        
                        <h6 class="mt-4 mb-3">Current Images</h6>
                        <div class="image-preview-container" id="current-images-container">
                            <!-- Current images will be shown here -->
                            <div class="text-center w-100" id="no-images-message" style="display: none;">
                                <p class="text-muted">No images uploaded yet.</p>
                            </div>
                        </div>
                        
                        <h6 class="mt-4 mb-3">New Images Preview</h6>
                        <div class="image-preview-container" id="new-images-preview">
                            <!-- New image previews will be shown here -->
                        </div>
                    </div>
                    
                    <!-- Product Details Tab -->
                    <div class="tab-pane fade" id="details" role="tabpanel">
                        <div class="form-group">
                            <label for="benefits">Benefits</label>
                            <textarea class="form-control summernote" id="benefits" name="benefits" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="usage">Usage</label>
                            <textarea class="form-control summernote" id="usage" name="usage" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="ingredients">Ingredients</label>
                            <textarea class="form-control summernote" id="ingredients" name="ingredients" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="dosage">Dosage</label>
                            <textarea class="form-control summernote" id="dosage" name="dosage" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="side_effects">Side Effects</label>
                            <textarea class="form-control summernote" id="side_effects" name="side_effects" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="precautions">Precautions</label>
                            <textarea class="form-control summernote" id="precautions" name="precautions" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="storage_info">Storage Information</label>
                            <textarea class="form-control summernote" id="storage_info" name="storage_info" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="additional_info">Additional Information</label>
                            <textarea class="form-control summernote" id="additional_info" name="additional_info" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="loading-spinner"></span>
                        <span id="btn-text">Update Product</span>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Image Confirmation Modal -->
<div class="modal fade" id="deleteImageModal" tabindex="-1" aria-labelledby="deleteImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteImageModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this image? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-image">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get token from localStorage
        const token = localStorage.getItem('admin_token');
        
        if (!token) {
            window.location.href = '{{ route("admin.login") }}';
            return;
        }
        
        // Get product ID from URL
        const urlParts = window.location.pathname.split('/');
        const productId = urlParts[urlParts.length - 1];
        document.getElementById('product-id').value = productId;
        
        // Initialize Summernote editor
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Load categories
        loadCategories();
        
        // Load product data
        loadProductData(productId);
        
        // Handle file input change for image preview
        document.getElementById('product_images').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('new-images-preview');
            previewContainer.innerHTML = '';
            
            // Get current image count
            const currentImageCount = document.querySelectorAll('#current-images-container .product-image-container').length;
            
            if (currentImageCount + files.length > 5) {
                alert(`You can only have a maximum of 5 images. You can add ${5 - currentImageCount} more images.`);
                e.target.value = '';
                return;
            }
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'product-image-preview';
                    imgContainer.appendChild(img);
                    
                    previewContainer.appendChild(imgContainer);
                }
                
                reader.readAsDataURL(file);
            }
            
            // Update file input label
            const label = document.querySelector('.custom-file-label');
            label.textContent = files.length > 1 ? `${files.length} files selected` : files[0].name;
        });
        
        // Handle form submission
        document.getElementById('product-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = document.getElementById('submit-btn');
            const loadingSpinner = document.getElementById('loading-spinner');
            const btnText = document.getElementById('btn-text');
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');
            
            submitBtn.disabled = true;
            loadingSpinner.classList.remove('d-none');
            btnText.textContent = 'Updating...';
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';
            
            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // For Laravel method spoofing
            
            // Add boolean values
            formData.set('active', document.getElementById('active').checked ? '1' : '0');
            formData.set('featured', document.getElementById('featured').checked ? '1' : '0');
            
            // Add description from Summernote
            formData.set('description', $('#description').summernote('code'));
            formData.set('benefits', $('#benefits').summernote('code'));
            formData.set('usage', $('#usage').summernote('code'));
            formData.set('ingredients', $('#ingredients').summernote('code'));
            formData.set('dosage', $('#dosage').summernote('code'));
            formData.set('side_effects', $('#side_effects').summernote('code'));
            formData.set('precautions', $('#precautions').summernote('code'));
            formData.set('storage_info', $('#storage_info').summernote('code'));
            formData.set('additional_info', $('#additional_info').summernote('code'));
            
            fetch(`/api/admin/products/${productId}`, {
                method: 'POST', // POST with _method=PUT for Laravel
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    throw new Error(Object.values(data.errors).flat().join('<br>'));
                }
                
                // Show success message
                successMessage.innerHTML = 'Product updated successfully.';
                successMessage.style.display = 'block';
                
                // Scroll to success message
                successMessage.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Reset button state
                submitBtn.disabled = false;
                loadingSpinner.classList.add('d-none');
                btnText.textContent = 'Update Product';
                
                // Reload product data to refresh images
                loadProductData(productId);
            })
            .catch(error => {
                // Reset button state
                submitBtn.disabled = false;
                loadingSpinner.classList.add('d-none');
                btnText.textContent = 'Update Product';
                
                // Show error message
                errorMessage.innerHTML = error.message || 'An error occurred while updating the product. Please try again.';
                errorMessage.style.display = 'block';
                
                // Scroll to error message
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
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
                const categorySelect = document.getElementById('category_id');
                
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
        
        // Function to load product data
        function loadProductData(productId) {
            fetch(`/api/admin/products/${productId}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.data) {
                    populateProductForm(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading product data:', error);
                document.getElementById('error-message').innerHTML = 'Error loading product data. Please try again.';
                document.getElementById('error-message').style.display = 'block';
            });
        }
        
        // Function to populate product form
        function populateProductForm(product) {
            // Set view product URL
            document.getElementById('view-product-btn').href = `/products/${product.id}`;
            
            // Populate general fields
            document.getElementById('name').value = product.name;
            $('#description').summernote('code', product.description || '');
            document.getElementById('category_id').value = product.category_id;
            document.getElementById('price').value = product.price;
            document.getElementById('sku').value = product.sku;
            document.getElementById('stock_quantity').value = product.stock_quantity;
            document.getElementById('active').checked = product.active;
            document.getElementById('featured').checked = product.featured;
            
            // Populate product details if available
            if (product.product_detail) {
                $('#benefits').summernote('code', product.product_detail.benefits || '');
                $('#usage').summernote('code', product.product_detail.usage || '');
                $('#ingredients').summernote('code', product.product_detail.ingredients || '');
                $('#dosage').summernote('code', product.product_detail.dosage || '');
                $('#side_effects').summernote('code', product.product_detail.side_effects || '');
                $('#precautions').summernote('code', product.product_detail.precautions || '');
                $('#storage_info').summernote('code', product.product_detail.storage_info || '');
                $('#additional_info').summernote('code', product.product_detail.additional_info || '');
            }
            
            // Populate images
            const imagesContainer = document.getElementById('current-images-container');
            imagesContainer.innerHTML = '';
            
            if (product.images && product.images.length > 0) {
                document.getElementById('no-images-message').style.display = 'none';
                
                product.images.forEach(image => {
                    const imgContainer = document.createElement('div');
                    imgContainer.className = 'position-relative product-image-container';
                    imgContainer.setAttribute('data-image-id', image.id);
                    
                    const img = document.createElement('img');
                    img.src = image.image_url;
                    img.alt = product.name;
                    img.className = 'product-image-preview';
                    imgContainer.appendChild(img);
                    
                    // Add delete button
                    const deleteBtn = document.createElement('button');
                    deleteBtn.className = 'btn btn-sm btn-danger image-delete-btn';
                    deleteBtn.innerHTML = '<i class="fas fa-times"></i>';
                    deleteBtn.setAttribute('data-image-id', image.id);
                    deleteBtn.addEventListener('click', function() {
                        showDeleteImageConfirmation(image.id);
                    });
                    imgContainer.appendChild(deleteBtn);
                    
                    // Add primary badge or set primary button
                    if (image.is_primary) {
                        const primaryBadge = document.createElement('span');
                        primaryBadge.className = 'image-primary-badge';
                        primaryBadge.textContent = 'Primary';
                        imgContainer.appendChild(primaryBadge);
                    } else {
                        const setPrimaryBtn = document.createElement('button');
                        setPrimaryBtn.className = 'btn btn-sm btn-light set-primary-btn';
                        setPrimaryBtn.textContent = 'Set as Primary';
                        setPrimaryBtn.setAttribute('data-image-id', image.id);
                        setPrimaryBtn.addEventListener('click', function() {
                            setImageAsPrimary(image.id);
                        });
                        imgContainer.appendChild(setPrimaryBtn);
                    }
                    
                    imagesContainer.appendChild(imgContainer);
                });
            } else {
                document.getElementById('no-images-message').style.display = 'block';
            }
        }
        
        // Function to show delete image confirmation
        function showDeleteImageConfirmation(imageId) {
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteImageModal'));
            deleteModal.show();
            
            document.getElementById('confirm-delete-image').onclick = function() {
                deleteImage(imageId);
                deleteModal.hide();
            };
        }
        
        // Function to delete image
        function deleteImage(imageId) {
            fetch(`/api/admin/product-images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove image from DOM
                    const imageContainer = document.querySelector(`.product-image-container[data-image-id="${imageId}"]`);
                    if (imageContainer) {
                        imageContainer.remove();
                    }
                    
                    // Show success message
                    document.getElementById('success-message').innerHTML = 'Image deleted successfully.';
                    document.getElementById('success-message').style.display = 'block';
                    
                    // Check if there are any images left
                    const remainingImages = document.querySelectorAll('.product-image-container');
                    if (remainingImages.length === 0) {
                        document.getElementById('no-images-message').style.display = 'block';
                    }
                } else {
                    throw new Error(data.message || 'Failed to delete image.');
                }
            })
            .catch(error => {
                console.error('Error deleting image:', error);
                document.getElementById('error-message').innerHTML = error.message || 'An error occurred while deleting the image.';
                document.getElementById('error-message').style.display = 'block';
            });
        }
        
        // Function to set image as primary
        function setImageAsPrimary(imageId) {
            fetch(`/api/admin/product-images/${imageId}/set-primary`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    document.getElementById('success-message').innerHTML = 'Primary image updated successfully.';
                    document.getElementById('success-message').style.display = 'block';
                    
                    // Reload product data to refresh images
                    loadProductData(productId);
                } else {
                    throw new Error(data.message || 'Failed to update primary image.');
                }
            })
            .catch(error => {
                console.error('Error setting primary image:', error);
                document.getElementById('error-message').innerHTML = error.message || 'An error occurred while updating the primary image.';
                document.getElementById('error-message').style.display = 'block';
            });
        }
    });
</script>
@endsection