<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Economic Homeo Pharmacy')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --accent-color: #28a745;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
        .navbar-brand img {
            height: 40px;
        }
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .nav-link {
            font-weight: 500;
        }
        
        .footer {
            background-color: #f8f9fa;
            padding: 3rem 0;
            margin-top: 3rem;
        }
        
        .footer-heading {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }
        
        .footer-link {
            color: #6c757d;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-link:hover {
            color: var(--primary-color);
        }
        
        .social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            margin-right: 10px;
            transition: transform 0.3s, background-color 0.3s;
        }
        
        .social-icon:hover {
            transform: translateY(-3px);
            background-color: #0b5ed7;
            color: white;
        }
        
        .copyright {
            background-color: #e9ecef;
            padding: 1rem 0;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="/images/logo.png" alt="Economic Homeo Pharmacy Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/products">Products</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="categoriesDropdown" id="categories-dropdown-menu">
                                <li><a class="dropdown-item" href="/products">All Categories</a></li>
                                <!-- Categories will be loaded dynamically -->
                                <li><div class="dropdown-item text-center py-2" id="categories-loading">
                                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contact">Contact</a>
                        </li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <a href="/cart" class="btn btn-outline-primary me-2 position-relative">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count">
                                0
                            </span>
                        </a>
                        <a href="/account" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-user"></i>
                        </a>
                        <form class="d-flex" action="/products" method="GET">
                            <input class="form-control me-2" type="search" name="search" placeholder="Search products..." aria-label="Search">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="footer-heading">Economic Homeo Pharmacy</h5>
                    <p>Providing quality homeopathic medicines for over 25 years. We are committed to natural healing and wellness.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="/" class="footer-link">Home</a></li>
                        <li class="mb-2"><a href="/products" class="footer-link">Products</a></li>
                        <li class="mb-2"><a href="/about" class="footer-link">About Us</a></li>
                        <li class="mb-2"><a href="/contact" class="footer-link">Contact</a></li>
                        <li class="mb-2"><a href="/blog" class="footer-link">Blog</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="footer-heading">Categories</h5>
                    <ul class="list-unstyled" id="footer-categories">
                        <!-- Categories will be loaded dynamically -->
                        <li class="mb-2"><div class="footer-link" id="footer-categories-loading">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Loading...
                        </div></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="footer-heading">Contact Us</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            123 Main Street, New Delhi, India
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2 text-primary"></i>
                            +91 98765 43210
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            info@economichomeo.com
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            Mon-Sat: 9:00 AM - 8:00 PM
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <div class="copyright text-center">
        <div class="container">
            <p class="mb-0">&copy; 2023 Economic Homeo Pharmacy. All rights reserved.</p>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Common Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load categories for dropdown and footer
            loadCategories();
            
            // Function to load categories
            function loadCategories() {
                fetch('/api/categories/active')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to load categories');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Hide loading indicators
                        document.getElementById('categories-loading').style.display = 'none';
                        document.getElementById('footer-categories-loading').style.display = 'none';
                        
                        if (!data.categories || data.categories.length === 0) {
                            return;
                        }
                        
                        // Populate dropdown menu
                        const dropdownMenu = document.getElementById('categories-dropdown-menu');
                        
                        // Populate footer categories
                        const footerCategories = document.getElementById('footer-categories');
                        footerCategories.innerHTML = '';
                        
                        // Only show top 5 categories in dropdown and footer
                        const categoriesToShow = data.categories.slice(0, 5);
                        
                        categoriesToShow.forEach(category => {
                            // Add to dropdown
                            const dropdownItem = document.createElement('li');
                            dropdownItem.innerHTML = `<a class="dropdown-item" href="/products/category/${category.slug}">${category.name}</a>`;
                            dropdownMenu.appendChild(dropdownItem);
                            
                            // Add to footer
                            const footerItem = document.createElement('li');
                            footerItem.className = 'mb-2';
                            footerItem.innerHTML = `<a href="/products/category/${category.slug}" class="footer-link">${category.name}</a>`;
                            footerCategories.appendChild(footerItem);
                        });
                        
                        // Add 'View All' link if there are more categories
                        if (data.categories.length > 5) {
                            // For dropdown
                            const viewAllDropdown = document.createElement('li');
                            viewAllDropdown.innerHTML = `<hr class="dropdown-divider"><a class="dropdown-item text-primary" href="/products">View All Categories</a>`;
                            dropdownMenu.appendChild(viewAllDropdown);
                            
                            // For footer
                            const viewAllFooter = document.createElement('li');
                            viewAllFooter.className = 'mb-2 mt-3';
                            viewAllFooter.innerHTML = `<a href="/products" class="footer-link text-primary">View All Categories</a>`;
                            footerCategories.appendChild(viewAllFooter);
                        }
                    })
                    .catch(error => {
                        console.error('Error loading categories:', error);
                        document.getElementById('categories-loading').style.display = 'none';
                        document.getElementById('footer-categories-loading').style.display = 'none';
                    });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>