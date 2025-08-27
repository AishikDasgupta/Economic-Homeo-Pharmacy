# Economic Homeo Pharmacy CMS Setup Guide

## Prerequisites

Before setting up the Laravel backend for your CMS, ensure you have the following installed:

1. **PHP** (>= 8.1)
2. **Composer** (for PHP dependency management)
3. **MySQL** (>= 5.7 or MariaDB >= 10.3)
4. **Node.js** and NPM (for frontend assets)
5. **Git** (for version control)

## Step 1: Install Laravel

```bash
# Install Laravel installer globally
composer global require laravel/installer

# Create a new Laravel project in a 'cms' subdirectory
laravel new cms

# Or alternatively using Composer directly
# composer create-project laravel/laravel cms
```

## Step 2: Configure Database

1. Create a new MySQL database for your CMS
2. Update the `.env` file in your Laravel project with your database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ehp_cms
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Step 3: Set Up Authentication

Laravel provides several options for authentication. For this CMS, we'll use Laravel Breeze for a simple, minimalistic authentication scaffold:

```bash
cd cms

# Install Laravel Breeze
composer require laravel/breeze --dev

# Install Breeze with the API stack for JWT authentication
php artisan breeze:install api

# Run migrations to create users table
php artisan migrate
```

## Step 4: Create Database Migrations

Create migrations for all the tables defined in the CMS architecture:

```bash
# Create migrations for categories
php artisan make:migration create_categories_table

# Create migrations for products
php artisan make:migration create_products_table

# Create migrations for product_category pivot table
php artisan make:migration create_product_category_table

# Create migrations for product images
php artisan make:migration create_product_images_table

# Create migrations for product details
php artisan make:migration create_product_details_table

# Create migrations for orders
php artisan make:migration create_orders_table

# Create migrations for order items
php artisan make:migration create_order_items_table

# Create migrations for pages
php artisan make:migration create_pages_table
```

Edit each migration file to include the fields defined in the CMS architecture document.

## Step 5: Create Models

```bash
# Create models with resource controllers and form requests
php artisan make:model Category -mcr
php artisan make:model Product -mcr
php artisan make:model ProductImage -mcr
php artisan make:model ProductDetail -mcr
php artisan make:model Order -mcr
php artisan make:model OrderItem -mcr
php artisan make:model Page -mcr
```

## Step 6: Define Model Relationships

Edit each model to define relationships between them according to the database schema.

## Step 7: Create API Routes

Update the `routes/api.php` file to include all the API endpoints defined in the CMS architecture document.

## Step 8: Implement Controllers

Implement the controller methods for each resource to handle CRUD operations.

## Step 9: Set Up Admin Panel

For the admin panel, you can use Laravel's built-in Blade templating system with Bootstrap 4:

```bash
# Install frontend dependencies
npm install

# Install Bootstrap and other frontend dependencies
npm install bootstrap jquery @popperjs/core
```

Create the necessary Blade views for the admin panel modules.

## Step 10: Implement File Upload Functionality

Set up file upload functionality for product images:

```bash
# Create a symbolic link from public/storage to storage/app/public
php artisan storage:link
```

## Step 11: Implement API Authentication

Configure Laravel Sanctum for API authentication:

```bash
# Install Laravel Sanctum
composer require laravel/sanctum

# Publish Sanctum configuration
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## Step 12: Create Database Seeders

Create seeders to populate the database with initial data:

```bash
# Create seeders
php artisan make:seeder UserSeeder
php artisan make:seeder CategorySeeder
php artisan make:seeder ProductSeeder
php artisan make:seeder PageSeeder
```

## Step 13: Implement Validation

Implement request validation for all form submissions and API endpoints.

## Step 14: Set Up CORS

Configure CORS to allow the frontend to communicate with the API:

Update the `config/cors.php` file with appropriate settings.

## Step 15: Implement Frontend Integration

Create JavaScript files to consume the API endpoints and integrate with the existing frontend.

## Step 16: Testing

Write tests for your API endpoints and controllers:

```bash
# Create feature tests
php artisan make:test ProductApiTest
php artisan make:test CategoryApiTest
php artisan make:test OrderApiTest
php artisan make:test AuthenticationTest
```

## Step 17: Deployment

Prepare your application for deployment:

```bash
# Optimize the application
php artisan optimize

# Compile frontend assets for production
npm run build
```

## Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Breeze Documentation](https://laravel.com/docs/10.x/starter-kits#laravel-breeze)
- [Laravel Sanctum Documentation](https://laravel.com/docs/10.x/sanctum)
- [Bootstrap Documentation](https://getbootstrap.com/docs/4.6/getting-started/introduction/)

## Next Steps

After setting up the basic structure, you should:

1. Implement the database migrations with the correct fields
2. Define model relationships
3. Create API endpoints
4. Build the admin panel interface
5. Integrate with the existing frontend

This guide provides a foundation for building your CMS. You'll need to customize it according to your specific requirements and preferences.