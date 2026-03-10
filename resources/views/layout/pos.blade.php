<!doctype html>
<html lang="en">
<head>
    @include('partial.header')
</head>
<style>

    #lowStockModal {
        z-index: 9999 !important;
    }
    .modal-backdrop {
        z-index: 9990 !important;

    }
    .modal-dialog-scrollable .modal-body {
        -webkit-overflow-scrolling: touch;
    }
</style>
<style>
    /* ប្តូរពណ៌ Header */
    .app-header.navbar {
        background-color: #2c3e50 !important;
    }

    .app-header.navbar .nav-link,
    .app-header.navbar .navbar-brand,
    .app-header.navbar .user-name, /* បន្ថែម class ឱ្យឈ្មោះ user បើអាច */
    .app-header.navbar .nav-item {
        color: #ffffff !important;
    }

    /* ប្តូរពណ៌ Icon */
    .app-header.navbar i,
    .app-header.navbar .bi {
        color: #ffffff !important;
    }

    /* Hover Effect */
    .app-header.navbar .nav-link:hover {
        color: #f1c40f !important;
        background-color: rgba(255, 255, 255, 0.1);
    }

    /* គម្លាត Icons កុំឱ្យឃ្លាតគ្នាពេក */
    .app-header .navbar-nav .nav-item .nav-link {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    .navbar-badge {
        position: absolute;
        top: 6px;
        right: 5px;
        padding: 2px 4px;
        font-size: .6rem;
        font-weight: 400;
    }
</style>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">

<div class="app-wrapper">

    {{-- Navbar --}}
    @include('partial.navbar')

    {{-- Main Content --}}
    <main class="app-main">
        {{-- Page Header --}}
        <div class="app-content">
            <div class="container-fluid">
                @yield('page-header')
            </div>
        </div>
            <div class="container-fluid">
                @yield('content')
            </div>
    </main>
    {{-- Footer --}}
    @include('partial.footerData')

</div>

{{-- Scripts --}}
@include('partial.footer')

</body>
</html>
