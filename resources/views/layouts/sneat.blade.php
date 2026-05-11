<!DOCTYPE html>
<html
lang="en"
class="light-style layout-menu-fixed"
dir="ltr"
data-theme="theme-default"
data-assets-path="{{ asset('sneat/assets/') }}"
data-template="vertical-menu-template-free"
>
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat/assets/img/favicon/favicon.ico') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
    />
    
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/fonts/boxicons.css') }}" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    @stack('styles')
    
    <!-- Helpers -->
    <script src="{{ asset('sneat/assets/vendor/js/helpers.js') }}"></script>
    
    <!-- Template config files -->
    <script src="{{ asset('sneat/assets/js/config.js') }}"></script>

    <style>
        /* Clean Sidebar Toggle */
        .layout-menu-toggle {
            cursor: pointer;
            font-size: 1.5rem;
            color: #697a8d;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        
        .layout-menu-toggle:hover {
            background-color: rgba(105, 108, 255, 0.1);
            color: #696cff;
        }

        /* Ensure content expands when menu is collapsed */
        html.layout-menu-collapsed .layout-menu,
        body.layout-menu-collapsed .layout-menu {
            transform: translateX(-100%) !important;
        }
        
        html.layout-menu-collapsed .layout-page,
        body.layout-menu-collapsed .layout-page {
            margin-left: 0 !important;
            width: 100% !important;
        }

        .layout-menu,
        .layout-page {
            transition: margin-left 0.3s ease, width 0.3s ease, transform 0.3s ease !important;
        }

        /* Animation */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .layout-menu-toggle:active i {
            animation: pulse 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('partials.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="d-flex align-items-center">
                        <!-- Single Toggle Button (Works for both mobile & desktop) -->
                        <div class="layout-menu-toggle me-3" id="menuToggle">
                            <i class="bx bx-menu"></i>
                        </div>
                    </div>
                    
                    <!-- Navbar Content -->
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User dropdown -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <span class="avatar-initial rounded-circle bg-primary">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <span class="avatar-initial rounded-circle bg-primary">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                                    <small class="text-muted">{{ auth()->user()->role ?? 'User' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-fluid flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('partials.footer')
                    <!-- / Footer -->
                    
                    <div class="content-backdrop fade"></div>
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    @stack('scripts')
    
    <!-- Main JS -->
    <script src="{{ asset('sneat/assets/js/main.js') }}"></script>
    
    <!-- Page JS -->
    @yield('page-js')

    <!-- ✅ Fixed Sidebar Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('menuToggle');
        
        // Restore saved state
        if (localStorage.getItem('layoutMenuCollapsed') === 'true') {
            document.documentElement.classList.add('layout-menu-collapsed');
            document.body.classList.add('layout-menu-collapsed');
        }

        function toggleMenu() {
            const isCollapsed = !document.documentElement.classList.contains('layout-menu-collapsed');
            
            document.documentElement.classList.toggle('layout-menu-collapsed');
            document.body.classList.toggle('layout-menu-collapsed');
            
            localStorage.setItem('layoutMenuCollapsed', isCollapsed);
            
            // Update icon
            if (toggleBtn) {
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bx-menu', !isCollapsed);
                    icon.classList.toggle('bx-menu-alt-right', isCollapsed);
                }
            }
            
            window.dispatchEvent(new Event('resize'));
        }

        // Click handler
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMenu();
            });
        }

        // Overlay click (mobile)
        const overlay = document.querySelector('.layout-overlay');
        if (overlay) {
            overlay.addEventListener('click', function() {
                if (window.innerWidth < 1200) {
                    document.documentElement.classList.remove('layout-menu-collapsed');
                    document.body.classList.remove('layout-menu-collapsed');
                    localStorage.setItem('layoutMenuCollapsed', 'false');
                }
            });
        }

        // Responsive handling
        function handleResize() {
            if (window.innerWidth < 1200) {
                document.documentElement.classList.remove('layout-menu-collapsed');
                document.body.classList.remove('layout-menu-collapsed');
            }
        }
        
        window.addEventListener('resize', handleResize);
        handleResize();
    });
    </script>

    <!-- Your existing custom styles -->
    <style>
    /* Custom staff position colors */
    .badge-field-executive { background-color: #007BFF; color: white; }
    .badge-sales-manager   { background-color: #28A745; color: white; }
    .badge-field-worker    { background-color: #FFC107; color: white; }
    .badge-admin           { background-color: #6F42C1; color: white; }
    .badge-other           { background-color: #6c757d; color: white; }

    /* Clean table styling */
    .table { border-collapse: collapse; }
    .table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
    }
    .table-hover tbody tr:hover { background-color: rgba(0, 0, 0, 0.02); }
    </style>
</body>
</html>