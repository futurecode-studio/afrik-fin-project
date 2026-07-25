<div class="max-w-xl mx-auto px-4 py-16">
    <p class="text-xs font-bold uppercase tracking-widest text-[#757683]">Authenticité</p>
    <h1 class="text-3xl font-extrabold text-[#001a61] mt-2">Vérifier un certificat</h1>
    <p class="text-[#444652] mt-2">Saisissez le numéro figurant sur le document officiel.</p>

    <form wire:submit.prevent="check" class="mt-8 space-y-4">
        <input type="text" wire:model="number" placeholder="CERT-2026-…"
            class="w-full rounded-xl border border-[#c5c5d4] px-4 py-3 text-[#001a61] font-semibold">
        @error('number') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        <button type="submit" class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold">Vérifier</button>
    </form>

    @if ($result)
        <div @class([
            'mt-8 rounded-2xl p-6 border',
            'bg-green-50 border-green-200' => $result['valid'] ?? false,
            'bg-amber-50 border-amber-200' => !($result['valid'] ?? false),
        ])>
            <p class="font-extrabold text-[#001a61]">{{ $result['message'] ?? '' }}</p>
            @if (! empty($result['data']))
                <ul class="mt-4 text-sm text-[#444652] space-y-1">
                    <li><span class="font-bold">Titulaire :</span> {{ $result['data']['holder'] }}</li>
                    <li><span class="font-bold">Formation :</span> {{ $result['data']['formation'] }}</li>
                    <li><span class="font-bold">Délivré le :</span> {{ $result['data']['issued_at'] }}</li>
                    <li><span class="font-bold">Terminé le :</span> {{ $result['data']['completed_at'] }}</li>
                </ul>
            @endif
        </div>
    @endif
</div>
