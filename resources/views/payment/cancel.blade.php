<x-layout>
    <x-slot name="title">
        Paiement: Annulé - Payment: Cancelled
    </x-slot>
    <x-slot name="page_title">
        Annulé
    </x-slot>
    <x-slot name="page_title_en">
        Cancelled
    </x-slot>
    <section>

        <p>Il semble que votre paiement n'a pas réussi. Si vous voulez recommencer, vous pouvez <a href="{{route('payment.start')}}">le faire ici</a>.</p>
        <p>It looks like your payment didn't work. If you want to try again, <a href="{{route('payment.start')}}">start here</a>.</a></p>

    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

