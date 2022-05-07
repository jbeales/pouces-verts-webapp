<x-layout>
    <x-slot name="title">
        Paiement: Succès! - Payment: Success!
    </x-slot>
    <x-slot name="page_title">
        Succès!
    </x-slot>
    <x-slot name="page_title_en">
        Success!
    </x-slot>
    <section>
        <p>Votre paiement a été complété. Merci beaucoup!</p>
        <p lang="en-CA">Your payment is complete! Thank you very much!</p>

        @if(isset($stripe_session) && $stripe_session->payment_status === 'paid')
            <div class="paid">
                <p><span>Montant Payé / <span lang="en-CA">Amount Paid</span></span> <span class="amount-paid">{{ number_format($stripe_session->amount_total/100, 2) }} $</span></p>
                <p class="description">{{ $stripe_session->client_reference_id }}</p>
            </div>

        @endif
        <p><a href="{{route('payment.start')}}">Effectuer un autre paiement.</a> / <a href="{{route('payment.start')}}" lang="en-CA">Make another payment.</a></p>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

