<div>
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#001a61]">Configuration API</h1>
            <p class="text-[#444652] mt-2">
                Clés stockées en base (chiffrées) avec priorité sur le <code class="text-xs bg-[#e7eeff] px-1 rounded">.env</code>.
                {{ $configuredCount }} / {{ count($board) }} API(s) prêtes.
            </p>
        </div>
        <a href="{{ route('admin.settings') }}" class="text-sm font-bold text-[#001a61] underline" wire:navigate.hover>← Paramètres</a>
    </div>

    <div class="grid lg:grid-cols-12 gap-6">
        {{-- Liste --}}
        <aside class="lg:col-span-4 space-y-2">
            @foreach ($board as $item)
                <button type="button" wire:click="selectProvider('{{ $item['provider'] }}')"
                    @class([
                        'w-full text-left adf-card-static p-4 transition border-2',
                        'border-[#001a61] bg-[#e7eeff]/50' => $activeProvider === $item['provider'],
                        'border-transparent hover:border-[#c5c5d4]' => $activeProvider !== $item['provider'],
                    ])>
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <p class="font-extrabold text-[#001a61]">{{ $item['label'] }}</p>
                            <p class="text-[11px] uppercase font-bold text-[#757683] mt-0.5">{{ $item['category'] }}</p>
                        </div>
                        <span @class([
                            'text-[10px] font-bold uppercase px-2 py-0.5 rounded-lg',
                            'bg-green-100 text-green-800' => $item['configured'],
                            'bg-amber-100 text-amber-900' => ! $item['configured'],
                        ])>{{ $item['configured'] ? 'OK' : 'À configurer' }}</span>
                    </div>
                    <p class="text-xs text-[#757683] mt-2 line-clamp-2">{{ $item['description'] }}</p>
                    <div class="mt-2 flex flex-wrap gap-1">
                        @if ($item['is_enabled'])
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-[#001a61] text-white">activé</span>
                        @endif
                        @if ($item['has_sandbox'])
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-[#e7eeff] text-[#0a2e8c]">
                                {{ $item['sandbox'] ? 'sandbox' : 'live' }}
                            </span>
                        @endif
                    </div>
                </button>
            @endforeach
        </aside>

        {{-- Formulaire --}}
        <section class="lg:col-span-8 adf-card-static p-6">
            @if ($active)
                <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-2xl font-extrabold text-[#001a61]">{{ $active['label'] }}</h2>
                        <p class="text-sm text-[#444652] mt-1">{{ $active['description'] }}</p>
                        <a href="{{ $active['docs'] }}" target="_blank" rel="noopener"
                            class="inline-flex items-center gap-1 mt-2 text-sm font-bold text-[#0a2e8c] hover:underline">
                            Documentation <span class="material-symbols-outlined text-base">open_in_new</span>
                        </a>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="space-y-5">
                    <div class="flex flex-wrap gap-6">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="isEnabled" class="rounded border-[#c5c5d4] text-[#001a61] focus:ring-[#001a61]">
                            <span class="text-sm font-bold text-[#001a61]">Activer cette intégration</span>
                        </label>
                        @if ($active['has_sandbox'])
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="sandbox" class="rounded border-[#c5c5d4] text-[#001a61] focus:ring-[#001a61]">
                                <span class="text-sm font-bold text-[#001a61]">Mode sandbox / test</span>
                            </label>
                        @endif
                    </div>

                    @foreach ($active['field_defs'] as $key => $field)
                        @php $status = $active['fields'][$key] ?? null; @endphp
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-1">
                                <label class="text-xs font-bold uppercase text-[#757683]">
                                    {{ $field['label'] }}
                                    @if ($field['required'] ?? false) <span class="text-red-500">*</span> @endif
                                </label>
                                <span class="text-[10px] font-bold uppercase tracking-wide
                                    @if(($status['source'] ?? '') === 'db') text-green-700
                                    @elseif(($status['source'] ?? '') === 'env') text-[#0a2e8c]
                                    @else text-[#757683] @endif">
                                    @if (($status['source'] ?? '') === 'db') source: base de données
                                    @elseif (($status['source'] ?? '') === 'env') source: .env ({{ $field['env'] }})
                                    @else non renseigné @endif
                                </span>
                            </div>
                            <input
                                type="{{ ($field['secret'] ?? false) ? 'password' : (($field['type'] ?? 'text') === 'number' ? 'number' : 'text') }}"
                                wire:model="form.{{ $key }}"
                                placeholder="{{ ($field['secret'] ?? false) && ($status['filled'] ?? false) ? '•••• conserver la valeur actuelle (laisser vide)' : ($field['placeholder'] ?? $field['env']) }}"
                                autocomplete="off"
                                class="w-full rounded-xl border border-[#c5c5d4] bg-white px-4 py-2.5 text-sm text-[#001a61] focus:ring-2 focus:ring-[#001a61]/20"
                            >
                            @if (($field['secret'] ?? false) && ($status['filled'] ?? false))
                                <p class="text-xs text-[#757683] mt-1">Valeur actuelle : <span class="font-mono">{{ $status['preview'] }}</span> — laissez vide pour conserver.</p>
                            @elseif (!($field['secret'] ?? false) && ($status['source'] ?? '') === 'env' && blank($form[$key] ?? null))
                                <p class="text-xs text-[#757683] mt-1">Fallback .env : <span class="font-mono">{{ $status['preview'] }}</span></p>
                            @endif
                            <p class="text-[11px] text-[#757683] mt-1">Variable d’environnement : <code class="bg-[#f0f3ff] px-1 rounded">{{ $field['env'] }}</code></p>
                        </div>
                    @endforeach

                    <div class="pt-4 flex flex-wrap gap-3 border-t border-[#e7eeff]">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#001a61] text-white font-bold hover:bg-[#0a2e8c]"
                            wire:loading.attr="disabled">
                            <span class="material-symbols-outlined text-base">save</span>
                            Enregistrer
                        </button>
                        <button type="button" wire:click="clearDbCredentials" wire:confirm="Effacer les clés stockées en base pour {{ $active['label'] }} ?"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-[#c5c5d4] text-[#001a61] font-bold hover:bg-[#f0f3ff]">
                            Réinitialiser DB
                        </button>
                    </div>
                </form>

                <div class="mt-8 rounded-xl bg-[#f0f3ff] border border-[#c5c5d4] p-4 text-sm text-[#444652]">
                    <p class="font-bold text-[#001a61] mb-1">Priorité de lecture backend</p>
                    <ol class="list-decimal list-inside space-y-1">
                        <li>Valeur enregistrée ici (base chiffrée)</li>
                        <li>Sinon variable <code class="text-xs">.env</code> / <code class="text-xs">config/services.php</code></li>
                    </ol>
                    <p class="mt-2 text-xs text-[#757683]">Le backend (paiements, sync BRVM/Mansa, etc.) utilise toujours cette résolution — pas besoin de redémarrer si vous sauvegardez ici.</p>
                </div>
            @endif
        </section>
    </div>
</div>
