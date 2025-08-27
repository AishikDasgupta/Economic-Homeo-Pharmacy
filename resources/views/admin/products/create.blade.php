@extends('admin.layouts.app')

@section('title', 'Add New Product')

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
    }
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
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
        <h1 class="h3 mb-0 text-gray-800">Add New Product</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>

    <!-- Product Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form id="product-form" enctype="multipart/form-data">
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

                <!-- Alert for errors -->
                <div class="alert alert-danger" id="error-message" style="display: none;"></div>

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
                                        <input type="checkbox" class="custom-control-input" id="active" name="active" checked>
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
                            <label for="product_images">Product Images</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="product_images" name="images[]" multiple accept="image/*">
                                <label class="custom-file-label" for="product_images">Choose files</label>
                            </div>
                            <small class="form-text text-muted">You can select multiple images. Maximum 5 images allowed.</small>
                        </div>
                        
                        <div class="image-preview-container" id="image-preview-container">
                            <!-- Image previews will be shown here -->
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
                        <span id="btn-text">Save Product</span>
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        
        // Get token from localStorage
        const token = localStorage.getItem('admin_token');
        
        if (!token) {
            window.location.href = '{{ route("admin.login") }}';
            return;
        }
        
        // Load categories
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
        
        // Handle file input change for image preview
        document.getElementById('product_images').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('image-preview-container');
            previewContainer.innerHTML = '';
            
            if (files.length > 5) {
                alert('You can only upload a maximum of 5 images.');
                e.target.value = '';
                return;
            }
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'product-image-preview';
                    previewContainer.appendChild(img);
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
            
            submitBtn.disabled = true;
            loadingSpinner.classList.remove('d-none');
            btnText.textContent = 'Saving...';
            errorMessage.style.display = 'none';
            
            const formData = new FormData(this);
            
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
            
            fetch('/api/admin/products', {
                method: 'POST',
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
                
                // Redirect to product list on success
                window.location.href = '{{ route("admin.products.index") }}';
            })
            .catch(error => {
                // Reset button state
                submitBtn.disabled = false;
                loadingSpinner.classList.add('d-none');
                btnText.textContent = 'Save Product';
                
                // Show error message
                errorMessage.innerHTML = error.message || 'An error occurred while saving the product. Please try again.';
                errorMessage.style.display = 'block';
                
                // Scroll to error message
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    });
</script>
@endsection