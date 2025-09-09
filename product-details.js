/**
 * Product Details Page Functionality
 * Fetches and displays product details based on the URL slug from Strapi
 */

// Configuration
const CONFIG = {
    apiUrl: 'http://localhost:1337',
    placeholderImage: 'https://via.placeholder.com/600x400?text=Image+Not+Available',
    debug: true
};

// DOM Elements
const elements = {
    // Main product elements
    mainProductImage: document.getElementById('mainProductImage'),
    productTitle: document.getElementById('productTitle'),
    productDescription: document.querySelector('.product-description p'),
    productPrice: document.getElementById('productPrice'),
    
    // Tab content elements
    benefitsList: document.getElementById('benefitsList'),
    ingredientsList: document.getElementById('ingredientsList'),
    dosageList: document.getElementById('dosageList'),
    safetyList: document.getElementById('safetyList'),
    
    // Other UI elements
    thumbnailContainer: document.getElementById('thumbnailContainer'),
    quantityInput: document.getElementById('quantity'),
    addToCartBtn: document.getElementById('addToCartBtn'),
    decreaseQty: document.getElementById('decreaseQty'),
    increaseQty: document.getElementById('increaseQty')
};

// Debug: Log all elements to check if they exist
if (CONFIG.debug) {
    console.log('DOM Elements:', elements);
}

// Debug logger
const debug = (...args) => {
    if (CONFIG.debug) {
        console.log('[Product Details]', ...args);
    }
};

// Get URL parameters
const getUrlParameter = (name) => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
};

// Format price with Indian Rupee symbol
const formatPrice = (price) => {
    return price ? `₹${parseFloat(price).toFixed(2)}` : 'Price not available';
};

// Handle image error
const handleImageError = (img) => {
    if (!img) return;
    img.onerror = null;
    img.src = CONFIG.placeholderImage;
    img.alt = 'Image not available';
};

// Format text with line breaks into HTML list items
const formatTextWithLineBreaks = (text) => {
    if (!text) return '';
    return text
        .split('\n')
        .filter(line => line.trim() !== '')
        .map(line => `<li>${line.trim()}</li>`)
        .join('');
};

// Update product images
const updateProductImages = (images) => {
    if (!images || !images.length) {
        if (elements.mainProductImage) {
            elements.mainProductImage.src = CONFIG.placeholderImage;
            elements.mainProductImage.alt = 'Product image not available';
        }
        return;
    }
    
    // Set main image
    if (elements.mainProductImage) {
        elements.mainProductImage.src = images[0].url;
        elements.mainProductImage.alt = elements.productTitle?.textContent || 'Product image';
        elements.mainProductImage.onerror = () => handleImageError(elements.mainProductImage);
    }
    
    // Clear existing thumbnails
    if (elements.thumbnailContainer) {
        elements.thumbnailContainer.innerHTML = '';
    }
    
    // If only one image, no need for thumbnails
    if (images.length <= 1 || !elements.thumbnailContainer) return;
    
    // Create thumbnails for additional images
    images.forEach((img, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = `thumbnail ${index === 0 ? 'active' : ''}`;
        thumbnail.style.cssText = 'width: 80px; height: 80px; cursor: pointer; border: 2px solid #ddd; border-radius: 4px; overflow: hidden; display: inline-block; margin-right: 8px;';
        
        const imgElement = document.createElement('img');
        imgElement.src = img.url;
        imgElement.alt = `Thumbnail ${index + 1}`;
        imgElement.style.width = '100%';
        imgElement.style.height = '100%';
        imgElement.style.objectFit = 'cover';
        imgElement.onerror = () => handleImageError(imgElement);
        
        // Click handler for thumbnails
        thumbnail.addEventListener('click', () => {
            if (elements.mainProductImage) {
                elements.mainProductImage.src = img.url;
                elements.mainProductImage.alt = `Product Image ${index + 1}`;
            }
            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
            thumbnail.classList.add('active');
        });
        
        thumbnail.appendChild(imgElement);
        elements.thumbnailContainer.appendChild(thumbnail);
    });
};

