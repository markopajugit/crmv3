<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Stylesheets -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>

@include('partials.menu')
<div class="wrapper d-flex flex-column min-vh-100 bg-light" style="padding-left: 16rem;">
    <header class="header header-sticky">
        <div class="container-fluid">
            <form action="/search/" class="header-search">
                <input id="search" class="form-control" type="search" autocomplete="off" placeholder="Search..." name="s" aria-label="Search" value="{{ request()->get('s') }}" data-autocomplete-route="{{ route('autocomplete') }}">
                <div id="searchResults" style="display: none;"></div>
                <select name="category" id="categoryName" class="form-control">
                    <option value="all" @if(request()->get('category') == 'all') selected @endif>All</option>
                    <option value="companies" @if(request()->get('category') == 'companies') selected @endif>Companies</option>
                    <option value="persons" @if(request()->get('category') == 'persons') selected @endif>Persons</option>
                </select>
                <button class="btn btn-primary" type="submit">Search</button>
            </form>

            <ul class="navbar-nav ms-auto">

                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else

                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </header>
    <div class="body flex-grow-1">
        <div class="container-fluid">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p class="mb-0">{{ $message }}</p>
                </div>
            @endif

            @yield('content')

        </div>
    </div>
    <footer class="footer">
        <div class="ms-auto">Hardcoded OÜ v1.5.4</div>
    </footer>
</div>

<!-- Scripts -->
<script>
    console.log('=== INLINE SCRIPT BEFORE VENDOR.JS ===');
    // Uncomment to test: alert('JavaScript is working!');
</script>
@if(file_exists(public_path('js/vendor.js')))
<script src="{{ mix('js/vendor.js') }}"></script>
@endif
<script>
    console.log('=== INLINE SCRIPT BEFORE APP.JS ===');
</script>
<script src="{{ mix('js/app.js') }}"></script>
<script>
    console.log('=== INLINE SCRIPT AFTER APP.JS ===');
    console.log('jQuery available:', typeof $ !== 'undefined', typeof jQuery !== 'undefined');
    console.log('CompanyEditor available:', typeof CompanyEditor !== 'undefined', typeof window.CompanyEditor !== 'undefined');
</script>

@stack('scripts')

</body>

</html>

