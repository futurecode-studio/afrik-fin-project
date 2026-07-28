<div class="bg-[#f9f9ff] text-[#131c2a] min-h-screen">
    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pt-12 pb-6">
        <a href="{{ route('formations') }}" class="text-sm font-bold text-[#001a61] hover:underline">← Catalogue des formations</a>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#001a61] mt-4">Votre panier</h1>
        <p class="text-[#444652] mt-2">{{ $count }} formation(s) sélectionnée(s)</p>
    </section>

    <section class="max-w-[1280px] mx-auto px-5 lg:px-16 pb-20">
        @if ($lines->isEmpty())
            <div class="bg-white border border-dashed border-[#c5c5d4] rounded-xl p-12 text-center">
                <span class="material-symbols-outlined text-5xl text-[#c5c5d4]">shopping_cart</span>
                <h2 class="text-xl font-bold text-[#001a61] mt-4">Panier vide</h2>
                <p class="text-[#444652] mt-2">Ajoutez une formation depuis le catalogue.</p>
                <a href="{{ route('formations') }}" class="inline-block mt-6 bg-[#001a61] text-white font-bold px-6 py-3 rounded">Voir les formations</a>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($lines as $line)
                        <article class="bg-white border border-[#c5c5d4] rounded-xl p-5 flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-28 h-24 rounded-lg bg-[#e7eeff] overflow-hidden shrink-0">
                                @if ($line->formation->image_url)
                                    <img src="{{ $line->formation->image_url }}" alt="" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('formation-detail', $line->formation->slug) }}" class="font-bold text-[#001a61] hover:underline">
                                    {{ $line->formation->titre }}
                                </a>
                                <p class="text-sm text-[#757683] mt-1">
                                    {{ $line->formation->niveau }}
                                    @if ($line->formation->duree) · {{ $line->formation->duree }}@endif
                                </p>
                                <div class="mt-3">
                                    <button type="button" wire:click="remove({{ $line->formation->id }})" class="text-sm text-red-600 font-medium hover:underline">Retirer</button>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-extrabold text-[#001a61]">
                                    @if ($line->formation->isFree())
                                        Gratuit
                                    @else
                                        {{ number_format($line->line_total, 0, ',', ' ') }} FCFA
                                    @endif
                                </p>
                            </div>
                        </article>
                    @endforeach
                    <button type="button" wire:click="clear" class="text-sm font-bold text-[#757683] hover:text-red-600">Vider le panier</button>
                </div>

                <aside class="bg-white border border-[#c5c5d4] rounded-xl p-6 h-fit sticky top-32">
                    <h2 class="font-bold text-[#001a61] text-lg">Récapitulatif</h2>
                    <div class="mt-4 flex justify-between text-sm">
                        <span>Sous-total</span>
                        <span class="font-bold">{{ number_format($subtotal, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <p class="text-xs text-[#757683] mt-3">Le paiement et l’inscription se finalisent formation par formation (connexion requise).</p>
                    <div class="mt-6 space-y-2">
                        @foreach ($lines as $line)
                            <a href="{{ route('formation-detail', $line->formation->slug) }}"
                                class="block text-center bg-[#001a61] text-white font-bold px-4 py-3 rounded hover:bg-[#0a2e8c] transition text-sm">
                                Continuer — {{ Str::limit($line->formation->titre, 28) }}
                            </a>
                        @endforeach
                    </div>
                </aside>
            </div>
        @endif
    </section>
</div>
