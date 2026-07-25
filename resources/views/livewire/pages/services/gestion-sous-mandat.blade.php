<div class="bg-[#f9f9ff] min-h-screen">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-14">
        <div class="grid lg:grid-cols-2 gap-10 items-start">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Services</p>
                <h1 class="text-4xl font-extrabold text-[#001a61] mt-2">Gestion Sous Mandat</h1>
                <p class="text-[#444652] mt-4">Déléguez la gestion de votre portefeuille à une SGI / SGO agréée AMF-UMOA, avec un cadre contractuel clair.</p>
                <ul class="mt-6 space-y-3 text-sm">
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">verified</span> Mandat discrétionnaire ou assisté</li>
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">monitoring</span> Reporting périodique de performance</li>
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">handshake</span> Mise en relation avec partenaires agréés</li>
                </ul>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                <form wire:submit.prevent="submit" class="space-y-4">
                    <div><label class="text-sm font-medium">Nom</label><input wire:model="name" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium">Email</label><input type="email" wire:model="email" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium">Téléphone</label><input wire:model="phone" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium">Société (optionnel)</label><input wire:model="company" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></div>
                    <div><label class="text-sm font-medium">Objectifs / message</label><textarea wire:model="message" rows="4" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></textarea></div>
                    <button class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold">Demander un mandat</button>
                </form>
            </div>
        </div>
    </section>
</div>
