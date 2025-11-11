<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex" style="background: #000000;">
        <a class="navbar-brand" href="{{ url('/dashboard') }}">
            <!--<img src="{{ asset('images/logo.png') }}" style="height: 100px;">-->
            <img src="{{ asset('images/wisor-logo.jpg') }}" style="margin-left:12px;width:150px;" alt="logo">
        </a>
    </div>
    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar="init" style="background: #000000;">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" style="height: 100%;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/persons') }}">Persons</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/companies') }}">Companies</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/orders') }}">Orders</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/invoices') }}">Invoices</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/renewals') }}">Renewals</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/documents') }}">Documents</a></li>
                            <!--<li class="nav-item"><a class="nav-link" href="{{ url('/proformas') }}">Proformas</a></li>-->
                            <li class="nav-item"><a class="nav-link" href="{{ url('/services') }}">Services</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/users') }}">Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/search/detailed') }}">Detailed Search</a></li>
                            <!--<li class="nav-item"><a class="nav-link" href="{{ url('/settings') }}">Settings</a></li>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ul>
</div>

<style>
    .sidebar-nav .nav-link {
        text-align: center;
        display: block;
        font-size: 20px;
    }
</style>
