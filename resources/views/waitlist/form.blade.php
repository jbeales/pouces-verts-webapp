<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste d'attente - Waitlist</title>
    <link rel="stylesheet" href="{{ mix('/css/main.css') }}">
    <link rel="stylesheet" href="{{ mix('/css/forms.css') }}">
</head>
<body>

<form action="{{ route('liste-attente.store') }}" method="POST" id="waitlist-form">
    @csrf
    <h1>Inscrivez-vous sur la liste d'attente</h1>

    @if (session('status') && session('status') === 'already on list')
        <div class="alert alert-warning" role="alert">
            <p>Vous êtes déja inscrit sur la liste d'attente! Quelqu'un vous contactera quand une place est disponible.</p>
            <p>You are already on the wait list! Someone will contact you when a place is available.</p>
        </div>
    @endif

    @if (session('status') && session('status') === 'saved')
        <div class="alert alert-success" role="alert">
            <p>Vous avez été ajouté à notre liste d'attente. Quelqu'un vous contactera quand une place est disponible.</p>
            <p>You have been added to the waitlist. Someone will contact you when a place is available.</p>
        </div>
    @endif

    <fieldset>
        <p>
            <label for="name">Nom / Name:</label>
            <input type="text" name="name" id="name" value="{{old('name')}}" required>
            @error('name')
                <span class="error">{{$message}}</span>
            @enderror
        </p>
        <p>
            <label for="phone">Téléphone / Phone</label>
            <input type="tel" name="phone" id="phone" minlength="10" value="{{old('phone')}}" required>
            @error('phone')
            <span class="error">{{$message}}</span>
            @enderror
        </p>
        <p>
            <label for="email">Courriel / Email</label>
            <input type="email" name="email" id="email" value="{{old('email')}}">
            @error('email')
            <span class="error">{{$message}}</span>
            @enderror
        </p>
        <p>
            <label>Langue préféré / Preferred Language</label>
            <label for="lang-fr"><input type="radio" value="fr" name="lang" {{ old('lang') == 'fr' ? ' checked' : '' }} id="lang-fr">Français</label>
            <label for="lang-en"><input type="radio" value="en" name="lang" {{ old('lang') == 'en' ? ' checked' : '' }} id="lang-en">English</label>
        </p>

        <p>
            <label for="note">Note</label>
            <textarea name="note" id="note">{{old('note')}}</textarea>
            @error('note')
            <span class="error">{{$message}}</span>
            @enderror
        </p>

        {!! NoCaptcha::displaySubmit('waitlist-form', 'Soumettre / Submit') !!}

    </fieldset>
</form>
{!! NoCaptcha::renderJs('fr-CA') !!}
</body>
</html>
