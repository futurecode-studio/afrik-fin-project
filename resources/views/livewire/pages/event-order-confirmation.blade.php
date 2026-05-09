<main class="flex-1 pt-20">
    <section class="py-12">
        <div class="container mx-auto px-4 max-w-xl">
            <div class="rounded-2xl border border-border bg-card shadow-elegant overflow-hidden">
                <div class="bg-primary text-primary-foreground p-8 text-center">
                    @if($order->status === 'paid')
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        <h1 class="text-2xl font-bold">Commande confirmée !</h1>
                        <p class="text-sm opacity-90 mt-1">Votre paiement a été reçu avec succès.</p>
                    @elseif($order->status === 'pending')
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <h1 class="text-2xl font-bold">Commande en attente</h1>
                        <p class="text-sm opacity-90 mt-1">Finalisez votre paiement pour confirmer la commande.</p>
                    @else
                        <h1 class="text-2xl font-bold">Détails de la commande</h1>
                    @endif
                </div>

                <div class="p-6 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-border">
                        <div>
                            <p class="text-xs text-muted-foreground uppercase tracking-wider">Commande</p>
                            <p class="font-bold text-lg">{{ $order->order_number }}</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            @if($order->status === 'paid') bg-emerald-100 text-emerald-800
                            @elseif($order->status === 'pending') bg-amber-100 text-amber-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            @if($order->status === 'paid') Payée
                            @elseif($order->status === 'pending') En attente
                            @else {{ $order->status }}
                            @endif
                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-muted-foreground uppercase tracking-wider mb-2">Articles</p>
                        <div class="space-y-3">
                            @foreach($order->items as $item)
                            <div class="flex items-start justify-between p-3 rounded-lg bg-muted/50 border border-border">
                                <div class="flex items-center gap-3">
                                    @if($item->product?->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="" class="w-12 h-12 rounded-lg object-cover border border-border">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-muted flex items-center justify-center text-xs font-bold text-muted-foreground">{{ substr($item->product_name, 0, 1) }}</div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-sm">{{ $item->product_name }}</p>
                                        <p class="text-xs text-muted-foreground">{{ number_format($item->unit_price, 0, ',', ' ') }} FCFA x {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <p class="font-bold text-sm">{{ number_format($item->total_price, 0, ',', ' ') }} FCFA</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-border">
                        <p class="font-bold text-lg">Total</p>
                        <p class="font-bold text-lg text-primary">{{ number_format($order->total, 0, ',', ' ') }} FCFA</p>
                    </div>

                    <div class="rounded-lg bg-muted/50 p-4 border border-border">
                        <p class="text-xs text-muted-foreground uppercase tracking-wider mb-2">Retrait sur place</p>
                        <p class="text-sm text-foreground">
                            <strong>{{ $order->event->title }}</strong><br>
                            {{ $order->event->location_name ?? $order->event->city ?? '' }} — {{ $order->event->starts_at?->format('d/m/Y') }}
                        </p>
                        <p class="text-xs text-muted-foreground mt-2">
                            Présentez ce reçu (PDF ou QR code) le jour de l'événement pour retirer vos articles.
                        </p>
                    </div>

                    @if($order->status === 'paid')
                    <div class="text-center">
                        <button wire:click="downloadReceipt" class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-primary-foreground rounded-xl font-semibold hover:bg-primary-light transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            Télécharger le reçu PDF
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>
