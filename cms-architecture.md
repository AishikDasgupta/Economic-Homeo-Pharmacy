# Economic Homeo Pharmacy CMS Architecture

## Overview
This document outlines the architecture for the Economic Homeo Pharmacy Content Management System (CMS). The CMS will allow administrators to manage products, categories, users, and content on the website.

## Technology Stack

### Backend
- **Framework**: PHP with Laravel
- **Database**: MySQL
- **Authentication**: JWT (JSON Web Tokens)
- **API**: RESTful API endpoints

### Frontend
- **Admin Panel**: Bootstrap 4, jQuery, and custom CSS
- **Integration**: JavaScript/AJAX for connecting with existing frontend

## Database Schema

### Users Table
```
users
- id (PK)
- name
- email
- password (hashed)
- role (admin, editor, customer)
- created_at
- updated_at
```

### Categories Table
```
categories
- id (PK)
- name
- description
- slug
- parent_id (FK to categories.id, for subcategories)
- image_path
- created_at
- updated_at
```

### Products Table
```
products
- id (PK)
- name
- description
- short_description
- price
- sale_price
- sku
- stock_quantity
- is_featured (boolean)
- is_active (boolean)
- slug
- created_at
- updated_at
```

### Product_Category Relationship Table
```
product_category
- product_id (FK)
- category_id (FK)
```

### Product Images Table
```
product_images
- id (PK)
- product_id (FK)
- image_path
- is_primary (boolean)
- alt_text
- created_at
- updated_at
```

### Product Details Table
```
product_details
- id (PK)
- product_id (FK)
- benefits
- ingredients
- usage_instructions
- precautions
- created_at
- updated_at
```

### Orders Table
```
orders
- id (PK)
- user_id (FK)
- status (pending, processing, completed, cancelled)
- total_amount
- shipping_address
- billing_address
- payment_method
- payment_status
- created_at
- updated_at
```

### Order Items Table
```
order_items
- id (PK)
- order_id (FK)
- product_id (FK)
- quantity
- price
- created_at
- updated_at
```

### Pages Table (for static content)
```
pages
- id (PK)
- title
- content
- slug
- meta_description
- meta_keywords
- is_active
- created_at
- updated_at
```

## Admin Panel Modules

1. **Dashboard**
   - Overview statistics
   - Recent orders
   - Low stock alerts
   - Sales charts

2. **Product Management**
   - List all products
   - Add/Edit/Delete products
   - Manage product images
   - Manage product details
   - Bulk operations

3. **Category Management**
   - List all categories
   - Add/Edit/Delete categories
   - Manage category hierarchy

4. **Order Management**
   - View all orders
   - Update order status
   - View order details
   - Generate invoices

5. **User Management**
   - List all users
   - Add/Edit/Delete users
   - Manage user roles

6. **Content Management**
   - Manage static pages
   - Edit homepage content
   - Manage banners and promotions

7. **Settings**
   - General website settings
   - Email templates
   - Payment gateway settings
   - Shipping options

## API Endpoints

### Authentication
- POST /api/login
- POST /api/logout
- POST /api/register
- GET /api/user

### Products
- GET /api/products
- GET /api/products/{id}
- POST /api/products
- PUT /api/products/{id}
- DELETE /api/products/{id}
- GET /api/products/featured
- GET /api/products/category/{category_id}

### Categories
- GET /api/categories
- GET /api/categories/{id}
- POST /api/categories
- PUT /api/categories/{id}
- DELETE /api/categories/{id}

### Orders
- GET /api/orders
- GET /api/orders/{id}
- POST /api/orders
- PUT /api/orders/{id}/status

### Users
- GET /api/users
- GET /api/users/{id}
- POST /api/users
- PUT /api/users/{id}
- DELETE /api/users/{id}

## Integration with Existing Frontend

The CMS will integrate with the existing frontend through:

1. **API Consumption**:
   - The frontend will consume the API endpoints to display products, categories, etc.

2. **Template Modifications**:
   - Existing HTML templates will be modified to fetch data from the CMS API
   - Product listings will be dynamically generated
   - Product details pages will pull content from the database

3. **User Authentication**:
   - The existing login/register functionality will be connected to the CMS authentication system

4. **Shopping Cart**:
   - The shopping cart will be enhanced to interact with the product database
   - Checkout process will create orders in the CMS

## Security Considerations

1. **Authentication**:
   - JWT-based authentication with token expiration
   - Role-based access control

2. **Data Validation**:
   - Server-side validation for all inputs
   - CSRF protection

3. **File Uploads**:
   - Validation of file types and sizes
   - Secure storage of uploaded files

4. **API Security**:
   - Rate limiting
   - CORS configuration

## Deployment Strategy

1. **Development Environment**:
   - Local development setup with version control

2. **Staging Environment**:
   - Testing environment that mirrors production

3. **Production Environment**:
   - Live server with proper security configurations
   - Database backups
   - SSL certificate

## Future Enhancements

1. **Multi-language Support**
2. **Advanced Search Functionality**
3. **Customer Reviews and Ratings**
4. **Inventory Management System**
5. **Email Marketing Integration**
6. **Analytics Dashboard**