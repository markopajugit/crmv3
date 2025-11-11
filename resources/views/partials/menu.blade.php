<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        <a class="navbar-brand" href="{{ url('/dashboard') }}">
            <img src="{{ asset('images/logo.svg') }}" class="header-logo" style="width:150px;height:auto;" alt="logo">
        </a>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="init">
        <div class="simplebar-wrapper">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset">
                    <div class="simplebar-content-wrapper">
                        <div class="simplebar-content">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                                    <i class="fa-solid fa-gauge-high"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('persons*') ? 'active' : '' }}" href="{{ url('/persons') }}">
                                    <i class="fa-solid fa-users"></i>
                                    <span>Persons</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('companies*') ? 'active' : '' }}" href="{{ url('/companies') }}">
                                    <i class="fa-solid fa-building"></i>
                                    <span>Companies</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('orders*') ? 'active' : '' }}" href="{{ url('/orders') }}">
                                    <i class="fa-solid fa-file-lines"></i>
                                    <span>Orders</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('invoices*') ? 'active' : '' }}" href="{{ url('/invoices') }}">
                                    <i class="fa-solid fa-receipt"></i>
                                    <span>Invoices</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('renewals*') ? 'active' : '' }}" href="{{ url('/renewals') }}">
                                    <i class="fa-solid fa-rotate"></i>
                                    <span>Renewals</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('documents*') ? 'active' : '' }}" href="{{ url('/documents') }}">
                                    <i class="fa-solid fa-folder"></i>
                                    <span>Documents</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('services*') ? 'active' : '' }}" href="{{ url('/services') }}">
                                    <i class="fa-solid fa-briefcase"></i>
                                    <span>Services</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ url('/users') }}">
                                    <i class="fa-solid fa-user-gear"></i>
                                    <span>Users</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('search*') ? 'active' : '' }}" href="{{ url('/search/detailed') }}">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <span>Detailed Search</span>
                                </a>
                            </li>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ul>
</div>
