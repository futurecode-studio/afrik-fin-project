<div style="padding: 20px; border: 1px solid #ccc; border-radius: 8px; margin: 20px 0;">
    <h3>Composant Livewire Test</h3>
    <p>Compteur: <strong>{{ $count }}</strong></p>
    
    <div style="margin-top: 15px;">
        <button wire:click="increment" style="background: #28a745; color: white; border: none; padding: 8px 16px; margin-right: 10px; border-radius: 4px; cursor: pointer;">
            +1
        </button>
        <button wire:click="decrement" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
            -1
        </button>
    </div>
    
    <p style="margin-top: 15px; color: #666; font-size: 14px;">
        ✅ Livewire fonctionne correctement ! Les boutons ci-dessus mettent à jour le compteur sans recharger la page.
    </p>
</div>
