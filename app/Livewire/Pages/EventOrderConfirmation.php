<?php

namespace App\Livewire\Pages;

use App\Models\EventOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class EventOrderConfirmation extends Component
{
    public EventOrder $order;

    public function mount($orderNumber)
    {
        $this->order = EventOrder::where('order_number', $orderNumber)
            ->with(['items.product', 'items.variant', 'event'])
            ->firstOrFail();
    }

    public function downloadReceipt()
    {
        $pdf = Pdf::loadView('events.receipt', [
            'order' => $this->order,
            'event' => $this->order->event,
        ]);
        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'recu-' . $this->order->order_number . '.pdf');
    }

    public function render()
    {
        return view('livewire.pages.event-order-confirmation')
            ->extends('layouts.site', ['title' => 'Commande confirmée'])
            ->section('content');
    }
}
