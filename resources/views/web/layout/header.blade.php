
<!-- ========== Topbar Start ========== -->
      <div class="navbar-custom">
        <div class="topbar container-fluid">
            <div class="d-flex align-items-center gap-1">

                <!-- Topbar Brand Logo -->
                <div class="logo-topbar">
                    <!-- Logo light -->
                    <a href="/" class="logo-light">
                        <span class="logo-lg">
                            <img src="{{$websiteData->logo_website_url}}" alt="logo">
                        </span>
                        <span class="logo-sm">
                            <img src="{{$websiteData->logo_website_url}}" alt="small logo">
                        </span>
                    </a>

                    <!-- Logo Dark -->
                    <a href="/" class="logo-dark">
                        <span class="logo-lg">
                            <img src="{{$websiteData->logo_website_url}}" alt="dark logo">
                        </span>
                        <span class="logo-sm">
                            <img src="{{$websiteData->logo_website_url}}" alt="small logo">
                        </span>
                    </a>
                </div>

                @if (!request()->isCustomer)
                <!-- Sidebar Menu Toggle Button -->
                <button class="button-toggle-menu">
                    <i class="ri-menu-line"></i>
                </button>
                <!-- Horizontal Menu Toggle Button -->
                <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                @endif

            </div>

            @if (!request()->isCustomer)
            <ul class="topbar-menu d-flex align-items-center gap-3">
                <!-- <li class="d-none d-sm-inline-block">
                    <div class="nav-link" id="light-dark-mode">
                        <i class="ri-moon-line fs-22"></i>
                    </div>
                </li> -->
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle arrow-none nav-user" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <span class="account-user-avatar">
                            <img src="https://cdn2.iconfinder.com/data/icons/colored-simple-circle-volume-01/128/circle-flat-general-51851bd79-512.png" alt="user-image" width="32" class="rounded-circle">
                        </span>
                        <span class="d-lg-block d-none">
                            <h5 class="my-0 fw-normal">
                                {{\Auth::guard()->user()->name}}
                                <i
                                    class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"></i></h5>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                        <!-- item-->
                        <div class=" dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome  {{\Auth::guard()->user()->name}}!</h6>
                        </div>

                        <!-- item-->
                        <a href="{{route('profile')}}" class="dropdown-item">
                            <i class="ri-settings-4-line fs-18 align-middle me-1"></i>
                            <span>Settings</span>
                        </a>

                        <!-- item-->
                        <a href="{{route('logout')}}" class="dropdown-item">
                            <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
            @endif
        </div>
    </div>
    <!-- ========== Topbar End ========== -->


    <!-- ========== Left Sidebar Start ========== -->
    <div class="leftside-menu">

        <!-- Brand Logo Light -->
        <a href="#" class="logo logo-light bg-danger">
            <span class="logo-lg">
                <img src="{{$websiteData->logo_website_url}}" alt="logo" style="height: 35px !important;">
                <span class="logo-txt text-white">{{config('app.name')}}</span>
            </span>
            <span class="logo-sm">
                <img src="{{$websiteData->logo_website_url}}" alt="small logo">
            </span>
        </a>

        <!-- Brand Logo Dark -->
        <a href="#" class="logo logo-dark">
            <span class="logo-lg">
                <img src="{{$websiteData->logo_website_url}}" alt="logo" style="height: 35px !important;">
                <span class="logo-txt text-white">{{$websiteData->nama}}</span>
            </span>
            <span class="logo-sm">
                <img src="{{$websiteData->logo_website_url}}" alt="small logo">
            </span>
        </a>

        <!-- Sidebar -left -->
        <div class="h-100" id="leftside-menu-container" data-simplebar>
            <!--- Sidemenu -->
            <ul class="side-nav">
                @if (Auth::guard()->check())
                    @if (Auth::guard()->user()->role == 'admin')
                        @include('web.layout.menu_admin')
                    @elseif (Auth::guard()->user()->role == 'injector')
                        @include('web.layout.menu_injector')
                    @elseif (Auth::guard()->user()->role == 'kasir')
                        @if(request('isCustomer'))
                            @include('web.layout.menu_customer')
                        @else
                            @include('web.layout.menu_cashier')
                        @endif
                    @endif
                    
                @else
                    @include('web.layout.menu_customer')
                @endif
            </ul>
            <!--- End Sidemenu -->
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- ========== Left Sidebar End ========== -->
