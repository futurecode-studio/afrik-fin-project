<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\FeatureFlag;
use App\Models\Partner;
use App\Models\ScheduledOrder;
use App\Models\StockOrderIntent;
use Livewire\Component;

class SgiSgoHub extends Component
{
    use WithSweetAlert;

    public function toggleFlag(int $id): void
    {
        $flag = FeatureFlag::findOrFail($id);
        $flag->update(['enabled' => ! $flag->enabled]);
        FeatureFlag::forgetCache();
        $this->swalSuccess($flag->enabled
            ? '"'.$flag->label.'" activé — visible côté public/client.'
            : '"'.$flag->label.'" désactivé — affichage « Bientôt ».');
    }

    public function render()
    {
        $flags = FeatureFlag::query()->orderBy('sort_order')->get();

        return view('livewire.admin.sgi-sgo-hub', [
            'flags' => $flags,
            'sgiCount' => Partner::sgi()->count(),
            'sgoCount' => Partner::sgo()->count(),
            'activePartners' => Partner::where('is_active', true)->whereIn('type', ['SGI', 'SGO'])->count(),
            'orderIntents' => StockOrderIntent::count() + ScheduledOrder::count(),
            'carnetCount' => StockOrderIntent::count(),
            'programmeCount' => ScheduledOrder::count(),
            'pendingOrders' => StockOrderIntent::where('status', 'pending')->count()
                + ScheduledOrder::where('status', 'pending')->count(),
        ])
            ->extends('layouts.admin', ['title' => 'SGI / SGO'])
            ->section('content');
    }
}
