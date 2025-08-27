<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Economic Homeo Pharmacy Admin</title>
    
    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template-->
    <style>
        :root {
            --primary: #28a745;
            --secondary: #6c757d;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fc;
        }
        
        /* Sidebar */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #4e73df;
            background: linear-gradient(180deg, #28a745 10%, #1cc88a 100%);
            transition: all 0.3s;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            overflow-x: hidden;
        }
        
        .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            letter-spacing: 0.05rem;
            z-index: 1;
        }
        
        .sidebar-brand-icon img {
            max-height: 2rem;
        }
        
        .sidebar-brand-text {
            color: white;
            display: inline;
        }
        
        .sidebar hr.sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 0 1rem 1rem;
        }
        
        .sidebar-heading {
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.75rem;
            font-weight: 800;
            padding: 0 1rem;
            text-transform: uppercase;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.85rem;
            display: block;
            text-decoration: none;
        }
        
        .nav-link:hover {
            color: white;
        }
        
        .nav-link.active {
            color: white;
            font-weight: 700;
        }
        
        .nav-link i {
            margin-right: 0.25rem;
            font-size: 0.85rem;
            width: 1.5rem;
            text-align: center;
        }
        
        /* Content */
        .content-wrapper {
            margin-left: 250px;
            min-height: 100vh;
        }
        
        /* Topbar */
        .topbar {
            height: 4.375rem;
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .topbar .dropdown-list {
            padding: 0;
            border: none;
            overflow: hidden;
        }
        
        .topbar .dropdown-list .dropdown-header {
            background-color: var(--primary);
            border: 1px solid var(--primary);
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            color: white;
        }
        
        .topbar .dropdown-list .dropdown-item {
            white-space: normal;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            border-left: 1px solid #e3e6f0;
            border-right: 1px solid #e3e6f0;
            border-bottom: 1px solid #e3e6f0;
            line-height: 1.3rem;
        }
        
        .topbar .dropdown-menu {
            position: absolute;
        }
        
        /* Cards */
        .card {
            margin-bottom: 24px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .card-header h6 {
            font-weight: 700;
            font-size: 1rem;
            color: var(--primary);
        }
        
        .border-left-primary {
            border-left: 0.25rem solid var(--primary) !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid var(--success) !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid var(--info) !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid var(--warning) !important;
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100px;
            }
            
            .sidebar .sidebar-brand-text {
                display: none;
            }
            
            .sidebar .nav-item .nav-link span {
                display: none;
            }
            
            .content-wrapper {
                margin-left: 100px;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="sidebar navbar-nav">
            <li class="sidebar-brand d-flex align-items-center justify-content-center">
                <div class="sidebar-brand-icon">
                    <img src="/images/logo.png" alt="Logo">
                </div>
                <div class="sidebar-brand-text mx-3">EHP Admin</div>
            </li>
            
            <hr class="sidebar-divider my-0">
            
            <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <div class="sidebar-heading">Content</div>
            
            <li class="nav-item {{ request()->is('admin/products*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.products.index') }}">
                    <i class="fas fa-fw fa-box"></i>
                    <span>Products</span>
                </a>
            </li>
            
            <li class="nav-item {{ request()->is('admin/categories*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Categories</span>
                </a>
            </li>
            
            <hr class="sidebar-divider">
            
            <div class="sidebar-heading">Settings</div>
            
            <li class="nav-item">
                <a class="nav-link" href="#" id="logout-link">
                    <i class="fas fa-fw fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>
                    
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small" id="admin-name">Admin</span>
                            <i class="fas fa-user-circle fa-fw"></i>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#" id="logout-dropdown">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            
            <!-- Main Content -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get admin user data from localStorage
            const adminUser = JSON.parse(localStorage.getItem('admin_user') || '{}');
            if (adminUser.name) {
                document.getElementById('admin-name').textContent = adminUser.name;
            }
            
            // Logout functionality
            const logoutLink = document.getElementById('logout-link');
            const logoutDropdown = document.getElementById('logout-dropdown');
            
            function handleLogout() {
                const token = localStorage.getItem('admin_token');
                
                if (token) {
                    fetch('/api/admin/logout', {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        // Clear local storage regardless of response
                        localStorage.removeItem('admin_token');
                        localStorage.removeItem('admin_user');
                        window.location.href = '{{ route("admin.login") }}';
                    })
                    .catch(error => {
                        console.error('Logout error:', error);
                        // Still clear local storage and redirect
                        localStorage.removeItem('admin_token');
                        localStorage.removeItem('admin_user');
                        window.location.href = '{{ route("admin.login") }}';
                    });
                } else {
                    window.location.href = '{{ route("admin.login") }}';
                }
            }
            
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleLogout();
                });
            }
            
            if (logoutDropdown) {
                logoutDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    handleLogout();
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>