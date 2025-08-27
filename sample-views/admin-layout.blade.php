<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - EHP Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom Admin CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h3>EHP Admin</h3>
                <button type="button" id="sidebarCollapse" class="btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a href="#productSubmenu" data-toggle="collapse" aria-expanded="{{ request()->is('admin/products*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <ul class="collapse list-unstyled {{ request()->is('admin/products*') ? 'show' : '' }}" id="productSubmenu">
                        <li>
                            <a href="{{ route('admin.products.index') }}">All Products</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.products.create') }}">Add New</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <a href="#categorySubmenu" data-toggle="collapse" aria-expanded="{{ request()->is('admin/categories*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fas fa-tags"></i> Categories
                    </a>
                    <ul class="collapse list-unstyled {{ request()->is('admin/categories*') ? 'show' : '' }}" id="categorySubmenu">
                        <li>
                            <a href="{{ route('admin.categories.index') }}">All Categories</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories.create') }}">Add New</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                </li>
                <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                    <a href="#userSubmenu" data-toggle="collapse" aria-expanded="{{ request()->is('admin/users*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fas fa-users"></i> Users
                    </a>
                    <ul class="collapse list-unstyled {{ request()->is('admin/users*') ? 'show' : '' }}" id="userSubmenu">
                        <li>
                            <a href="{{ route('admin.users.index') }}">All Users</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.create') }}">Add New</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->is('admin/pages*') ? 'active' : '' }}">
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="{{ request()->is('admin/pages*') ? 'true' : 'false' }}" class="dropdown-toggle">
                        <i class="fas fa-file-alt"></i> Pages
                    </a>
                    <ul class="collapse list-unstyled {{ request()->is('admin/pages*') ? 'show' : '' }}" id="pageSubmenu">
                        <li>
                            <a href="{{ route('admin.pages.index') }}">All Pages</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.pages.create') }}">Add New</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content" class="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapseSmall" class="btn d-md-none">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="ml-auto d-flex align-items-center">
                        <!-- Notifications -->
                        <div class="dropdown mr-3">
                            <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="badge badge-danger">3</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
                                <a class="dropdown-item" href="#">New order received</a>
                                <a class="dropdown-item" href="#">Product stock low</a>
                                <a class="dropdown-item" href="#">New user registered</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">View all notifications</a>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="https://via.placeholder.com/30" class="rounded-circle mr-2" alt="Admin">
                                <span>Admin</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">Profile</a>
                                <a class="dropdown-item" href="#">Settings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('#sidebarCollapse, #sidebarCollapseSmall').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>