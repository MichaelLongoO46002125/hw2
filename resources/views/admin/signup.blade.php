@extends('layouts.generic')

@section('title', 'REGISTRA UTENTE')

@section('css')
    @parent
    <link rel="stylesheet" href="{{ asset('css/admin_signup.css') }}">
@endsection

@section('js-route')
    const routeCheckEmail = "{{ route('/check/email', '') }}";
@endsection

@section('js')
    @parent
    <script src="{{ asset('js/admin_signup.js') }}" defer></script>
@endsection

@section('article')
<section data-sec="signup-sec">
    <form name="signup-form" method="post">
        <input name="_token" type="hidden" value="{{ $csrf_token }}">
        <h3>REGISTRA UTENTE</h3>
        @error('job')
            <span>Si è verificato un errore!</span>
        @enderror
        <span data-error="general"
        @error('general')
            class="general"
        @else
            class="general hidden"
        @enderror
        >Riempire tutti i campi!</span>

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
        <span data-error="tel" @error('tel') @else class="hidden" @enderror>Numero inserito non valido!</span>

        <label class="select-job">
            <strong>RUOLO:</strong>
            <select name="job" >
                <option value="{{ config('hw.auth.user') }}" @if( old('job') === config('hw.auth.user') ) selected @endif>USER</option>
                <option value="{{ config('hw.auth.admin') }}" @if( old('job') === config('hw.auth.admin') ) selected @endif>ADMIN</option>
                <option value="{{ config('hw.auth.bartender') }}" @if( old('job') === config('hw.auth.bartender') ) selected @endif>BARISTA</option>
                <option value="{{ config('hw.auth.chef') }}" @if( old('job') === config('hw.auth.chef') ) selected @endif>CHEF</option>
                <option value="{{ config('hw.auth.waiter') }}" @if( old('job') === config('hw.auth.waiter') ) selected @endif>CAMERIERE/A</option>
            </select>
        </label>

        <div data-subform="subform" @if( old('job') === config('hw.auth.user') || old('job') === null ) class="subform none" @else class="subform" @endif>
            <label>
                <strong>Inizio turno:</strong>
                <input type="text" name="duty_start" value="{{ old('duty_start') }}">
                <span data-error="duty_start" @error('duty_start') @else class="hidden" @enderror>Orario non valido! [Formato 00:00]!</span>
            </label>

            <label>
                <strong>Fine turno:</strong>
                <input type="text" name="duty_end" value="{{ old('duty_end') }}">
                <span data-error="duty_end" @error('duty_end') @else class="hidden" @enderror>Orario non valido! [Formato 00:00]!</span>
            </label>

            <label>
                <strong>Salario:</strong>
                <input type="text" name="salary" value="{{ old('salary') }}">
                <span data-error="salary" @error('salary') @else class="hidden" @enderror>Salario non valido!</span>
            </label>
        </div>

        <input type="submit" value="Registra utente">
    </form>
</section>
@endsection
