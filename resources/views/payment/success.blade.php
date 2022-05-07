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
        <p><a href="{{route('payment.start')}}">Effectuer un autre paiement.</a> / <a href="{{route('payment.start')}}" lang="en-CA">Make another payment.</a></p>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

