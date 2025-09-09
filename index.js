/**
 * Economic Homeo Pharmacy - Main JavaScript
 * Updated for Strapi v4 API
 */

// Configuration
const CONFIG = {
  apiUrl: 'http://localhost:1337',
  endpoints: {
    banners: '/api/homepage-banners?populate=*',
    products: '/api/products?populate=*',
    testimonials: '/api/testimonials?populate=*',
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
  heroSlider: document.querySelector('#mainCarousel .carousel-inner'),
  neutraceuticals: document.querySelector('#neutraceuticalsContainer'),
  cosmetics: document.querySelector('#cosmeticsContainer'),
  ayurvedic: document.querySelector('#ayurvedicContainer'),
  testimonials: document.querySelector('#testimonialsContainer')
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
      <p class="mt-2">Loading content...</p>
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
const renderBanner = (banner, isActive = false) => {
  const bannerData = banner.attributes || banner;
  
  if (!bannerData) {
    debug('Invalid banner data:', banner);
    return '';
  }
  
  const { title, link, image } = bannerData;
  
  // Handle different image URL structures
  let imageUrl = '';
  if (image?.data?.attributes?.url) {
    imageUrl = image.data.attributes.url;
  } else if (image?.url) {
    imageUrl = image.url;
  } else if (typeof image === 'string') {
    imageUrl = image;
  }
  
  if (!imageUrl) {
    debug('No image URL found for banner:', banner);
    return '';
  }
  
  // Ensure the URL is properly formatted
  const fullImageUrl = imageUrl.startsWith('http') 
    ? imageUrl 
    : `${CONFIG.apiUrl}${imageUrl.startsWith('/') ? '' : '/'}${imageUrl}`;
  
  return `
    <div class="carousel-item ${isActive ? 'active' : ''}">
      <div class="row">
        <div class="col-12 p-0">
          <a href="${link || '#'}" class="d-block w-100">
            <img src="${fullImageUrl}" 
                 class="d-block w-100" 
                 alt="${title || 'Banner'}"
                 style="max-height: 500px; object-fit: contain; background-color: #f8f9fa;">
          </a>
        </div>
      </div>
    </div>
  `;
};

const renderProductCard = (product) => {
  const productData = product.attributes || product;
  
  if (!productData) {
    debug('Invalid product data:', product);
    return '';
  }
  
  const { name, desc, price, slug, Image, category } = productData;
  
  // Handle the image URL from the API response
  let imageUrl = '';
  
  // Check the image structure from the API response
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
  
  // Debug log to check the generated URL
  console.log('Product:', name, 'Image URL:', fullImageUrl);
  
  return `
    <div class="col-md-3 mb-4">
      <div class="card h-100">
        <img src="${fullImageUrl}" 
             class="card-img-top" 
             alt="${name || 'Product'}" 
             style="height: 200px; object-fit: contain; padding: 1rem;"
             onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Image+Not+Available'">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">${name || 'Product Name'}</h5>
          ${desc ? `<div class="card-text flex-grow-1 mb-1" style="min-height: 30px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
            ${desc.trim()}
          </div>` : '<div class="mb-2" style="min-height: 20px;"></div>'}
          ${price ? `<p class="h5 mb-3 text-primary">₹${parseFloat(price).toFixed(2)}</p>` : ''}
          <a href="product-details.html?slug=${slug || product.id}" class="btn btn-primary mt-auto">
            View Product
          </a>
        </div>
      </div>
    </div>
  `;
};

const renderTestimonial = (testimonial) => {
  const testimonialData = testimonial.attributes || testimonial;
  
  if (!testimonialData) {
    debug('Invalid testimonial data:', testimonial);
    return '';
  }
  
  const { authorName, authorRole, description, authorImage } = testimonialData;
  
  // Handle the author image URL
  let fullImageUrl = 'https://via.placeholder.com/50?text=' + (authorName ? authorName.charAt(0) : 'U');
  
  if (authorImage) {
    // Handle different possible image URL structures
    if (typeof authorImage === 'string') {
      fullImageUrl = authorImage.startsWith('http') 
        ? authorImage 
        : `${CONFIG.apiUrl}${authorImage.startsWith('/') ? '' : '/'}${authorImage}`;
    } else if (authorImage?.url) {
      fullImageUrl = authorImage.url.startsWith('http')
        ? authorImage.url
        : `${CONFIG.apiUrl}${authorImage.url.startsWith('/') ? '' : '/'}${authorImage.url}`;
    } else if (authorImage?.data?.attributes?.url) {
      const imgPath = authorImage.data.attributes.url;
      fullImageUrl = imgPath.startsWith('http')
        ? imgPath
        : `${CONFIG.apiUrl}${imgPath.startsWith('/') ? '' : '/'}${imgPath}`;
    }
  }

  // Debug log to check the generated URL
  console.log('Testimonial from:', authorName, 'Image URL:', fullImageUrl);
  
  return `
    <div class="col-md-4 mb-4">
      <div class="testimonial-card h-100 p-4 bg-gray rounded-lg">
        <p class="testimonial-text mb-4">${description || 'No content available'}</p>
        <div class="testimonial-author d-flex align-items-center">
          <img src="${fullImageUrl}" 
               alt="${authorName || 'User'}" 
               class="rounded-circle me-3"
               style="width: 50px; height: 50px; object-fit: cover;"
               onerror="this.onerror=null; this.src='https://via.placeholder.com/50?text=' + (authorName ? authorName.charAt(0) : 'U')">
          <div>
            <h6 class="mb-0">${authorName || 'Anonymous'}</h6>
            ${authorRole ? `<small class="text-muted">${authorRole}</small>` : ''}
          </div>
        </div>
      </div>
    </div>
  `;
};

// Data Loading Functions
const loadBanners = async () => {
  const element = elements.heroSlider;
  if (!element) {
    debug('Hero slider element not found');
    return;
  }
  
  try {
    showLoading(element);
    const { data } = await fetchData(CONFIG.endpoints.banners);
    
    if (!data || !data.length) {
      showError(element, 'No banners available');
      return;
    }
    
    debug('Banners data:', data);
    
    const bannersHtml = data.map((banner, index) => 
      renderBanner(banner, index === 0)
    ).join('');
    
    element.innerHTML = bannersHtml;
    debug('Banners loaded successfully');
  } catch (error) {
    console.error('Error loading banners:', error);
    showError(element, 'Failed to load banners. Please try again later.');
  }
};

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
    const { data } = await fetchData(CONFIG.endpoints.products);
    
    if (!data || !data.length) {
      showError(element, `No products available`);
      return;
    }
    
    // Filter products by category if needed
    const filteredProducts = data.filter(product => {
      const productCategory = product.attributes?.category?.data?.attributes?.name || 
                            product.attributes?.category || 
                            product.category;
      return !category || !productCategory || 
             productCategory.toLowerCase().includes(category.toLowerCase());
    });
    
    if (!filteredProducts.length) {
      showError(element, `No ${category} products available`);
      return;
    }
    
    // Group products into carousel items (4 per slide)
    let productsHtml = '';
    const hasMultipleSlides = filteredProducts.length > 4;
    
    for (let i = 0; i < filteredProducts.length; i += 4) {
      const products = filteredProducts.slice(i, i + 4);
      const productsHtmlChunk = products.map(renderProductCard).join('');
      productsHtml += `
        <div class="carousel-item ${i === 0 ? 'active' : ''}">
          <div class="row">
            ${productsHtmlChunk}
          </div>
        </div>
      `;
    }
    
    // Get the parent carousel element
    const carouselElement = element.closest('.carousel');
    if (carouselElement) {
      // Show/hide carousel controls based on number of products
      const prevControl = carouselElement.querySelector('.carousel-control-prev');
      const nextControl = carouselElement.querySelector('.carousel-control-next');
      
      if (prevControl) prevControl.style.display = hasMultipleSlides ? 'flex' : 'none';
      if (nextControl) nextControl.style.display = hasMultipleSlides ? 'flex' : 'none';
      
      // If only one slide, remove the carousel functionality
      if (!hasMultipleSlides) {
        carouselElement.classList.remove('slide');
      } else {
        carouselElement.classList.add('slide');
      }
    }
    
    element.innerHTML = productsHtml;
    debug(`Loaded ${filteredProducts.length} ${category} products`);
  } catch (error) {
    console.error(`Error loading ${category}:`, error);
    showError(element, `Failed to load ${category}. Please try again later.`);
  }
};

