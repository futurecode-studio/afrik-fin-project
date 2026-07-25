<div class="bg-[#f9f9ff] min-h-[70vh] flex items-center">
    <section class="max-w-[720px] mx-auto px-5 py-16 w-full">
        <div class="bg-white border border-[#c5c5d4] rounded-2xl p-8 lg:p-10 text-center shadow-sm">
            <div class="mx-auto w-16 h-16 rounded-full bg-green-100 text-green-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-4xl" style="font-variation-settings:'FILL' 1">check_circle</span>
            </div>
            <h1 class="text-3xl font-extrabold text-[#001a61] mt-5">Paiement confirmé</h1>
            <p class="text-[#444652] mt-2">Merci. Votre transaction a bien été enregistrée.</p>

            @if ($payment)
                <dl class="mt-8 text-left grid sm:grid-cols-2 gap-4 text-sm">
                    <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                        <dt class="text-[#757683] text-xs uppercase tracking-wider">Numéro de commande</dt>
                        <dd class="font-bold text-[#001a61] mt-1 font-mono">{{ $payment->reference }}</dd>
                    </div>
                    <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                        <dt class="text-[#757683] text-xs uppercase tracking-wider">Date</dt>
                        <dd class="font-bold text-[#001a61] mt-1">{{ optional($payment->paid_at ?? $payment->updated_at)->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                        <dt class="text-[#757683] text-xs uppercase tracking-wider">Mode de paiement</dt>
                        <dd class="font-bold text-[#001a61] mt-1">{{ $payment->provider ?: ($payment->payment_method ?: '—') }}</dd>
                    </div>
                    <div class="p-4 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4]">
                        <dt class="text-[#757683] text-xs uppercase tracking-wider">Montant total</dt>
                        <dd class="font-bold text-[#001a61] mt-1">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency ?: 'FCFA' }}</dd>
                    </div>
                </dl>
                @if ($payment->formation)
                    <div class="mt-6 text-left p-5 rounded-xl border border-[#c5c5d4] bg-[#001a61] text-white">
                        <p class="text-sm text-white/70">Formation</p>
                        <p class="font-extrabold text-lg mt-1">{{ $payment->formation->titre }}</p>
                        <ul class="mt-4 space-y-2 text-sm text-white/85">
                            <li class="flex gap-2"><span class="material-symbols-outlined text-[#ffbf00] text-base">check_circle</span> Accès à vos modules</li>
                            <li class="flex gap-2"><span class="material-symbols-outlined text-[#ffbf00] text-base">workspace_premium</span> Certificat à la réussite</li>
                            <li class="flex gap-2"><span class="material-symbols-outlined text-[#ffbf00] text-base">support_agent</span> Support prioritaire</li>
                        </ul>
                    </div>
                @endif
            @else
                <p class="mt-6 text-sm text-[#757683]">Aucune référence de paiement fournie. Si vous venez de payer, votre accès sera activé sous peu.</p>
            @endif

            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    <a href="{{ route('client.formations') }}" class="inline-flex justify-center px-5 py-3 rounded-xl bg-[#001a61] text-white font-bold">Mes formations</a>
                @endauth
                <a href="{{ route('formations') }}" class="inline-flex justify-center px-5 py-3 rounded-xl border border-[#001a61] text-[#001a61] font-bold">Catalogue</a>
                <a href="{{ route('home') }}" class="inline-flex justify-center items-center gap-1 px-5 py-3 text-[#444652] font-medium">
                    <span class="material-symbols-outlined text-base">arrow_back</span> Accueil
                </a>
            </div>
        </div>
    </section>
</div>
