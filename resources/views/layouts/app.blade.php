<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CRM') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script
        src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
        crossorigin="anonymous"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-UkVD+zxJKGsZP3s/JuRzapi4dQrDDuEf/kHphzg8P3v8wuQ6m9RLjTkPGeFcglQU" crossorigin="anonymous">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

    <script
        src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"
        integrity="sha256-xLD7nhI62fcsEZK2/v8LsBcb4lG7dgULkuXoXB/j91c="
        crossorigin="anonymous"></script>
</head>
<body>

@include('partials.menu')
<div class="wrapper d-flex flex-column min-vh-100" style="padding-left: 16rem;">
    <header class="header header-sticky">
        <div class="container-fluid">
            <form action="/search" class="header-search" method="GET">
                <div class="search-input-wrapper">
                    <input id="search" class="form-control" type="search" autocomplete="off" placeholder="Search..." name="s" aria-label="Search" value="{{ request()->get('s') }}">
                    <div id="searchResults"></div>
                </div>
                <select name="category" id="categoryName" class="form-control">
                    <option value="all" @if(request()->get('category') == 'all' || !request()->get('category')) selected @endif>All</option>
                    <option value="companies" @if(request()->get('category') == 'companies') selected @endif>Companies</option>
                    <option value="persons" @if(request()->get('category') == 'persons') selected @endif>Persons</option>
                </select>
                <button class="btn btn-search" type="submit">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Search</span>
                </button>
            </form>

            <script type="text/javascript">
                $(document).ready(function(){

                    $(document).click(function(event) {
                        var $target = $(event.target);
                        if(!$target.closest('#searchResults').length && 
                           !$target.closest('#search').length &&
                           $('#searchResults').is(":visible")) {
                            $('#searchResults').hide();
                        }
                    });

                    $('#search').keyup(function(){
                        var query = $(this).val();
                        if(query != '')
                        {
                            $.ajax({
                                url:"{{ route('autocomplete') }}",
                                method:"GET",
                                data:{s:query, category: $('#categoryName').val()},
                                success:function(data){
                                    $('#searchResults').fadeIn();
                                    $('#searchResults').html(data);
                                },
                                error:function(xhr, status, error){
                                    console.error('Search error:', error);
                                    $('#searchResults').hide();
                                }
                            });
                        } else {
                            $('#searchResults').hide();
                        }
                    });

                });
            </script>

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
                            <i class="fa-solid fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-sign-out-alt"></i>
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
            @yield('content')
        </div>
    </div>
    <footer class="footer">
        <div class="ms-auto" style="font-size: 10px; color: #ced4da;">Hardcoded OÜ v2.3.5</div>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@4.2.0/dist/js/coreui.bundle.min.js" integrity="sha384-n0qOYeB4ohUPebL1M9qb/hfYkTp4lvnZM6U6phkRofqsMzK29IdkBJPegsyfj/r4" crossorigin="anonymous"></script>

@stack('scripts')

</body>

</html>

