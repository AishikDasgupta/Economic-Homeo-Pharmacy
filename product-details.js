/**
 * Product Details Page Functionality
 * Fetches and displays product details based on the URL slug
 */

// Configuration
const CONFIG = {
    apiUrl: 'http://localhost:1337',
    debug: true
};

// DOM Elements
const elements = {
    mainProductImage: document.getElementById('mainProductImage'),
    productTitle: document.getElementById('productTitle'),
    productPrice: document.getElementById('productPrice'),
    productDescription: document.getElementById('productDescription'),
    productFullDescription: document.getElementById('productFullDescription'),
    productCategory: document.getElementById('productCategory'),
    productCategoryText: document.getElementById('productCategoryText'),
    productSKU: document.getElementById('productSKU'),
    thumbnailContainer: document.querySelector('.thumbnail-container'),
    relatedProducts: document.getElementById('relatedProducts'),
    productSpecs: document.getElementById('productSpecs'),
    quantityInput: document.getElementById('quantity'),
    decreaseQtyBtn: document.getElementById('decreaseQty'),
    increaseQtyBtn: document.getElementById('increaseQty'),
    addToCartBtn: document.getElementById('addToCartBtn')
};

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
    return `₹${parseFloat(price).toFixed(2)}`;
};

// Handle image error
const handleImageError = (img) => {
    img.onerror = null;
    img.src = 'https://via.placeholder.com/500x500?text=Image+Not+Available';
};

// Update product images
const updateProductImages = (images) => {
    if (!images || !images.length) return;
    
    // Set main image
    elements.mainProductImage.src = images[0].url;
    elements.mainProductImage.alt = elements.productTitle.textContent;
    elements.mainProductImage.onerror = () => handleImageError(elements.mainProductImage);
    
    // Clear existing thumbnails
    elements.thumbnailContainer.innerHTML = '';
    
    // Add thumbnails
    images.forEach((img, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = 'thumbnail me-2';
        thumbnail.style.cssText = 'width: 80px; height: 80px; cursor: pointer; border: 2px solid #ddd; border-radius: 4px; overflow: hidden;';
        
        const imgElement = document.createElement('img');
        imgElement.src = img.url;
        imgElement.alt = `Thumbnail ${index + 1}`;
        imgElement.className = 'w-100 h-100 object-fit-cover';
        imgElement.onerror = () => handleImageError(imgElement);
        
        // Click handler for thumbnails
        imgElement.addEventListener('click', () => {
            elements.mainProductImage.src = img.url;
            elements.mainProductImage.alt = `Product Image ${index + 1}`;
        });
        
        thumbnail.appendChild(imgElement);
        elements.thumbnailContainer.appendChild(thumbnail);
    });
};

// Update product details
const updateProductDetails = (product) => {
    const { id, attributes } = product;
    const { name, description, price, category, images, specifications, sku } = attributes;
    
    // Update basic info
    elements.productTitle.textContent = name || 'Product Name';
    elements.productPrice.textContent = price ? formatPrice(price) : 'Price not available';
    elements.productDescription.textContent = description || 'No description available';
    elements.productFullDescription.innerHTML = description || 'No detailed description available';
    elements.productCategoryText.textContent = category || 'Uncategorized';
    elements.productSKU.textContent = sku || `EHP-${id}`;
    
    // Update images
    if (images && images.data && images.data.length > 0) {
        const imageUrls = images.data.map(img => ({
            url: img.attributes.url.startsWith('http') 
                ? img.attributes.url 
                : `${CONFIG.apiUrl}${img.attributes.url.startsWith('/') ? '' : '/'}${img.attributes.url}`
        }));
        updateProductImages(imageUrls);
    } else if (attributes.image && attributes.image.data) {
        // Fallback to single image if multiple images not available
        const imgUrl = attributes.image.data.attributes.url.startsWith('http')
            ? attributes.image.data.attributes.url
            : `${CONFIG.apiUrl}${attributes.image.data.attributes.url.startsWith('/') ? '' : '/'}${attributes.image.data.attributes.url}`;
        updateProductImages([{ url: imgUrl }]);
    }
    
    // Update specifications
    if (specifications) {
        elements.productSpecs.innerHTML = '';
        Object.entries(specifications).forEach(([key, value]) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <th scope="row" style="width: 30%;">${key}</th>
                <td>${value}</td>
            `;
            elements.productSpecs.appendChild(row);
        });
    }
};

// Fetch product by slug
const fetchProductBySlug = async (slug) => {
    try {
        const response = await fetch(
            `${CONFIG.apiUrl}/api/products?filters[slug][$eq]=${slug}&populate=*`
        );
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const { data } = await response.json();
        
        if (!data || data.length === 0) {
            throw new Error('Product not found');
        }
        
        return data[0];
    } catch (error) {
        console.error('Error fetching product:', error);
        showError('Failed to load product details. Please try again later.');
        return null;
    }
};

// Show error message
const showError = (message) => {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger';
    errorDiv.textContent = message;
    
    const container = document.querySelector('.container');
    if (container) {
        container.prepend(errorDiv);
    }
};

// Initialize the page
const init = async () => {
    const slug = getUrlParameter('slug');
    
    if (!slug) {
        showError('No product specified');
        return;
    }
    
    try {
        const product = await fetchProductBySlug(slug);
        if (product) {
            updateProductDetails(product);
            document.title = `${product.attributes.name} | Economic Homeo Pharmacy`;
        }
    } catch (error) {
        console.error('Error initializing product details:', error);
        showError('An error occurred while loading the product details.');
    }
};

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Initialize the page
    init();
    
    // Quantity controls
    elements.decreaseQtyBtn?.addEventListener('click', () => {
        const currentValue = parseInt(elements.quantityInput.value) || 1;
        if (currentValue > 1) {
            elements.quantityInput.value = currentValue - 1;
        }
    });
    
    elements.increaseQtyBtn?.addEventListener('click', () => {
        const currentValue = parseInt(elements.quantityInput.value) || 1;
        elements.quantityInput.value = currentValue + 1;
    });
    
    // Add to cart
    elements.addToCartBtn?.addEventListener('click', () => {
        const quantity = parseInt(elements.quantityInput.value) || 1;
        // TODO: Implement add to cart functionality
        alert(`Added ${quantity} item(s) to cart!`);
    });
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
