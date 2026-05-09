<div x-data="{ scanning: false, lastResult: null }" @keydown.escape.window="scanning = false">
    <div class="container mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">Émargement</h1>
                <p class="text-sm text-muted-foreground">{{ $event->title }}</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-card border border-border p-3 text-center">
                    <p class="text-xs text-muted-foreground">Présents</p>
                    <p class="text-2xl font-bold text-primary">{{ $totalCheckedIn }}</p>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200">{{ session('message') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200">{{ session('error') }}</div>
        @endif

        <!-- Zone Scan -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2">
                <div class="rounded-lg border bg-card p-6 border-border">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-bold">Scanner un QR Code</h2>
                        <button @click="scanning = !scanning" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary-light transition-colors text-sm font-medium">
                            <svg x-show="!scanning" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            <span x-text="scanning ? 'Arrêter le scan' : 'Démarrer le scan'"></span>
                        </button>
                    </div>

                    <!-- Feedback scan -->
                    @if($scanResult)
                    <div class="mb-4 rounded-lg p-4 border text-sm
                        @match($scanResult['type'])
                            'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200'
                            'warning' => 'bg-amber-50 text-amber-800 border-amber-200'
                            'error' => 'bg-red-50 text-red-800 border-red-200'
                        @endmatch">
                        <div class="flex items-center gap-2">
                            @if($scanResult['type'] === 'success')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif($scanResult['type'] === 'warning')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            <div>
                                <p class="font-semibold">{{ $scanResult['message'] }}</p>
                                @if(isset($scanResult['participant']))
                                    <p class="text-xs mt-0.5">{{ $scanResult['participant'] }} @if(isset($scanResult['institution'])) • {{ $scanResult['institution'] }} @endif @if(isset($scanResult['ticket'])) • {{ $scanResult['ticket'] }} @endif</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Simulated QR input (real implementation would use ZXing/QR-scanner JS library) -->
                    <div x-show="scanning" class="border-2 border-dashed border-border rounded-lg p-8 text-center">
                        <p class="text-sm text-muted-foreground mb-3">Placez le QR code du participant devant la caméra.</p>
                        <p class="text-xs text-muted-foreground mb-4">(En production, intégrer une librairie JS de scan QR comme html5-qrcode)</p>
                        <div class="flex justify-center">
                            <input type="text" x-ref="qrInput" placeholder="Coller/entrer le QR code ici..." class="px-4 py-2 border border-border rounded-lg bg-background text-center font-mono text-sm w-full max-w-md" @keydown.enter="$wire.handleQrDecoded($refs.qrInput.value); $refs.qrInput.value = ''">
                        </div>
                    </div>
                    <div x-show="!scanning" class="text-center text-sm text-muted-foreground py-8">
                        Appuyez sur "Démarrer le scan" pour activer la caméra ou saisir manuellement un QR code.
                    </div>
                </div>
            </div>
            <div>
                <div class="rounded-lg border bg-card p-4 border-border">
                    <h3 class="font-bold text-sm mb-3">Derniers enregistrements</h3>
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($recent as $r)
                        <div class="flex items-center gap-3 p-2 rounded-lg bg-muted/50">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">{{ substr($r->first_name, 0, 1) }}</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $r->fullName() }}</p>
                                <p class="text-xs text-muted-foreground">{{ $r->checkIn?->checked_in_at?->format('H:i') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-muted-foreground text-center py-4">Aucun émargement encore.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste complète pour check-in manuel -->
        <div class="rounded-lg border bg-card p-6 border-border">
            <h2 class="font-bold mb-4">Émargement manuel par liste</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-muted">
                        <tr>
                            <th class="text-left p-3 font-semibold">Nom</th>
                            <th class="text-left p-3 font-semibold">Email</th>
                            <th class="text-left p-3 font-semibold">Billet</th>
                            <th class="text-left p-3 font-semibold">Statut</th>
                            <th class="text-right p-3 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $pendingRegs = \App\Models\EventRegistration::where('event_id', $event->id)
                            ->whereNotIn('status', ['cancelled','no_show','checked_in'])
                            ->with('ticketType')
                            ->limit(50)
                            ->get();
                        @endphp
                        @forelse($pendingRegs as $reg)
                        <tr class="border-t border-border hover:bg-muted/30">
                            <td class="p-3 font-medium">{{ $reg->fullName() }}</td>
                            <td class="p-3 text-muted-foreground">{{ $reg->email }}</td>
                            <td class="p-3">{{ $reg->ticketType?->name ?? '-' }}</td>
                            <td class="p-3">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs bg-blue-50 text-blue-700">
                                    @match($reg->status)
                                        'registered' => 'Enregistré'
                                        'confirmed' => 'Confirmé'
                                        'checked_in' => 'Présent'
                                        'cancelled' => 'Annulé'
                                        'no_show' => 'Absent'
                                        @default => $reg->status
                                    @endmatch
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <button wire:click="manualCheckIn({{ $reg->id }})" class="text-xs bg-primary text-primary-foreground px-3 py-1.5 rounded-lg hover:bg-primary-light transition-colors">Valider présence</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="p-4 text-center text-muted-foreground text-sm">Tous les participants confirmés sont enregistrés.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
