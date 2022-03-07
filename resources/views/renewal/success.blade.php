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
        <p>Votre paiement a été complété. Bon jardinage en {{ date('Y') }}!</p>
        <p lang="en-CA">Your payment is complete! Happy gardening in {{ date('Y') }}!</p>
    </section>
    {!! NoCaptcha::renderJs('fr-CA') !!}
</x-layout>