// Update product details
const updateProductDetails = (product) => {
    debug('Updating product details with:', product);
    
    if (!product || !product.attributes) {
        console.error('Invalid product data:', product);
        showError('Invalid product data received');
        return;
    }
    
    const { attributes } = product;
    const { 
        name = 'Product Name', 
        detailedDescription = '', 
        description = 'No description available',
        price = 0,
        image = null,
        images = { data: [] },
        benefits = '',
        keyIngredients = '',
        dosage = '',
        information = ''
    } = attributes;
    
    // Debug log the data we're working with
    debug('Product data for UI:', {
        name,
        price,
        hasDescription: !!detailedDescription || !!description,
        hasImages: images?.data?.length > 0,
        hasBenefits: !!benefits,
        hasIngredients: !!keyIngredients,
        hasDosage: !!dosage,
        hasInfo: !!information
    });
    
    // Debug log the data we're working with
    debug('Product data:', {
        name,
        price,
        hasDescription: !!detailedDescription || !!description,
        hasImages: images?.data?.length > 0,
        hasBenefits: !!benefits,
        hasIngredients: !!keyIngredients,
        hasDosage: !!dosage,
        hasInfo: !!information
    });
    
    // Debug log the data we're working with
    debug('Product data:', {
        name,
        price,
        hasDescription: !!detailedDescription || !!description,
        hasImages: images?.data?.length > 0,
        hasBenefits: !!benefits,
        hasIngredients: !!keyIngredients,
        hasDosage: !!dosage,
        hasInfo: !!information
    });

    // Update product title
    if (elements.productTitle) {
        elements.productTitle.textContent = name || 'Product Name';
    }
    
    // Update product price
    if (elements.productPrice) {
        elements.productPrice.textContent = formatPrice(price);
    }
    
    // Update product description (fallback to short description if detailed not available)
    if (elements.productDescription) {
        elements.productDescription.textContent = detailedDescription || description || 'No description available';
    }
    
    // Update tab content
    const updateTabContent = (element, content) => {
        if (!element) return;
        
        if (typeof content === 'string' && content.trim() !== '') {
            // If content is a string with line breaks, convert to list items
            if (content.includes('\n')) {
                const items = content.split('\n').filter(item => item.trim() !== '');
                element.innerHTML = items.map(item => `<li>${item.trim()}</li>`).join('');
            } else {
                // Single paragraph
                element.innerHTML = `<li>${content}</li>`;
            }
        } else {
            element.innerHTML = '<li>Information not available</li>';
        }
    };
    
    // Update each tab's content
    updateTabContent(elements.benefitsList, benefits);
    updateTabContent(elements.ingredientsList, keyIngredients);
    updateTabContent(elements.dosageList, dosage);
    updateTabContent(elements.safetyList, information);
    
    // Update main product image
    if (elements.mainProductImage) {
        if (image?.data?.attributes?.url) {
            elements.mainProductImage.src = `${CONFIG.apiUrl}${image.data.attributes.url}`;
            elements.mainProductImage.alt = name || 'Product Image';
        } else if (images?.data?.length > 0) {
            // Fallback to first image if main image is not available
            elements.mainProductImage.src = `${CONFIG.apiUrl}${images.data[0].attributes.url}`;
            elements.mainProductImage.alt = name || 'Product Image';
        } else {
            // Use placeholder if no images are available
            elements.mainProductImage.src = CONFIG.placeholderImage;
            elements.mainProductImage.alt = 'Image not available';
        }
    }
    
    // Update product images
    if (images?.data?.length > 0) {
        const imageUrls = images.data.map(img => ({
            url: img.attributes.url.startsWith('http') 
                ? img.attributes.url 
                : `${CONFIG.apiUrl}${img.attributes.url.startsWith('/') ? '' : '/'}${img.attributes.url}`
        }));
        updateProductImages(imageUrls);
    } else if (image?.data) {
        // Fallback to single image if multiple images not available
        const imgUrl = image.data.attributes.url.startsWith('http')
            ? image.data.attributes.url
            : `${CONFIG.apiUrl}${image.data.attributes.url.startsWith('/') ? '' : '/'}${image.data.attributes.url}`;
        updateProductImages([{ url: imgUrl }]);
    } else {
        // No images available, use placeholder
        updateProductImages([{ url: CONFIG.placeholderImage }]);
    }
};

// Fetch product by slug from Strapi
const fetchProductBySlug = async (slug) => {
    if (!slug) {
        throw new Error('No product slug provided');
    }

    try {
        const url = `${CONFIG.apiUrl}/api/products?filters[slug][$eq]=${encodeURIComponent(slug)}&populate=*`;
        debug('Fetching product from:', url);
        
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const responseData = await response.json();
        debug('API Response:', responseData);
        
        if (!responseData.data || responseData.data.length === 0) {
            throw new Error('Product not found');
        }
        
        // Transform the response to match our expected structure
        const product = responseData.data[0];
        const transformedProduct = {
            id: product.id,
            attributes: {
                name: product.name,
                description: product.desc,
                detailedDescription: product.detailedDescription,
                price: product.price,
                benefits: product.benefits,
                keyIngredients: product.keyIngredients,
                dosage: product.dosage,
                information: product.information,
                image: product.Image ? {
                    data: {
                        attributes: {
                            url: product.Image.url,
                            formats: product.Image.formats
                        }
                    }
                } : null,
                images: {
                    data: product.Image ? [{
                        attributes: {
                            url: product.Image.url,
                            formats: product.Image.formats
                        }
                    }] : []
                }
            }
        };
        
        debug('Transformed product data:', transformedProduct);
        return transformedProduct;
    } catch (error) {
        console.error('Error fetching product:', error);
        showError('Failed to load product details. Please try again later.');
        return null;
    }
};

