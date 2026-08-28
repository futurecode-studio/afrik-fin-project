<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\WithSweetAlert;
use App\Models\Formation;
use App\Models\FormationCatalogItem;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormationCatalog extends Component
{
    use WithFileUploads;
    use WithSweetAlert;

    public Formation $formation;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $catalogImages = [];

    public string $newTitle = '';

    public function mount(Formation $formation): void
    {
        $this->formation = $formation;
    }

    public function uploadCatalogImages(): void
    {
        $this->validate([
            'catalogImages.*' => 'image|max:5120',
            'newTitle' => 'nullable|string|max:255',
        ]);

        $order = (int) $this->formation->catalogItems()->max('display_order');

        foreach ($this->catalogImages as $upload) {
            $order++;
            $path = $upload->store('formations/catalog/'.$this->formation->id, 'public');
            $this->formation->catalogItems()->create([
                'image_path' => $path,
                'title' => $this->newTitle ?: null,
                'display_order' => $order,
                'is_active' => true,
            ]);
        }

        $this->catalogImages = [];
        $this->newTitle = '';
        $this->swalSuccess('Visuel(s) ajouté(s) au catalogue.');
    }

    public function removeItem(int $itemId): void
    {
        $item = $this->formation->catalogItems()->whereKey($itemId)->first();
        if (! $item) {
            return;
        }

        $item->delete();
        $this->swalSuccess('Visuel retiré du catalogue.');
    }

    public function moveItem(int $itemId, string $direction): void
    {
        $items = $this->formation->catalogItems()->orderBy('display_order')->get();
        $index = $items->search(fn ($item) => $item->id === $itemId);

        if ($index === false) {
            return;
        }

        $swapIndex = $direction === 'up' ? $index - 1 : $index + 1;
        if ($swapIndex < 0 || $swapIndex >= $items->count()) {
            return;
        }

        $current = $items[$index];
        $other = $items[$swapIndex];
        $currentOrder = $current->display_order;
        $current->update(['display_order' => $other->display_order]);
        $other->update(['display_order' => $currentOrder]);
    }

    public function toggleActive(int $itemId): void
    {
        $item = $this->formation->catalogItems()->whereKey($itemId)->first();
        if (! $item) {
            return;
        }

        $item->update(['is_active' => ! $item->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.formation-catalog', [
            'items' => $this->formation->catalogItems()->orderBy('display_order')->get(),
        ])
            ->extends('layouts.admin', ['title' => 'Catalogue — '.$this->formation->titre])
            ->section('content');
    }
}
