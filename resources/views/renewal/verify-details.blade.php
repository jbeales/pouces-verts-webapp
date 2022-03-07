<x-layout>
    <x-slot name="title">
        Paiement: Détails - Payment: Details
    </x-slot>
    <x-slot name="page_title">
        Vérification des détails
    </x-slot>
    <x-slot name="page_title_en">
        Verification of details
    </x-slot>
    <section>
        <form action="{{ route('renewal.handler') }}" method="POST" id="renewal-form">
            @csrf


            <fieldset>
                <h3>Étape 2: Vérifier que vous payez le bon montant.<span lang="en-CA">Step 2: Verify you are paying the right price.</span></h3>

                <p>
                    <label for="small_gardens">Nombre de petits jardins:</label>
                    <span class="calculator">
                        <input type="number" min="0" max="4" step="1" name="small_gardens" id="small_gardens" value="{{ old('small_gardens', $small_gardens_count) }}">
                        <em class="calculated"></em>
                    </span>
                    @error('small_gardens')
                    <span class="error">{{$message}}</span>
                    @enderror
                    <span class="info">Vos petits jardins sont: {{ $small_gardens }}</span>
                    <span class="info">Si vous avez 3 petits jardins, ils sont facturé comme 1 grand jardin.</span>
                </p>

                <p>
                    <label for="large_gardens">Nombre de grands jardins:</label>
                    <span class="calculator">
                        <input type="number" min="0" max="2" step="1" name="large_gardens" id="large_gardens" value="{{ old('large_gardens', $large_gardens_count) }}">
                        <em class="calculated"></em>
                    </span>
                    @error('large_gardens')
                    <span class="error">{{$message}}</span>
                    @enderror

                    <span class="info">Vos grands jardins sont: {{ $large_gardens }}</span>
                </p>

                @if($has_biblio_loisir)
                    <p>
                        <span class="calculator biblio-loisir">
                            <em>2$ Rabais pour la carte biblio-loisir.</em>
                            <em class="calculated">-2 $</em>
                        </span>
                    </p>
                @endif

                <p class="calculator total">
                    <strong>Totale à payer / Amount to Pay:</strong>
                    <strong class="calculated"></strong>
                </p>

                <button name="submit" id="submit">Prochain / Next</button>

                <script>
                    function recalc() {
                        let total = 0,
                            smallTotal = 0,
                            largeTotal = 0,
                            smallElm = document.querySelector('#small_gardens'),
                            largeElm = document.querySelector('#large_gardens');

                        if(smallElm.value >= 3) {
                            largeElm.value = parseInt(largeElm.value, 10) + 1;
                            smallElm.value = parseInt(smallElm.value, 10) - 3;
                        }

                        smallTotal = smallElm.value * 14;
                        smallElm.parentNode.querySelector('.calculated').innerText = smallTotal + ' $';

                        largeTotal = largeElm.value * 30;
                        largeElm.parentNode.querySelector('.calculated').innerText = largeTotal + ' $';

                        total = smallTotal + largeTotal;
                        if(document.querySelector('.calculator.biblio-loisir')) {
                            total -= 2;
                        }

                        if(total < 0) {
                            total = 0;
                        }

                        document.querySelector('.calculator.total .calculated').innerText = total + ' $';

                    }
                    window.addEventListener('DOMContentLoaded', function(e) {
                        document.querySelector('#renewal-form').addEventListener('change', recalc);
                        document.querySelector('#renewal-form').addEventListener('blur', recalc);
                        recalc();
                    });
                </script>
            </fieldset>
        </form>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