// Show error message
const showError = (message) => {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger m-3';
    errorDiv.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>${message}
        <a href="Our products.html" class="btn btn-outline-primary btn-sm ms-3">Back to Products</a>
    `;
    
    const container = document.querySelector('.container');
    if (container) {
        container.prepend(errorDiv);
    }
};

// Initialize the page
const init = async () => {
    const slug = getUrlParameter('slug');
    debug('Initializing product page with slug:', slug);
    
    if (!slug) {
        showError('No product specified in the URL. Please ensure you are accessing this page with a valid product link.');
        return;
    }
    
    try {
        debug('Fetching product data...');
        const product = await fetchProductBySlug(slug);
        
        if (!product) {
            throw new Error('Failed to fetch product data');
        }
        
        debug('Product data received:', product);
        
        // Basic validation of required fields
        const requiredFields = ['name', 'description'];
        const missingFields = requiredFields.filter(field => !product.attributes[field]);
        
        if (missingFields.length > 0) {
            throw new Error(`Product data is missing required fields: ${missingFields.join(', ')}`);
        }
        
        // Update the UI with product data
        updateProductDetails(product);
        
        // Update page title
        if (product.attributes.name) {
            document.title = `${product.attributes.name} | Economic Homeo Pharmacy`;
        }
        
        // Update meta description for SEO
        const metaDescription = document.querySelector('meta[name="description"]');
        if (metaDescription && product.attributes.description) {
            metaDescription.content = String(product.attributes.description).substring(0, 160);
        }
        
        debug('Product page initialized successfully');
    } catch (error) {
        console.error('Error initializing product details:', error);
        showError(`An error occurred while loading the product details: ${error.message}`);
    }
};

// Add to cart functionality
const addToCart = (product, quantity = 1) => {
    try {
        // Get current cart from localStorage or initialize empty array
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Check if product already exists in cart
        const existingItemIndex = cart.findIndex(item => 
            item.id === product.id && 
            (!item.variant || item.variant === (product.variant || ''))
        );
        
        if (existingItemIndex >= 0) {
            // Update quantity if product already in cart
            cart[existingItemIndex].quantity += quantity;
        } else {
            // Add new item to cart
            cart.push({
                id: product.id,
                name: product.attributes?.name || 'Unnamed Product',
                price: product.attributes?.price || 0,
                quantity: quantity,
                image: product.attributes?.image?.data?.attributes?.url || CONFIG.placeholderImage,
                slug: product.attributes?.slug,
                variant: product.variant
            });
        }
        
        // Save updated cart to localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update cart count in the UI
        updateCartCount();
        
        // Show success message
        showSuccess('Product added to cart!');
        
        debug('Updated cart:', cart);
    } catch (error) {
        console.error('Error adding to cart:', error);
        showError('Failed to add product to cart');
    }
};

// Update cart count in the UI
const updateCartCount = () => {
    try {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        const totalItems = cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
        
        // Update cart count in the header
        const cartCountElements = document.querySelectorAll('.cart-count');
        cartCountElements.forEach(el => {
            el.textContent = totalItems;
            el.style.display = totalItems > 0 ? 'inline-block' : 'none';
        });
        
        return totalItems;
    } catch (error) {
        console.error('Error updating cart count:', error);
        return 0;
    }
};

// Show success message
const showSuccess = (message) => {
    // You can implement a toast or alert here
    alert(message);
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize product details
    init();
    
    // Initialize cart count
    updateCartCount();
    
    // Quantity controls
    if (elements.quantityInput) {
        const decreaseBtn = document.getElementById('decreaseQty');
        const increaseBtn = document.getElementById('increaseQty');
        
        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(elements.quantityInput.value) || 1;
                if (currentValue > 1) {
                    elements.quantityInput.value = currentValue - 1;
                }
            });
        }
        
        if (increaseBtn) {
            increaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(elements.quantityInput.value) || 1;
                elements.quantityInput.value = currentValue + 1;
            });
        }
    }
    
    // Add to cart functionality
    const addToCartBtn = document.getElementById('addToCartBtn');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => {
            const quantity = parseInt(elements.quantityInput?.value) || 1;
            // TODO: Implement add to cart functionality
            console.log('Added to cart:', { 
                product: elements.productTitle?.textContent,
                quantity 
            });
            
            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.role = 'alert';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i>
                Added ${quantity} item${quantity > 1 ? 's' : ''} to cart
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const container = document.querySelector('.container');
            if (container) {
                container.prepend(alert);
                
                // Auto-hide after 3 seconds
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 3000);
            }
        });
    }
});

// Export for testing if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        getUrlParameter,
        formatPrice,
        updateProductDetails,
        fetchProductBySlug
    };
}
