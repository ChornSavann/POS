 <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />

 <meta name="color-scheme" content="light dark" />
 <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
 <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
 <!--end::Accessibility Meta Tags-->
 <!--begin::Primary Meta Tags-->
 <meta name="title" content="AdminLTE v4 | Dashboard" />
 <meta name="author" content="ColorlibHQ" />
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="description"
     content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
 <meta name="keywords"
     content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />

 <!--end::Primary Meta Tags-->
 <!--begin::Accessibility Features-->
 <!-- Skip links will be dynamically added by accessibility.js -->
 <meta name="supported-color-schemes" content="light dark" />
 <link rel="preload" href="{{ asset('./css/adminlte.css') }}" as="style" />
 <!--end::Accessibility Features-->
 <!--begin::Fonts-->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
     integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
     onload="this.media='all'" />
 <!--end::Fonts-->
 <!--begin::Third Party Plugin(OverlayScrollbars)-->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
     crossorigin="anonymous" />
 <!--end::Third Party Plugin(OverlayScrollbars)-->
 <!--begin::Third Party Plugin(Bootstrap Icons)-->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
     crossorigin="anonymous" />
 <!--end::Third Party Plugin(Bootstrap Icons)-->
 <!--begin::Required Plugin(AdminLTE)-->
 <link rel="stylesheet" href="{{ asset('./css/adminlte.css') }}" />
 <!--end::Required Plugin(AdminLTE)-->
 <!-- apexcharts -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
     integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />
 <!-- jsvectormap -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
     integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
     integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
     crossorigin="anonymous" referrerpolicy="no-referrer" />
 <link rel="icon" href="{{ asset('assets\logo-rest.jpg') }}" type="image/png">

 <style>
     /* បង្ខំឱ្យ Modal បង្ហាញចេញមកក្រៅ */
     body.modal-open {
         overflow: hidden;
     }

     /* បើវាជាន់ងងឹតស្លុប លុប Backdrop ចោលតែម្ដង */
     .modal-backdrop {
         display: none !important;
     }

     /* បន្ថែមពណ៌ស្រាលៗនៅពីក្រោយ Modal ជំនួសវិញ */
     .modal {
         background: rgba(0, 0, 0, 0.5);
     }
 </style>
