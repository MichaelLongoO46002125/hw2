<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MHW2 - @yield('title')</title>
        @section('google-font')
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=Cormorant+Unicase&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        @show

        @section('css')
            <link rel="stylesheet" href="{{ asset('css/generic.css') }}">
        @show

        <script type="text/javascript" defer>
            @yield('js-csrf_token')
            @yield('js-route')
        </script>

        @section('js')
            <script src="{{ asset('js/generic.js') }}" defer></script>
        @show
    </head>

    <body>
        @section('navbar')
        <nav>
            <a id="nav-logo" href="{{ route('home') }}">
                <img src="{{ asset('resources/icons/logo.png') }}"/>
                <span>Home</span>
            </a>
            <div id="nav-links">
                @if($authStatus["isAdmin"])
                    <a href="{{ route('admin/signup') }}">Registra Utente</a>
                @endif
                <a href="{{ route('catering') }}">Ristorazione</a>
                <a href="{{ route('gallery') }}">Galleria</a>
                <a href="{{ route('booking') }}">Prenotazione</a>
                @if($authStatus["authenticated"])
                    <a href="{{ route('logout') }}">Logout</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endif
            </div>
            <div id="nav-menu">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </nav>
        @show

        @yield('header')

        <article>
            @yield('article')
        </article>

        @section('footer')
        <footer>
            <span><strong>CREATORE SITO: </strong>Michael Longo O46002125</span>
            <span>
                <strong>INDIRIZZO: </strong><address>VIA NON ESISTENTE, 99 - 95100 CATANIA, ITALY</address><br>
                <strong>TEL: </strong><address>+39 095 XX XX XXX</address><br>
                <strong>MAIL: </strong><address>homeworkhotel@mhw.it</address>
            </span>
            <div id="footer-icon-cont">
                <a class="footer-icon">
                    <img src="{{ asset('resources/icons/facebook-icon.png') }}">
                </a>
                <a class="footer-icon">
                    <img src="{{ asset('resources/icons/instagram-icon.png') }}">
                </a>
            </div>
            <span id="copyright">
                © Copyright 2021 - HomeWork Hotel
            </span>
        </footer>
        @show
    </body>
</html>
