@extends('layouts.generic')

@section('title', 'RISTORAZIONE')

@section('google-font')
    @parent
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/catering.css') }}">
@endsection

@section('js-route')
    const routeFetchCatering = "{{ route('/fetch/catering') }}";
@endsection

@section('js')
    @parent
    <script src="{{ asset('js/catering.js') }}" defer></script>
@endsection

@section('article')
<section data-sec="menu-sec" class="menu-sec">
    <div data-subSec="menu-list" class="menu-list">
        <h3>Menu</h3>
        <h4 class="error hidden">Si è verificato un errore</h4>
    </div>

    <div data-subSec="recipe-details" class="recipe">
        <div data-btn="close-rm" class="close-recipe"></div>
        <h2></h2>
        <img>
        <div>
            <span class="label">Cucina: </span>
            <span data-detail="cuisines"></span>
        </div>
        <div>
            <span class="label">Tipo di pasto: </span>
            <span data-detail="dish-types"></span>
        </div>
        <div>
            <span class="label">Vegano:</span>
            <span data-detail="vegan"></span>
        </div>
        <div>
            <span class="label">Vegetariano: </span>
            <span data-detail="vegetarian"></span>
        </div>
        <span class="label" data-detail="gluten"></span>
        <span class="label" data-detail="dairy"></span>
        <h3>Ingredienti:</h3>
        <span data-detail="ingredients"></span>
    </div>
</section>
@endsection
