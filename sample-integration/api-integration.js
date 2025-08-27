/**
 * Economic Homeo Pharmacy CMS API Integration
 * 
 * This file contains functions to interact with the CMS API endpoints
 * and integrate them with the existing frontend.
 */

// API Base URL - Change this to your actual API URL in production
const API_BASE_URL = '/api';

// Headers for API requests
const getHeaders = () => {
    const token = localStorage.getItem('auth_token');
    return {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': token ? `Bearer ${token}` : ''
    };
};

/**
 * Authentication Functions
 */

// Login user and get token
async function login(email, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Login failed');
        }

        // Store token in localStorage
        localStorage.setItem('auth_token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));

        return data;
    } catch (error) {
        console.error('Login error:', error);
        throw error;
    }
}

// Register new user
async function register(name, email, password, password_confirmation) {
    try {
        const response = await fetch(`${API_BASE_URL}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, email, password, password_confirmation })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Registration failed');
        }

        return data;
    } catch (error) {
        console.error('Registration error:', error);
        throw error;
    }
}

// Logout user
async function logout() {
    try {
        const response = await fetch(`${API_BASE_URL}/logout`, {
            method: 'POST',
            headers: getHeaders()
        });

        // Clear local storage regardless of response
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');

        return response.ok;
    } catch (error) {
        console.error('Logout error:', error);
        // Clear local storage even if API call fails
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        throw error;
    }
}

// Check if user is authenticated
function isAuthenticated() {
    return localStorage.getItem('auth_token') !== null;
}

// Get current user
function getCurrentUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
}

/**
 * Product Functions
 */

// Get all products with optional filters
async function getProducts(filters = {}) {
    try {
        // Build query string from filters
        const queryParams = new URLSearchParams();
        
        if (filters.category_id) queryParams.append('category_id', filters.category_id);
        if (filters.search) queryParams.append('search', filters.search);
        if (filters.featured !== undefined) queryParams.append('featured', filters.featured);
        if (filters.active !== undefined) queryParams.append('active', filters.active);
        if (filters.sort_by) queryParams.append('sort_by', filters.sort_by);
        if (filters.sort_direction) queryParams.append('sort_direction', filters.sort_direction);
        if (filters.per_page) queryParams.append('per_page', filters.per_page);
        if (filters.page) queryParams.append('page', filters.page);
        
        const queryString = queryParams.toString();
        const url = `${API_BASE_URL}/products${queryString ? `?${queryString}` : ''}`;
        
        const response = await fetch(url, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch products');
        }

        return data;
    } catch (error) {
        console.error('Error fetching products:', error);
        throw error;
    }
}

// Get featured products
async function getFeaturedProducts(limit = 8) {
    try {
        const response = await fetch(`${API_BASE_URL}/products/featured?limit=${limit}`, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch featured products');
        }

        return data;
    } catch (error) {
        console.error('Error fetching featured products:', error);
        throw error;
    }
}

// Get products by category
async function getProductsByCategory(categoryId, page = 1, perPage = 12) {
    try {
        const response = await fetch(`${API_BASE_URL}/products/category/${categoryId}?page=${page}&per_page=${perPage}`, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch products by category');
        }

        return data;
    } catch (error) {
        console.error('Error fetching products by category:', error);
        throw error;
    }
}

// Get single product by ID or slug
async function getProduct(idOrSlug) {
    try {
        const response = await fetch(`${API_BASE_URL}/products/${idOrSlug}`, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch product');
        }

        return data;
    } catch (error) {
        console.error('Error fetching product:', error);
        throw error;
    }
}

/**
 * Category Functions
 */

// Get all categories
async function getCategories() {
    try {
        const response = await fetch(`${API_BASE_URL}/categories`, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch categories');
        }

        return data;
    } catch (error) {
        console.error('Error fetching categories:', error);
        throw error;
    }
}

/**
 * Cart Functions
 */

// Get cart from localStorage
function getCart() {
    const cart = localStorage.getItem('cart');
    return cart ? JSON.parse(cart) : { items: [], total: 0 };
}

// Save cart to localStorage
function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Add product to cart
function addToCart(product, quantity = 1) {
    const cart = getCart();
    
    // Check if product already exists in cart
    const existingItemIndex = cart.items.findIndex(item => item.id === product.id);
    
    if (existingItemIndex !== -1) {
        // Update quantity if product already in cart
        cart.items[existingItemIndex].quantity += quantity;
    } else {
        // Add new item to cart
        cart.items.push({
            id: product.id,
            name: product.name,
            price: product.current_price,
            image: product.primary_image ? product.primary_image.image_path : null,
            quantity: quantity
        });
    }
    
    // Recalculate total
    cart.total = cart.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    
    // Save updated cart
    saveCart(cart);
    
    // Update cart UI
    updateCartUI();
    
    return cart;
}

// Remove product from cart
function removeFromCart(productId) {
    const cart = getCart();
    
    // Filter out the product
    cart.items = cart.items.filter(item => item.id !== productId);
    
    // Recalculate total
    cart.total = cart.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    
    // Save updated cart
    saveCart(cart);
    
    // Update cart UI
    updateCartUI();
    
    return cart;
}

// Update cart item quantity
function updateCartItemQuantity(productId, quantity) {
    const cart = getCart();
    
    // Find the item
    const item = cart.items.find(item => item.id === productId);
    
    if (item) {
        // Update quantity
        item.quantity = quantity;
        
        // Recalculate total
        cart.total = cart.items.reduce((total, item) => total + (item.price * item.quantity), 0);
        
        // Save updated cart
        saveCart(cart);
        
        // Update cart UI
        updateCartUI();
    }
    
    return cart;
}

// Clear cart
function clearCart() {
    // Save empty cart
    saveCart({ items: [], total: 0 });
    
    // Update cart UI
    updateCartUI();
    
    return { items: [], total: 0 };
}

// Update cart UI
function updateCartUI() {
    const cart = getCart();
    
    // Update cart count in header
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = cart.items.reduce((count, item) => count + item.quantity, 0);
    }
    
    // Update cart dropdown if it exists
    const cartDropdown = document.querySelector('.cart-dropdown');
    if (cartDropdown) {
        // Implementation depends on your specific UI
    }
}

/**
 * Order Functions
 */

// Create a new order
async function createOrder(orderData) {
    try {
        const response = await fetch(`${API_BASE_URL}/orders`, {
            method: 'POST',
            headers: getHeaders(),
            body: JSON.stringify(orderData)
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to create order');
        }

        return data;
    } catch (error) {
        console.error('Error creating order:', error);
        throw error;
    }
}

// Get user orders
async function getUserOrders() {
    try {
        const response = await fetch(`${API_BASE_URL}/user/orders`, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch orders');
        }

        return data;
    } catch (error) {
        console.error('Error fetching user orders:', error);
        throw error;
    }
}

// Get order details
async function getOrderDetails(orderId) {
    try {
        const response = await fetch(`${API_BASE_URL}/orders/${orderId}`, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch order details');
        }

        return data;
    } catch (error) {
        console.error('Error fetching order details:', error);
        throw error;
    }
}

/**
 * Page Functions
 */

// Get page content by slug
async function getPage(slug) {
    try {
        const response = await fetch(`${API_BASE_URL}/pages/${slug}`, {
            method: 'GET',
            headers: getHeaders()
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Failed to fetch page');
        }

        return data;
    } catch (error) {
        console.error('Error fetching page:', error);
        throw error;
    }
}

// Export all functions
export {
    // Authentication
    login,
    register,
    logout,
    isAuthenticated,
    getCurrentUser,
    
    // Products
    getProducts,
    getFeaturedProducts,
    getProductsByCategory,
    getProduct,
    
    // Categories
    getCategories,
    
    // Cart
    getCart,
    addToCart,
    removeFromCart,
    updateCartItemQuantity,
    clearCart,
    updateCartUI,
    
    // Orders
    createOrder,
    getUserOrders,
    getOrderDetails,
    
    // Pages
    getPage
};