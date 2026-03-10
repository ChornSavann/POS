<!doctype html>
<html lang="en">
<head>
    @include('partial.header')
</head>
<style>
    /* បង្ខំឱ្យ Modal លោតមកលើ Backdrop */
    #lowStockModal {
        z-index: 9999 !important;
    }

    /* ប្រសិនបើផ្ទាំងងងឹតពេក ឬមើលមិនឃើញ Modal */
    .modal-backdrop {
        z-index: 9990 !important;
        /* បើនៅតែងងឹតស្លុប អាចសាកល្បង display: none; ជាបណ្ដោះអាសន្នដើម្បីតេស្ត */
    }

    /* កែសម្រួលឱ្យ Scroll ក្នុង Modal ដើរស្រួលលើ Mobile */
    .modal-dialog-scrollable .modal-body {
        -webkit-overflow-scrolling: touch;
    }
</style>
<style>
    /* ប្តូរពណ៌ Header */
    .app-header.navbar {
        background-color: #2c3e50 !important;
    }

    /* កែត្រង់នេះ៖ កុំប្រើ div ឬ span ទូទៅពេក */
    /* ចង្អុលទៅតែអក្សរ Menu និងឈ្មោះ User ប៉ុណ្ណោះ */
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
<style>
    /* ប្តូរពណ៌ផ្ទៃខាងក្រោយ Sidebar */
    aside.app-sidebar {
        background-color: #2c3e50 !important;
    }

    /* ប្រសិនបើបងចង់ឱ្យ Sidebar កាន់តែស្អាត បងអាចថែម Style ខាងក្រោមនេះ */

    /* កែពណ៌អក្សរក្នុង Sidebar Menu */
    aside.app-sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* ពណ៌ពេល Hover លើ Menu Sidebar */
    aside.app-sidebar .nav-link:hover {
        background-color: rgba(1, 0, 0, 0.1) !important;
        color: #ffffff !important;
    }

    /* ពណ៌ Menu ដែលកំពុង Active */
    aside.app-sidebar .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        border-left: 3px solid #3740f3; /* បន្ថែមបន្ទាត់ពណ៌លឿងពីមុខឱ្យលេចធ្លោ */
    }
    .app-content .container-fluid{
    padding-left:5px !important;
    padding-right:5px !important;

    }
    .app-content-header .container-fluid{
        padding-left:12px !important;
        padding-right:12px !important;

    }
    /* ធ្វើឱ្យ menu កូនៗរៀងចូលក្នុង */
    .nav-treeview .nav-item {
        padding-left: 1rem; /* បងអាចដំឡើងលេខនេះបាន បើចង់ឱ្យចូលក្នុងជាងនេះ */
    }

    /* បន្ថែមស្ទីលឱ្យ icon តូចជាងមុនបន្តិច ដើម្បីមើលទៅ Soft */
    .nav-treeview .nav-icon {
        font-size: 0.9rem;
    }

</style>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">

<div class="app-wrapper">

    {{-- Navbar --}}
    @include('partial.navbar')

    {{-- Sidebar --}}
    @include('partial.sidebar')

    {{-- Main Content --}}
    <main class="app-main">

        {{-- Page Header --}}
        <div class="app-content p-2">
            <div class="container-fluid">
                @yield('page-header')
            </div>
        </div>

        {{-- Page Content --}}
        <div class="app-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </main>

    {{-- Footer --}}
    @include('partial.footerData')

</div>

{{-- Scripts --}}
@include('partial.footer')

</body>
</html>
