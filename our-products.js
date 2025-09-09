/**
 * Economic Homeo Pharmacy - Products Page JavaScript
 * Integrated with Strapi v4 API
 */

// Configuration
const CONFIG = {
  apiUrl: 'http://localhost:1337',
  endpoints: {
    products: '/api/products?populate=*',
  },
  debug: true
};

// Debug logger
const debug = (...args) => {
  if (CONFIG.debug) {
    console.log('[DEBUG]', ...args);
  }
};

// DOM Elements
const elements = {
  neutraceuticals: document.querySelector('#neutraceuticalsContainer'),
  cosmetics: document.querySelector('#cosmeticsContainer'),
  ayurvedic: document.querySelector('#ayurvedicContainer')
};

// Helper Functions
const showError = (element, message) => {
  if (!element) return;
  element.innerHTML = `
    <div class="col-12">
      <div class="alert alert-warning">${message}</div>
    </div>
  `;
};

const showLoading = (element) => {
  if (!element) return;
  element.innerHTML = `
    <div class="col-12 text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2">Loading products...</p>
    </div>
  `;
};

// API Functions
const fetchData = async (endpoint) => {
  try {
    debug(`Fetching: ${endpoint}`);
    const response = await fetch(`${CONFIG.apiUrl}${endpoint}`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    debug(`Response from ${endpoint}:`, data);
    return data;
  } catch (error) {
    console.error(`Error fetching from ${endpoint}:`, error);
    throw error;
  }
};

// Render Functions
const renderProductCard = (product) => {
  const productData = product.attributes || product;
  
  if (!productData) {
    debug('Invalid product data:', product);
    return '';
  }
  
  const { name, desc, price, slug, Image, category } = productData;
  
  // Handle the image URL from the API response
  let imageUrl = '';
  
  if (Image?.url) {
    // If the image has a direct URL
    imageUrl = Image.url.startsWith('http') 
      ? Image.url 
      : `${CONFIG.apiUrl}${Image.url.startsWith('/') ? '' : '/'}${Image.url}`;
  } else if (Image?.data?.attributes?.url) {
    // If the image is nested in a data.attributes structure
    const imgPath = Image.data.attributes.url;
    imageUrl = imgPath.startsWith('http') 
      ? imgPath 
      : `${CONFIG.apiUrl}${imgPath.startsWith('/') ? '' : '/'}${imgPath}`;
  } else if (typeof Image === 'string') {
    // If Image is a direct URL string
    imageUrl = Image.startsWith('http') 
      ? Image 
      : `${CONFIG.apiUrl}${Image.startsWith('/') ? '' : '/'}${Image}`;
  }
  
  // If no image is found, use a placeholder
  const fullImageUrl = imageUrl || 'https://via.placeholder.com/300x200';
  
  // Generate product detail URL
  const productSlug = slug || name?.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]/g, '') || product.id;
  const productUrl = `product-details.html?slug=${encodeURIComponent(productSlug)}`;
  
  return `
    <div class="col-md-3 mb-4">
      <div class="card h-100 product-card">
        <a href="${productUrl}" class="text-decoration-none text-dark">
          <div class="product-image-container" style="height: 200px; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
            <img src="${fullImageUrl}" 
                 class="card-img-top p-3" 
                 alt="${name || 'Product'}" 
                 style="max-height: 100%; max-width: 100%; object-fit: contain;"
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Image+Not+Available'">
          </div>
          <div class="card-body d-flex flex-column">
            <h5 class="card-title" style="font-size: 1rem; min-height: 2.5rem;">${name || 'Product Name'}</h5>
            ${desc ? `<div class="card-text flex-grow-1 mb-2" style="font-size: 0.875rem; color: #6c757d; min-height: 3.5rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
              ${desc.trim()}
            </div>` : '<div class="mb-2" style="min-height: 3.5rem;"></div>'}
            ${price ? `<p class="h5 mb-3 text-primary">₹${parseFloat(price).toFixed(2)}</p>` : ''}
          </a>
          <a href="${productUrl}" class="btn btn-primary mt-auto">
            View Details
          </a>
        </div>
      </div>
    </div>
  `;
};

// Data Loading Functions
const loadProducts = async (type) => {
  const element = elements[type];
  if (!element) {
    debug(`${type} container not found`);
    return;
  }
  
  const categories = {
    neutraceuticals: 'Neutraceuticals',
    cosmetics: 'Cosmetics',
    ayurvedic: 'Ayurvedic Medicines'
  };
  
  const category = categories[type] || type;
  
  try {
    showLoading(element);
    const response = await fetchData(CONFIG.endpoints.products);
    
    // Log the response for debugging
    debug('API Response:', response);
    
    // Extract data from the response
    const data = response.data || [];
    
    if (!data || !data.length) {
      showError(element, `No ${category} products available`);
      return;
    }
    
    // Log the first product for debugging
    if (data.length > 0) {
      debug('First product data:', data[0]);
    }
    
    // Filter products by category
    const filteredProducts = data.filter(product => {
      const productData = product.attributes || product;
      const productCategory = (productData.category || '').toLowerCase();
      const targetCategory = type.toLowerCase();
      
      // Check for different variations of the category name
      if (targetCategory === 'ayurvedic') {
        return productCategory.includes('ayurvedic') || productCategory.includes('ayurveda');
      }
      return productCategory.includes(targetCategory);
    });
    
    debug(`Found ${filteredProducts.length} products in category ${type}`);
    
    if (filteredProducts.length === 0) {
      showError(element, `No ${category} products found`);
      return;
    }
    
    // Render products in a grid
    const productsHtml = `
      <div class="container">
        <h2 class="mb-4">${category}</h2>
        <div class="row g-4">
          ${filteredProducts.map(product => renderProductCard(product)).join('')}
        </div>
      </div>
    `;
    
    element.innerHTML = productsHtml;
    debug(`Loaded ${filteredProducts.length} ${category} products`);
  } catch (error) {
    console.error(`Error loading ${category}:`, error);
    showError(element, `Failed to load ${category}. Please try again later.`);
  }
};

// Initialize the page
const init = async () => {
  debug('Initializing products page...');
  
  try {
    // Load all product categories
    await Promise.all([
      loadProducts('neutraceuticals'),
      loadProducts('cosmetics'),
      loadProducts('ayurvedic')
    ]);
    
    debug('Products page initialized');
  } catch (error) {
    console.error('Error initializing products page:', error);
  }
};

// Start the application when the DOM is fully loaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
