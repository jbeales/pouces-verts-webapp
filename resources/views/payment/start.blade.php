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
        <form action="{{ route('payment.handler') }}" method="POST" id="renewal-form">
            <style>
                form input[type=number] {
                    width:10em;
                }
            </style>
            @csrf
            <h2>Faire une paiement<span lang="en-CA">Make a payment</span></h2>

            <p>Vous pouvez envoyer de l'argent aux Pouces verts ici.</p>
            <p lang="en-CA">You can send money to Pouces Verts here..</p>

            <fieldset>
                <h3>Quelle montant voulez-vous envoyer?<span lang="en-CA">How much do you want to send?</span></h3>
                <p>
                    <label for="amount">Montant / <span lang="en-CA">Amount</span></label>
                    <input name="amount" id="amount" value="{{old('amount')}}" type="number" placeholder="1.0" step="0.01" min="0" max="10" list="quick-prices">$
                    @error('amount')
                    <span class="error">{{$message}}</span>
                    @enderror
                    <datalist id="quick-prices">
                        <option value="5">
                        <option value="15">
                        <option value="30">
                        <option value="45">
                        <option value="60">
                    </datalist>
                </p>

                <p>
                    <label for="email">Votre Courriel / <span lang="en-CA">Your Email</span></label>
                    <input type="email" name="email" id="email" value="{{old('email')}}">
                    @error('email')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>

                <p>
                    <label for="payment-name">Ce paiement est pour:  / <span lang="en-CA">This payment is for:</span></label>
                    <select type="text" name="payment-name" id="payment-name">
                        <option value="">Selectionnez / Choose</option>
                        @foreach( [
	                        'Atelier / Workshop',
	                        'Clé / Key',
	                        'Don / Donation',
	                        'Frais du jardinage / Gardening Fees',
	                        'Fumier / Manure',
	                        'Autre / Other'
	                    ] as $item )
                            @php
                                $selector = '';
								if( old('payment-name') === $item) {
									$selector = ' selected';
								}

                            @endphp
                        <option value="{{$item}}"{!! $selector !!}>{{$item}}</option>
                       @endforeach
                    </select>
                    @error('payment-name')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>

                <p>
                    <label for="description">Déscription ou commentaire:  / <span lang="en-CA">Description or comment:</span></label>
                    <input type="text" name="description" id="description" value="{{old('description')}}">
                    @error('description')
                    <span class="error">{{$message}}</span>
                    @enderror
                </p>

                {!! NoCaptcha::displaySubmit('renewal-form', 'Continuez / Continue') !!}

            </fieldset>
        </form>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

