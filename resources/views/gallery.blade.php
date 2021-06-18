@extends('layouts.generic')

@section('title', 'GALLERIA')

@section('google-font')
    @parent
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre&display=swap" rel="stylesheet">
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/gallery.css') }}">
@endsection

@section('js-route')
    const routeFetchGallery = "{{ route('/fetch/gallery') }}";
@endsection

@section('js')
    @parent
    <script src="{{ asset('js/gallery.js') }}" defer></script>
@endsection

@section('article')
<section data-sec="modal-sec"class="modal hidden">

</section>
<section data-sec="gallery-sec" class="gallery">
    <h1 class="error hidden">Si è verificato un errore!</h1>
</section>
@endsection
