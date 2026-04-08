<nav class="app-header navbar navbar-expand text-white" style="background-color: #2c3e50 !important;">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <li class="nav-item d-none d-md-flex align-items-center me-3">
                <div id="digital-clock" class="fw-bold px-2 py-1 rounded"
                    style="font-family: 'Courier New', Courier, monospace; letter-spacing: 1px;">
                    00:00:00 AM
                </div>
            </li>
            <!--begin::Navbar Search-->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="bi bi-search"></i>
                </a>
            </li>
            <!--end::Navbar Search-->
            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#lowStockModal">
                    <i class="fa-regular fa-bell"></i>
                    @if ($lowStockCount > 0)
                        <span class="navbar-badge badge text-bg-danger">
                            {{ $lowStockCount > 99 ? '99+' : $lowStockCount }}
                        </span>
                    @endif
                </a>
                @include('partial.lowStock')
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fa-regular fa-house"></i>

                </a>
            </li>
            <!--end::Messages Dropdown Menu-->
            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
                <a class="nav-link" href="{{ route('order.index') }}">
                    <i class="fa-brands fa-windows"></i>

                </a>

            </li>
            <!--end::Notifications Dropdown Menu-->
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    {{-- ឆែកមើលថាបើមានរូបក្នុង Database ឱ្យបង្ហាញរូបនោះ បើអត់ទេឱ្យបង្ហាញរូប Default --}}
                    <img src="{{ Auth::user()->profile_picture ? asset('Image/users-image/' . Auth::user()->profile_picture) : asset('assets/img/user2-160x160.jpg') }}"
                        class="user-image rounded-circle shadow" style="width: 32px; height: 32px; object-fit: cover;"
                        {{-- បន្ថែម style ដើម្បីឱ្យរូបរាងមូលស្អាត --}} alt="User Image" />

                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        <img src="{{ auth()->user()->profile_picture ? asset('Image/users-image/' . auth()->user()->profile_picture) : asset('assets/img/user2-160x160.jpg') }}"
                            class="rounded-circle shadow" style="width: 90px; height: 90px; object-fit: cover;"
                            alt="User Image" />
                        <p>
                            {{ Auth::user()->name }} - Web Developer
                            <small>សមាជិកតាំងពី៖ {{ Auth::user()->created_at->format('M. Y') }}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                        <a href="#" class="btn btn-default btn-flat float-end"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sign out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
