@extends('layouts.generic')

@section('title', 'HOME')

@section('google-font')
    @parent
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('js-csrf_token')
    const csrf_token = "{{ $csrf_token }}";
@endsection

@section('js-route')
    const routeFetchContents = "{{ route("/fetch/contents") }}";
    const routeFetchFavorites = "{{ route("/fetch/favorites") }}";
    const routeAddFavorite= "{{ route("/favorite/add") }}";
    const routeRemoveFavorite= "{{ route("/favorite/remove") }}";
@endsection

@section('js')
    @parent
    <script src="{{ asset('js/home.js') }}" defer></script>
@endsection

@section('header')
<header>
    <div id="overlay"></div>
    <h1>HomeWork Hotel</h1>
    <span>Camere di lusso, servizio in camera, piscina, bar, ristorante e tanto altro...</span>
</header>
@endsection

@section('article')
<section data-sec="fav-sec" class="fav-sec hidden"> {{-- fav section --}}
    <h2>PREFERITI</h2>
    <div class="fav-list">
        <div data-btn="fav-next" class="next-cont"></div>
        <div data-btn="fav-prev" class="prev-cont"></div>
    </div>
</section>

<section data-sec="search-sec"> {{-- search bar section --}}
    <form name="search-form" class="search-form">
        <span>Cerca per titolo</span>
        <div>
            <input type="text" name="search_bar" placeholder="Cerca...">
            <button type="submit">
                <img src="resources/icons/search-icon.png">
            </button>
        </div>
    </form>
</section>

<section data-sec="content-sec" class="content-sec"> {{-- content section --}}
    <div data-cont-msg="msg"  class="content-msg hidden">Nessun risultato trovato</div>
    <div data-btn="cont-next" class="next-cont"></div>
    <div data-btn="cont-prev" class="prev-cont"></div>
</section>
@endsection
