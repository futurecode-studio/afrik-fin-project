<div x-data="qrScanner()" x-init="init()" @keydown.escape.window="stopScan()" class="p-6 lg:p-8 space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[#757683] mb-2 text-xs font-semibold tracking-wider uppercase flex-wrap">
                <a href="{{ route('admin.events') }}" class="hover:text-[#001a61]" wire:navigate.hover>Événements</a>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="text-[#001a61]">{{ Str::limit($event->title, 40) }}</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-[#001a61]">Émargement QR</h1>
            <p class="text-sm text-[#444652] mt-1">Scannez le billet des participants ou validez manuellement.</p>
        </div>
        <div class="admin-card px-5 py-3 text-center">
            <p class="text-xs uppercase text-[#757683]">Présents</p>
            <p class="text-3xl font-extrabold text-[#001a61]">{{ $totalCheckedIn }}</p>
        </div>
    </div>

    @include('livewire.admin.partials.event-admin-nav', ['event' => $event])

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 admin-card p-5 space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="font-bold text-[#001a61]">Scanner un QR Code</h2>
                <button type="button" @click="scanning ? stopScan() : startScan()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#001a61] text-white text-sm font-bold hover:bg-[#0a2e8c]">
                    <span class="material-symbols-outlined text-[18px]" x-text="scanning ? 'stop_circle' : 'qr_code_scanner'"></span>
                    <span x-text="scanning ? 'Arrêter' : 'Démarrer la caméra'"></span>
                </button>
            </div>

            @if ($scanResult)
                <div @class([
                    'rounded-xl p-4 border text-sm',
                    'bg-emerald-50 text-emerald-800 border-emerald-200' => $scanResult['type'] === 'success',
                    'bg-amber-50 text-amber-800 border-amber-200' => $scanResult['type'] === 'warning',
                    'bg-red-50 text-red-800 border-red-200' => $scanResult['type'] === 'error',
                ])>
                    <p class="font-semibold">{{ $scanResult['message'] }}</p>
                    @if (isset($scanResult['participant']))
                        <p class="text-xs mt-1">
                            {{ $scanResult['participant'] }}
                            @isset($scanResult['institution']) · {{ $scanResult['institution'] }} @endisset
                            @isset($scanResult['ticket']) · {{ $scanResult['ticket'] }} @endisset
                        </p>
                    @endif
                </div>
            @endif

            <div x-show="scanning" x-cloak class="rounded-xl overflow-hidden border border-[#c5c5d4] bg-black/90">
                <div id="qr-reader" class="w-full max-w-lg mx-auto"></div>
            </div>

            <div x-show="!scanning" class="rounded-xl border border-dashed border-[#c5c5d4] p-8 text-center">
                <span class="material-symbols-outlined text-4xl text-[#001a61]">qr_code_scanner</span>
                <p class="text-sm text-[#444652] mt-2">Démarrez la caméra pour scanner les billets, ou saisissez le code manuellement.</p>
            </div>

            <div class="pt-2 border-t border-[#e7eeff]">
                <label class="text-xs font-bold uppercase text-[#757683]">Saisie manuelle du QR</label>
                <div class="mt-2 flex gap-2">
                    <input type="text" x-ref="manualQr" placeholder="Coller le code QR…"
                        class="admin-input flex-1 text-sm font-mono"
                        @keydown.enter.prevent="submitManual()">
                    <button type="button" @click="submitManual()"
                        class="px-4 py-2 rounded-xl border border-[#c5c5d4] text-sm font-bold text-[#001a61] hover:bg-[#e7eeff]">
                        Valider
                    </button>
                </div>
            </div>
        </div>

        <div class="admin-card overflow-hidden">
            <div class="px-5 py-4 border-b border-[#e7eeff]">
                <h3 class="font-bold text-[#001a61]">Derniers enregistrements</h3>
            </div>
            <div class="divide-y divide-[#e7eeff] max-h-[28rem] overflow-y-auto">
                @forelse ($recent as $r)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($r->first_name ?? '?', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-[#001a61] truncate">{{ $r->fullName() }}</p>
                            <p class="text-xs text-[#757683]">{{ $r->checked_in_at?->format('d/m H:i') ?? $r->checkIn?->checked_in_at?->format('d/m H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-[#757683]">Aucun émargement encore.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="admin-card overflow-hidden">
        <div class="px-5 py-4 border-b border-[#e7eeff]">
            <h2 class="font-bold text-[#001a61]">Émargement manuel par liste</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left px-4 py-3">Nom</th>
                        <th class="text-left px-4 py-3">Email</th>
                        <th class="text-left px-4 py-3">Statut</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $pendingRegs = \App\Models\EventRegistration::where('event_id', $event->id)
                            ->whereNotIn('status', ['cancelled', 'no_show', 'checked_in'])
                            ->orderBy('last_name')
                            ->limit(100)
                            ->get();
                    @endphp
                    @forelse ($pendingRegs as $reg)
                        <tr class="border-t border-[#e7eeff]">
                            <td class="px-4 py-3 font-semibold text-[#001a61]">{{ $reg->fullName() }}</td>
                            <td class="px-4 py-3 text-[#757683]">{{ $reg->email }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs {{ $reg->statusColorClasses() }}">{{ $reg->statusLabel() }}</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" wire:click="manualCheckIn({{ $reg->id }})"
                                    class="text-xs font-bold px-3 py-1.5 rounded-lg bg-[#001a61] text-white hover:bg-[#0a2e8c]">
                                    Valider présence
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-[#757683]">Tous les participants confirmés sont enregistrés.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
function qrScanner() {
    return {
        scanning: false,
        scanner: null,
        lastCode: null,
        lastAt: 0,
        init() {
            window.addEventListener('livewire:navigated', () => this.stopScan());
        },
        async startScan() {
            if (typeof Html5Qrcode === 'undefined') {
                alert('Bibliothèque QR indisponible. Utilisez la saisie manuelle.');
                return;
            }
            this.scanning = true;
            await this.$nextTick();
            try {
                this.scanner = new Html5Qrcode('qr-reader');
                await this.scanner.start(
                    { facingMode: 'environment' },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decoded) => this.onDecoded(decoded),
                    () => {}
                );
            } catch (e) {
                console.error(e);
                this.scanning = false;
                alert('Impossible d’accéder à la caméra. Autorisez l’accès ou saisissez le code.');
            }
        },
        async stopScan() {
            this.scanning = false;
            if (this.scanner) {
                try { await this.scanner.stop(); } catch (_) {}
                try { this.scanner.clear(); } catch (_) {}
                this.scanner = null;
            }
        },
        onDecoded(code) {
            const now = Date.now();
            if (code === this.lastCode && now - this.lastAt < 2500) return;
            this.lastCode = code;
            this.lastAt = now;
            @this.call('handleQrDecoded', code);
        },
        submitManual() {
            const v = (this.$refs.manualQr?.value || '').trim();
            if (!v) return;
            @this.call('handleQrDecoded', v);
            this.$refs.manualQr.value = '';
        }
    }
}
</script>
@endpush
