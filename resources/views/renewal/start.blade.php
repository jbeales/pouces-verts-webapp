<x-layout>
    <x-slot name="title">
        Paiement - Payment
    </x-slot>
    <x-slot name="page_title">
        Paiement
    </x-slot>
    <x-slot name="page_title_en">
        Payment
    </x-slot>
    <section>
        <form action="{{ route('renewal.handler') }}" method="POST" id="renewal-form">
            @csrf
            <h2>Payez votre adhésion<span lang="en-CA">Pay for your membership</span></h2>

            <p>Vous pouvez seulement payez votre adhésion avec ce formulaire une fois que vous avez soumis votre formulaire de renouvellement en ligne.</p>
            <p>You must submit your renewal form online before you can pay using this form.</p>

            @if (session('status') && session('status') === 'not found')
                <div class="alert alert-warning" role="alert">
                    <p>Votre inscription n'a pas été trouvé. Avez-vous déjà soumis votre formulaire d'inscription?</p>
                    <p lang="en-CA">Your registration was not found. Have you submitted your renewal form yet?</p>
                </div>
            @endif

            @if (session('status') && session('status') === 'paid')
                <div class="alert alert-warning" role="alert">
                    <p>Vous avez déjà payé! Si vous voulez vraiment nous donner plus d'argent, communiquer avec une membre du CA.</p>
                    <p lang="en-CA">It looks like you have already paid! If you really want to give us more money, talk to a member of the board.</p>
                </div>
            @endif

            <fieldset>
                <h3>Étape 1: Entrez votre adresse courriel pour trouver votre inscription<span lang="en-CA">Step 1: Enter your email address to find your registration</span></h3>
                <p>
                    <label for="email">Courriel / <span lang="en-CA">Email</span></label>
                    <input type="email" name="email" id="email" value="{{old('email')}}">
                    @error('email')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>

                {!! NoCaptcha::displaySubmit('renewal-form', 'Commencer / Start') !!}

            </fieldset>
        </form>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

