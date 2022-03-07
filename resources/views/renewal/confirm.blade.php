<x-layout>
    <x-slot name="title">
        Paiement: Confirmation - Payment: Confirmation
    </x-slot>
    <x-slot name="page_title">
        Confirmation des détails
    </x-slot>
    <x-slot name="page_title_en">
        Confirmation
    </x-slot>
    <section>
        <form action="{{ route('renewal.handler') }}" method="POST" id="renewal-form">
            @csrf

            <fieldset>
                <h3>Étape 3: Confirmation.<span lang="en-CA">Step 3: Confirmation.</span></h3>

                <p>Ici l'information qui sera envoyé au processeur pour le paiement.</p>
                <p lang="en-CA">Information that will be sent to the processor for payment.</p>

                <p>
                    <label>Petits jardins / <span lang="en-CA">Small Gardens</span></label>
                    <span class="info">Pour les jardins / <span class="en-CA">For gardens</span>{{ $small_gardens }}</span>
                    <span class="calculator"><span>{{$to_charge['small']['quantity']}} X 14</span> {{$to_charge['small']['subtotal']}}$</span>
                </p>

                <p>
                    <label>Grands jardins / <span lang="en-CA">Large Gardens</span></label>
                    <span class="info">Pour les jardins / <span class="en-CA">For gardens</span>{{ $large_gardens }}</span>
                    <span class="calculator"><span>{{$to_charge['large']['quantity']}} X 30</span> {{$to_charge['large']['subtotal']}}$</span>
                </p>

                @isset($to_charge['discount'])
                    <p>
                        <span class="calculator"><span>Rabais biblio-loisir / biblio-loisir discount:</span> - 2$ </span>
                    </p>
                @endisset

                <p class="calculator total">
                    <strong>Totale à payer / Amount to Pay:</strong>
                    <strong class="calculated">{{$to_charge['total']}} $</strong>
                </p>
                <input name="confirm" type="hidden" value="yes">
                <button name="submit" id="submit">Prochain Étape: Payez / Next Step: Pay!</button>
            </fieldset>
        </form>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

