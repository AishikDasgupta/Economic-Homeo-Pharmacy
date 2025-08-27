# Economic Homeo Pharmacy CMS Documentation

## Overview

This document provides comprehensive guidance on using the Content Management System (CMS) for Economic Homeo Pharmacy. The CMS allows administrators to manage products, categories, and other content on the website.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Admin Authentication](#admin-authentication)
3. [Dashboard](#dashboard)
4. [Product Management](#product-management)
5. [Category Management](#category-management)
6. [Frontend Integration](#frontend-integration)
7. [API Endpoints](#api-endpoints)
8. [Troubleshooting](#troubleshooting)

## Getting Started

### System Requirements

- PHP 8.1 or higher
- MySQL 5.7 or higher
- Composer
- Node.js and NPM (for frontend assets)

### Installation

1. Clone the repository:
   ```
   git clone [repository-url]
   cd Economic-Homeo-Pharmacy
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

3. Install JavaScript dependencies:
   ```
   npm install
   ```

4. Create a `.env` file by copying `.env.example` and update the database configuration.

5. Generate application key:
   ```
   php artisan key:generate
   ```

6. Run migrations and seed the database:
   ```
   php artisan migrate --seed
   ```

7. Create symbolic link for storage:
   ```
   php artisan storage:link
   ```

8. Compile assets:
   ```
   npm run dev
   ```

9. Start the development server:
   ```
   php artisan serve
   ```

## Admin Authentication

### Admin Login

1. Navigate to `/admin/login`
2. Enter your admin credentials (email and password)
3. Upon successful login, you will be redirected to the admin dashboard

### Admin Registration

Admin registration is restricted and requires a secret key. To register a new admin:

1. Use the API endpoint `/api/admin/register` with the following parameters:
   - `name`: Admin's name
   - `email`: Admin's email address
   - `password`: Admin's password (min 8 characters)
   - `password_confirmation`: Confirm password
   - `secret`: The admin registration secret key

### Admin Logout

1. Click on the user icon in the top-right corner of the admin panel
2. Select "Logout" from the dropdown menu

## Dashboard

The dashboard provides an overview of your website's key metrics:

- Total number of products
- Total number of categories
- Number of featured products
- Number of active products
- Recent products (last 5 added)
- Recent categories (last 5 added)

## Product Management

### Viewing Products

1. Navigate to "Products" in the sidebar menu
2. The products page displays a table with all products
3. Use the search box to find specific products
4. Filter products by category, status (active/inactive), or featured status
5. Sort products by clicking on column headers

### Adding a New Product

1. Click "Add New Product" button on the products page
2. Fill in the product details in the form:
   - **General Information**:
     - Name (required)
     - Description (required)
     - Category (required)
     - Price (required)
     - SKU (required)
     - Stock Quantity
     - Active status
     - Featured status
   - **Images**:
     - Upload one or more product images
     - Set a primary image
     - Add alt text for each image
   - **Product Details**:
     - Benefits
     - Usage
     - Ingredients
     - Dosage
     - Side Effects
     - Precautions
     - Storage
     - Additional Information
3. Click "Save Product" to create the product

### Editing a Product

1. Click the "Edit" button next to a product in the products table
2. Update the product details as needed
3. Click "Update Product" to save changes

### Deleting a Product

1. Click the "Delete" button next to a product in the products table
2. Confirm the deletion in the confirmation dialog

### Bulk Actions

You can perform actions on multiple products at once:

1. Select products using the checkboxes
2. Choose an action from the "Bulk Actions" dropdown:
   - Activate
   - Deactivate
   - Feature
   - Unfeature
   - Delete
3. Click "Apply" to execute the action

## Category Management

### Viewing Categories

1. Navigate to "Categories" in the sidebar menu
2. The categories page displays a table with all categories
3. Use the search box to find specific categories

### Adding a New Category

1. Click "Add New Category" button on the categories page
2. Fill in the category details:
   - Name (required)
   - Description
   - Active status
3. Click "Save Category" to create the category

### Editing a Category

1. Click the "Edit" button next to a category in the categories table
2. Update the category details as needed
3. Click "Update Category" to save changes

### Deleting a Category

1. Click the "Delete" button next to a category in the categories table
2. Confirm the deletion in the confirmation dialog

## Frontend Integration

The CMS is integrated with the frontend of the website, providing a seamless experience for users. The frontend includes:

### Home Page

- Featured products section
- Categories section
- About section
- Testimonials section
- Newsletter subscription

### Products Page

- Product listing with filters
- Search functionality
- Category filter
- Price range filter
- Sorting options
- Pagination

### Product Detail Page

- Product images with thumbnails
- Product information
- Product details in tabs (Benefits, Usage, Ingredients, etc.)
- Related products

### Category Page

- Products in the selected category
- Filters specific to the category
- Sorting options
- Pagination

## API Endpoints

The CMS provides a set of API endpoints for both public and admin access:

### Public Endpoints

- `GET /api/products`: Get all active products with pagination
- `GET /api/products/featured`: Get featured products
- `GET /api/products/{slug}`: Get product details by slug
- `GET /api/products/category/{slug}`: Get products by category
- `GET /api/categories/active`: Get all active categories
- `GET /api/categories/{slug}`: Get category details by slug

### Admin Endpoints

#### Authentication

- `POST /api/admin/login`: Admin login
- `POST /api/admin/register`: Admin registration (requires secret key)
- `POST /api/admin/logout`: Admin logout
- `GET /api/admin/user`: Get current admin user details
- `POST /api/admin/refresh`: Refresh authentication token

#### Products

- `GET /api/admin/products`: Get all products with filters and pagination
- `POST /api/admin/products`: Create a new product
- `GET /api/admin/products/{id}`: Get product details by ID
- `PUT /api/admin/products/{id}`: Update a product
- `DELETE /api/admin/products/{id}`: Delete a product
- `POST /api/admin/products/{id}/toggle-active`: Toggle product active status
- `POST /api/admin/products/{id}/toggle-featured`: Toggle product featured status
- `POST /api/admin/products/bulk-action`: Perform bulk actions on products
- `GET /api/admin/products/count`: Get product counts for dashboard

#### Product Images

- `POST /api/admin/product-images/{id}/set-primary`: Set a product image as primary
- `DELETE /api/admin/product-images/{id}`: Delete a product image
- `PUT /api/admin/product-images/update-order`: Update product images order

#### Categories

- `GET /api/admin/categories`: Get all categories with filters and pagination
- `POST /api/admin/categories`: Create a new category
- `GET /api/admin/categories/{id}`: Get category details by ID
- `PUT /api/admin/categories/{id}`: Update a category
- `DELETE /api/admin/categories/{id}`: Delete a category
- `POST /api/admin/categories/{id}/toggle-active`: Toggle category active status
- `GET /api/admin/categories/count`: Get category count for dashboard

## Troubleshooting

### Common Issues

#### Authentication Issues

- **Problem**: Unable to log in to admin panel
- **Solution**: Verify your credentials and ensure you have admin privileges

#### Image Upload Issues

- **Problem**: Unable to upload images
- **Solution**: Check file permissions on the storage directory and ensure the symbolic link is created

#### API Request Issues

- **Problem**: API requests returning 401 Unauthorized
- **Solution**: Ensure your authentication token is valid and included in the request header

#### Database Issues

- **Problem**: Database migration errors
- **Solution**: Check database configuration in `.env` file and ensure database exists

### Getting Help

If you encounter issues not covered in this documentation, please contact the development team at support@economichomeo.com.

---

© 2023 Economic Homeo Pharmacy. All rights reserved.