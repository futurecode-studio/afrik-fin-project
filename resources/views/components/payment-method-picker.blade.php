@props([
    'selected' => 'mtn',
])

@php
    $methods = app(\App\Services\PaymentService::class)->customerPaymentMethods();
    $ready = app(\App\Services\PaymentService::class)->paymentsAvailable();
@endphp

<div {{ $attributes->merge(['class' => 'space-y-3']) }}>
    <p class="text-sm font-bold text-[#001a61]">Moyen de paiement</p>

    @if (! $ready)
        <p class="text-sm text-amber-900 bg-amber-50 border border-amber-200 rounded-xl p-3">
            Paiement temporairement indisponible. Réessayez plus tard ou contactez le support.
        </p>
    @else
        <div class="grid grid-cols-2 gap-3">
            @foreach ($methods as $key => $method)
                <label class="cursor-pointer block">
                    <input type="radio" wire:model.live="paymentMethod" value="{{ $key }}" class="sr-only peer">
                    <div class="rounded-xl border-2 p-3 text-center transition peer-checked:border-[#001a61] peer-checked:bg-[#e7eeff] border-[#c5c5d4] hover:border-[#001a61]/40 h-full">
                        <span class="inline-flex min-w-10 h-10 px-2 rounded-full bg-[#001a61]/10 text-[#001a61] font-extrabold text-xs items-center justify-center">{{ $method['icon'] }}</span>
                        <p class="mt-2 text-sm font-bold text-[#001a61]">{{ $method['label'] }}</p>
                        <p class="text-[11px] text-[#757683]">{{ $method['hint'] }}</p>
                    </div>
                </label>
            @endforeach
        </div>
        <p class="text-xs text-[#757683]">Paiement sécurisé. Vous serez guidé pour finaliser avec l’opérateur choisi.</p>
    @endif
</div>
