@extends('layouts.generic')

@section('title', 'LOGIN')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('js')
    @parent
    <script src="{{ asset('js/login.js') }}" defer></script>
@endsection

@section('article')
<section data-sec="login-sec">
    <form name="login-form" method="post">
        <input name="_token" type="hidden" value="{{ $csrf_token }}">
        <h3>EFFETTUA IL LOGIN</h3>

        <span data-error="general" @error('general') class="general" @else class="general hidden" @enderror>
            Compilare tutti i campi!
        </span>

        <label>
            <strong>EMAIL:</strong>
            <input type="text" name="email" value="{{ old('email') }}"">
        </label>
        <span data-error="email" @error('email') @else class="hidden" @enderror>
            Email non riconosciuta!
        </span>

        <label>
            <strong>PASSWORD:</strong>
            <input type="password" name="password">
        </label>
        <span data-error="pw" @error('password') @else class="hidden" @enderror>
            Password errata!
        </span>

        <div class="remember">
            <input type="checkbox" name="remember">Ricorda l'accesso
        </div>

        <div>
            <input type="submit" value="Login">
            <button type="button"><a href="{{ route('signup') }}">Registrati</a></button>
        </div>
    </form>
</section>
@endsection
