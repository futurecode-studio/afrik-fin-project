<div class="bg-[#f9f9ff] min-h-screen">
    @include('partials.markets-nav')
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-12">
        <h1 class="text-3xl font-extrabold text-[#001a61]">Suivi d’Introduction en Bourse</h1>
        <p class="text-[#444652] mt-2">Offres publiques et introductions suivies sur l’espace UEMOA / BRVM.</p>

        <div class="mt-6 flex flex-wrap gap-2">
            <button type="button" wire:click="$set('status','')" @class(['px-3 py-1.5 rounded-lg text-sm font-bold border', $status===''?'bg-[#001a61] text-white border-[#001a61]':'bg-white border-[#c5c5d4]'])>Toutes</button>
            @foreach (['annonce'=>'Annoncée','souscription'=>'Souscription','cloture'=>'Clôturée','cote'=>'Cotée'] as $k=>$l)
                <button type="button" wire:click="$set('status','{{ $k }}')" @class(['px-3 py-1.5 rounded-lg text-sm font-bold border', $status===$k?'bg-[#001a61] text-white border-[#001a61]':'bg-white border-[#c5c5d4]'])>{{ $l }}</button>
            @endforeach
        </div>

        <div class="mt-8 space-y-4">
            @forelse ($ipos as $ipo)
                <article class="bg-white border border-[#c5c5d4] rounded-xl p-6 flex flex-col lg:flex-row gap-6 justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-[#0a2e8c]">{{ $ipo->getStatusLabel() }} · {{ $ipo->sector ?: 'Secteur n/d' }}</p>
                        <h2 class="text-2xl font-extrabold text-[#001a61] mt-1">{{ $ipo->company_name }}</h2>
                        @if ($ipo->symbol)<p class="font-mono text-sm text-[#757683] mt-1">{{ $ipo->symbol }}</p>@endif
                        <p class="text-sm text-[#444652] mt-3 max-w-2xl">{{ $ipo->description }}</p>
                        <dl class="mt-4 grid sm:grid-cols-3 gap-3 text-sm">
                            @if ($ipo->offer_price_min || $ipo->offer_price_max)
                                <div class="p-3 rounded-lg bg-[#f0f3ff]"><dt class="text-xs text-[#757683]">Fourchette</dt><dd class="font-bold text-[#001a61]">{{ number_format($ipo->offer_price_min ?? 0,0,',',' ') }} – {{ number_format($ipo->offer_price_max ?? 0,0,',',' ') }} FCFA</dd></div>
                            @endif
                            @if ($ipo->subscription_start)
                                <div class="p-3 rounded-lg bg-[#f0f3ff]"><dt class="text-xs text-[#757683]">Souscription</dt><dd class="font-bold text-[#001a61]">{{ $ipo->subscription_start->format('d/m/Y') }} → {{ optional($ipo->subscription_end)->format('d/m/Y') }}</dd></div>
                            @endif
                            @if ($ipo->listing_date)
                                <div class="p-3 rounded-lg bg-[#f0f3ff]"><dt class="text-xs text-[#757683]">Cotation</dt><dd class="font-bold text-[#001a61]">{{ $ipo->listing_date->format('d/m/Y') }}</dd></div>
                            @endif
                        </dl>
                    </div>
                    <div class="shrink-0 flex flex-col gap-2">
                        <a href="{{ route('mise-en-relation') }}" class="inline-flex justify-center px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Participer à l’offre</a>
                        @if ($ipo->prospectus_url)
                            <a href="{{ $ipo->prospectus_url }}" target="_blank" class="inline-flex justify-center px-5 py-3 rounded-xl border border-[#001a61] text-[#001a61] font-bold">Prospectus</a>
                        @endif
                    </div>
                </article>
            @empty
                <div class="bg-white border border-dashed border-[#c5c5d4] rounded-xl p-10 text-center">
                    <p class="font-bold text-[#001a61]">Aucune introduction publiée</p>
                    <p class="text-sm text-[#444652] mt-2">Les IPO / OPO seront listées ici dès validation administrative.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
