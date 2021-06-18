@extends('layouts.generic')

@section('title', 'SIGNUP')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}">
@endsection

@section('js-route')
    const routeCheckEmail = "{{ route('/check/email', '') }}";
@endsection

@section('js')
    @parent
    <script src="{{ asset('js/signup.js') }}" defer></script>
@endsection

@section('article')
<section data-sec="signup-sec">
    <form name="signup-form" method="post">
        <input name="_token" type="hidden" value="{{ $csrf_token }}">
        <h3>REGISTRATI</h3>

        <span data-error="general" class="general @error('general') @else hidden @enderror">Riempire tutti i campi!</span>

        <label>
            <strong>NOME:</strong>
            <input type="text" name="name" value="{{ old('name') }}">
        </label>
        <span data-error="name" @error('name') @else class="hidden" @enderror>Il nome non può essere vuoto!</span>

        <label>
            <strong>COGNOME:</strong>
            <input type="text" name="last_name" value="{{ old('last_name') }}">
        </label>
        <span data-error="last_name" @error('last_name') @else class="hidden" @enderror>Il cognome non può essere vuoto!</span>

        <label>
            <strong>EMAIL:</strong>
            <input type="text" name="email" value="{{ old('email') }}">
        </label>
        @if (!$errors->has("email") && !$errors->has("email2"))
            <span data-error="email" class="hidden">Default</span>
        @else
            <span data-error="email">@if ($errors->has("email"))
                    Email non valida!
                @else
                    Email già in uso!
                @endif
            </span>
        @endif

        <label>
            <strong>TELEFONO:</strong>
            <input type="text" name="tel" value="{{ old('tel') }}">
        </label>
        <span data-error="tel" @error('tel') @else class="hidden" @enderror>Inserire un numero valido!</span>

        <label>
            <strong>PASSWORD:</strong>
            <input type="password" name="pw">
        </label>
        @if (!$errors->has("pw") && !$errors->has("pw2"))
            <span data-error="pw" class="hidden">Default</span>
        @else
            <span data-error="pw">@if ($errors->has("pw"))
                    La password deve contenere almeno 8 caratteri!
                @endif
                @if ($errors->has("pw2"))
                    La password deve contenere almeno 1 lettera maiuscola, almeno 1 lettera minuscola e almeno 1 numero!
                @endif
            </span>
        @endif

        <label>
            <strong>CONFERMA PASSWORD:</strong>
            <input type="password" name="cpw">
        </label>
        <span data-error="cpw" @error('cpw') @else class="hidden" @enderror>Le due password devono coincidere!</span>

        <div>
            <input type="submit" value="Registrati">
            <button type="button"><a href="{{ route('login') }}">Login</a></button>
        </div>
    </form>
</section>
@endsection