const loadTestimonials = async () => {
  const element = elements.testimonials;
  if (!element) {
    debug('Testimonials container not found');
    return;
  }
  
  try {
    showLoading(element);
    const { data } = await fetchData(CONFIG.endpoints.testimonials);
    
    if (!data || !data.length) {
      showError(element, 'No testimonials available');
      return;
    }
    
    const testimonialsHtml = data.map(renderTestimonial).join('');
    element.innerHTML = `
      <div class="row">
        ${testimonialsHtml}
      </div>
    `;
    
    debug(`Loaded ${data.length} testimonials`);
  } catch (error) {
    console.error('Error loading testimonials:', error);
    showError(element, 'Failed to load testimonials. Please try again later.');
  }
};

// Initialize the page
const init = async () => {
  debug('Initializing page...');
  
  try {
    // Load all data in parallel
    await Promise.all([
      loadBanners(),
      loadProducts('neutraceuticals'),
      loadProducts('cosmetics'),
      loadProducts('ayurvedic'),
      loadTestimonials()
    ]);
    
    // Initialize carousels after content is loaded
    if (typeof $ !== 'undefined' && $.fn.carousel) {
      $('.carousel').carousel({
        interval: 5000,
        pause: 'hover'
      });
      debug('Carousels initialized');
    } else {
      console.warn('jQuery or Bootstrap carousel not found');
    }
  } catch (error) {
    console.error('Error initializing page:', error);
  }
  
  debug('Page initialization complete');
};

// Start the application when the DOM is fully loaded
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init();
}
