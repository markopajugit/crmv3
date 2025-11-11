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
                <input id="search" class="form-control mr-sm-2" style="width:45%; display: inline-block;" type="search" autocomplete="off" placeholder="Search" name="s" aria-label="Search" value="{{ request()->get('s') }}" data-autocomplete-route="{{ route('autocomplete') }}">
                <div id="searchResults" style=" display:none;position: absolute;padding: 10px;list-style: none;"></div>
                <select name="category" id="categoryName" style="width:30%; display: inline-block;" class="form-control">
                    <option value="all" @if(request()->get('category') == 'all') selected @endif>All</option>
                    <option value="companies" @if(request()->get('category') == 'companies') selected @endif>Companies</option>
                    <option value="persons" @if(request()->get('category') == 'persons') selected @endif>Persons</option>
                </select>
                <button class="btn" style="width: 20%;top: -2px;position: relative;" type="submit">Search</button>
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
    <div class="body flex-grow-1 px-3 p-2">
        <div class="container-fluid">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <p>{{ $message }}</p>
                </div>
            @endif

            @yield('content')

        </div>
    </div>
    <footer class="footer">
        <div class="ms-auto" style="font-size: 10px; color: #ced4da;">Hardcoded OÜ v1.5.4</div>
    </footer>
</div>

<!-- Scripts -->
<script src="{{ mix('js/vendor.js') }}"></script>
<script src="{{ mix('js/app.js') }}"></script>

</body>

</html>

