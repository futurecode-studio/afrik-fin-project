<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[900px] mx-auto px-5 lg:px-8 py-14">
        <x-client.coming-soon
            title="Bientôt disponible"
            feature="Carnet d’ordres via SGI agréée"
            icon="receipt_long"
            description="L’envoi d’intentions d’ordres vers une Société de Gestion et d’Intermédiation (SGI) sera activé dès validation des partenaires. Consultez la liste des SGI ou demandez une orientation."
        >
            <div class="space-y-4">
                <h1 class="text-3xl font-extrabold text-[#001a61]">Carnet d’Ordres Direct</h1>
                <div class="h-40 rounded-xl bg-white border border-[#c5c5d4]"></div>
                <div class="h-24 rounded-xl bg-white border border-[#c5c5d4]"></div>
            </div>
        </x-client.coming-soon>
    </section>
</div>
