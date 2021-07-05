<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Scripts -->
    <script src="{{ asset(mix('js/app.js')) }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ asset(mix('css/vendor.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md top-bar bg-primary bg-topheader d-none d-sm-block p-0">
            <div class="container app-container d-lg-flex">
                <div class="d-flex align-items-center mr-auto">
                    @if(!empty($siteSettings['top_bar_email']))
                    <p class="text-light mb-0 mr-4 top-email">
                        <a class="text-reset" href="mailto:{{$siteSettings['top_bar_email']}}"><i class="fas fa-envelope"></i> {{$siteSettings['top_bar_email']}}</a>
                    </p>
                    @endif
                    @if(!empty($siteSettings['top_bar_phone']))
                    <p class="text-light mb-0 top-phone">
                        <a class="text-reset" href="tel:{{$siteSettings['top_bar_phone']}}"><i class="fas fa-phone-alt"></i> {{$siteSettings['top_bar_phone']}}</a>
                    </p>
                    @endif
                </div>
                <div class="d-flex align-items-center ml-auto">
                    <p class="mb-0 text-light">@auth <span class="mr-1">Welcome,</span> {{ Auth::user()->full_name }} @endauth</p>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-expand-md navbar-light bg-white  border-bottom border-light p-0">
            <div class="container app-container">
                <a class="navbar-brand navbar-logo" href="{{ url('/') }}">
                    @if(!empty($siteSettings['logo'])) 
                        <img class="u-logo-desktop img-fluid p-2" src="{{$siteSettings['logo']}}" alt="logo-img">
                    @else
                        <img class="u-logo-desktop img-fluid p-2" src="{{ asset('images/logo.svg') }}" alt="logo-img">
                    @endif
                    @if(!empty($siteSettings['mini_logo'])) 
                        <img class="u-logo-mobile img-fluid p-2" src="{{$siteSettings['mini_logo']}}" alt="logo-img">
                    @else
                        <img class="u-logo-mobile img-fluid p-2" src="{{ asset('images/mini-logo.svg') }}" alt="logo-img">
                    @endif
                </a>
                <button class="navbar-toggler border-0 text-muted" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle Navigation">
                    <i class="fas fa-bars"></i>
                </button>
        
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto main-menu">
                    <!-- Authentication Links -->
                    @guest
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endguest
                    </ul>
                </div>
            </div>
        </nav>
        
        <main class="content">
            @yield('content')
        </main>
    </div>
    <footer class="app-footer bg-dark bg-footer ">
        <div class="container footer-container d-lg-flex py-3">
            <div class="d-flex align-items-center mr-auto">
                <p class="text-light mb-0 copyright-text">{!!$siteSettings['footer_text']!!}</p>
            </div>
        </div>
    </footer>
    
</body>
</html>
