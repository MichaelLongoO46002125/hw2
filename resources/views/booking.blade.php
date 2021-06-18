@extends('layouts.generic')

@section('title', 'PRENOTAZIONE')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/booking.css') }}">
@endsection

@section('js-csrf_token')
    const csrf_token = "{{ $csrf_token }}";
@endsection

@section('js-route')
    @if($authStatus["isAdmin"])
        const routeCheckEmail = "{{ route('/check/email', '') }}";
    @endif
    const routeFetchRooms = "{{ route('/fetch/rooms') }}";
    const routeBookingRoom = "{{ route('/fetch/booking') }}";
@endsection

@section('js')
    @parent
    @if($authStatus["isAdmin"])
        <script src="{{ asset('js/admin_booking.js') }}" defer></script>
    @elseif($authStatus["authenticated"])
        <script src="{{ asset('js/user_booking.js') }}" defer></script>
    @else
        <script src="{{ asset('js/booking_2.js') }}" defer></script>
    @endif
    <script src="{{ asset('js/booking.js') }}" defer></script>
@endsection

@section('article')
<section data-sec="booking-sec" class="booking-sec">
    <div data-sub-sec="search-side-bar" class="search-side-bar start-visible-hidden">
        <div data-sb-btn="sidebar-btn-close" class="close-sidebar"></div>
        <h3>CERCA</h3>
        <form name="search-form">
            <label>
                <strong>Check-In</strong>
                <input type="date" class="input" name="check_in" value="{{ date('Y-m-d') }}">
            </label>

            <label>
                <strong>Check-Out</strong>
                <input type="date" class="input" name="check_out" value="{{ date('Y-m-d', strtotime("+1 day")) }}">
            </label>

            <label>
                <strong>Numero persone</strong>
                <input type="number" class="input" name="persons_num" min="1" max="10" value="1" required>
            </label>

            <label class="label-checkbox">
                <input type="checkbox" name="matrimonial">
                <strong>Letto matrimoniale</strong>
            </label>

            <label class="label-checkbox">
                <input type="checkbox" name="single">
                <strong>Letto singolo</strong>
            </label>

            <label>
                <strong>Tariffa minima</strong>
                <input type="text" class="input" name="min_fee">
            </label>

            <label>
                <strong>Tariffa massima</strong>
                <input type="text" class="input" name="max_fee">
            </label>

            <input type="submit" value="CERCA">
        </form>
    </div>

    <div data-sub-sec="show-room" class="show-room">

    </div>
</section>

<section data-modal="error" class="modal-msg hidden">
    <div>
        <h3>ERRORE</h3>
        <p></p>
        <button type="button" data-modal-error="close" class="input confirm">CHIUDI</button>
    </div>
</section>

<section data-modal="message" class="modal-msg hidden">
    <div>
        <h3>ATTENZIONE</h3>
        <p></p>
        <button type="button" data-modal-msg="close" class="input confirm">CHIUDI</button>
    </div>
</section>

<section data-modal="booking" class="modal-booking hidden">
    <div>
        @if(!$authStatus["authenticated"])
            <span>Per prenotare devi effettuare il login</span>
            <button class="input confirm" type="button"><a href="{{ route('login') }}">Login</a></button>
            <span>oppure</span>
            <span>Chiama al +390950000000</span>
        @else
            <h2>CONFERMA</h2>
            <h3></h3> {{-- Tipo+Sistemazione --}}
            <span></span> {{-- Prezzo --}}

            @if($authStatus["isAdmin"])
                <label>
                    <strong>Email</strong>
                    <input type="text" class="input" data-modal-in="email">
                </label>
                <span class="error hidden" data-modal-msg="email_error"></span>
                <form method="post" name="reg-email-form" class="hidden" action="{{ route("admin/signup") }}">
                    <input name="_token" type="hidden" value="{{ $csrf_token }}">
                    <input type="hidden" name="email" value="">
                    <input type="hidden" name="from" value="booking">
                    <input type="submit" class="input confirm-form" value="REGISTRA CLIENTE">
                </form>
            @endif

            <div class="check-date"> {{-- Conferma date --}}
                <label>
                    <strong>Check-In</strong>
                    <input type="date" class="input" data-modal-in="check_in" value="{{ date('Y-m-d') }}">
                </label>
                <label>
                    <strong>Check-Out</strong>
                    <input type="date" class="input" data-modal-in="check_out" value="{{ date('Y-m-d', strtotime("+1 day")) }}">
                </label>
            </div>
            <span class="error hidden" data-modal-msg="error"></span>

            <div class="button-area">
                <button class="input confirm" data-modal-in="close"  type="button">CHIUDI</button>
                <button class="input confirm" data-modal-in="submit" type="button">PRENOTA</button>
            </div>
        @endif
    </div>
</section>

<div data-sb-btn="sidebar-btn-open" class="open-sidebar start-hidden-visible"></div>
@endsection
