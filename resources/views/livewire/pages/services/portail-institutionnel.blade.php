<div class="bg-[#f9f9ff] min-h-screen">
    <section class="max-w-[1100px] mx-auto px-5 lg:px-8 py-14">
        <div class="grid lg:grid-cols-2 gap-10 items-start">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-[#0a2e8c]">Corporate</p>
                <h1 class="text-4xl font-extrabold text-[#001a61] mt-2">Portail Institutionnel & Corporate</h1>
                <p class="text-[#444652] mt-4">Pour banques, assurances, family offices et entreprises : accès privilégié aux marchés UEMOA, reporting et formations sur mesure.</p>
                <ul class="mt-6 space-y-3 text-sm">
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">apartment</span> Solutions institutionnelles</li>
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">school</span> Formations intra-entreprise</li>
                    <li class="flex gap-2"><span class="material-symbols-outlined text-[#001a61]">query_stats</span> Analyses & données BRVM</li>
                </ul>
            </div>
            <div class="bg-white border border-[#c5c5d4] rounded-xl p-6">
                <form wire:submit.prevent="submit" class="space-y-4">
                    <div><label class="text-sm font-medium">Organisation</label><input wire:model="organization" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('organization')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium">Fonction</label><input wire:model="role" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></div>
                    <div><label class="text-sm font-medium">Nom du contact</label><input wire:model="name" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium">Email</label><input type="email" wire:model="email" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium">Téléphone</label><input wire:model="phone" class="w-full mt-1 rounded-lg border-[#c5c5d4]">@error('phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="text-sm font-medium">Besoin</label><textarea wire:model="message" rows="4" class="w-full mt-1 rounded-lg border-[#c5c5d4]"></textarea></div>
                    <button class="w-full py-3 rounded-xl bg-[#001a61] text-white font-bold">Contacter l’équipe corporate</button>
                </form>
            </div>
        </div>
    </section>
</div>
