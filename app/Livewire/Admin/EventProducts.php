<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventProduct;
use App\Models\EventProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class EventProducts extends Component
{
    use WithSweetAlert;
    use WithPagination, WithFileUploads;

    public Event $event;

    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $productId;

    // Product fields
    public $name = '';
    public $description = '';
    public $price = 0;
    public $is_active = true;
    public $image = null;
    public $image_url = '';

    // Variants
    public $variants = [];

    protected $paginationTheme = 'tailwind';

    public function mount(Event $event)
    {
        $this->event = $event;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'image' => $this->editMode ? 'nullable|image|max:2048' : 'nullable|image|max:2048',
            'variants' => 'nullable|array',
            'variants.*.variant_name' => 'required_with:variants|string|max:100',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.size' => 'nullable|string|max:50',
            'variants.*.color' => 'nullable|string|max:50',
            'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
            'variants.*.sku' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
        ];
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
{
        $this->showModal = false;
    }

    public function edit($id)
    {
        $product = EventProduct::with('variants')->findOrFail($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->is_active = $product->is_active;
        $this->image_url = $product->image;
        $this->variants = $product->variants->map(fn($v) => [
            'id' => $v->id,
            'variant_name' => $v->variant_name,
            'price' => $v->price,
            'size' => $v->size,
            'color' => $v->color,
            'stock_quantity' => $v->stock_quantity,
            'sku' => $v->sku,
        ])->toArray();
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->image) {
            $path = $this->image->store('events/products', 'public');
            $this->image_url = $path;
        }

        $data = [
            'event_id' => $this->event->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'is_active' => (bool) $this->is_active,
            'image' => $this->image_url,
        ];

        if ($this->editMode) {
            $product = EventProduct::findOrFail($this->productId);
            $product->update($data);
        } else {
            $product = EventProduct::create($data);
        }

        // Sync variants
        $existingIds = [];
        foreach ($this->variants as $v) {
            if (!empty($v['variant_name'])) {
                $variantData = [
                    'product_id' => $product->id,
                    'variant_name' => $v['variant_name'],
                    'price' => !empty($v['price']) ? (float) $v['price'] : null,
                    'size' => $v['size'] ?? null,
                    'color' => $v['color'] ?? null,
                    'stock_quantity' => (int) ($v['stock_quantity'] ?? 0),
                    'sku' => $v['sku'] ?? strtoupper(Str::slug($product->name)) . '-' . strtoupper(Str::slug($v['variant_name'])),
                ];
                if (!empty($v['id'])) {
                    $variant = EventProductVariant::find($v['id']);
                    if ($variant) {
                        $variant->update($variantData);
                        $existingIds[] = $variant->id;
                    }
                } else {
                    $variant = EventProductVariant::create($variantData);
                    $existingIds[] = $variant->id;
                }
            }
        }

        // Delete removed variants
        $product->variants()->whereNotIn('id', $existingIds)->delete();

        $this->swalSuccess($this->editMode ? 'Article modifié avec succès.' : 'Article créé avec succès.');
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->productId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $product = EventProduct::findOrFail($this->productId);
        $product->variants()->delete();
        $product->delete();
        $this->swalSuccess('Article supprimé avec succès.');
        $this->showDeleteModal = false;
        $this->productId = null;
    }

    public function addVariant()
    {
        $this->variants[] = [
            'variant_name' => '',
            'price' => '',
            'size' => '',
            'color' => '',
            'stock_quantity' => 0,
            'sku' => '',
        ];
    }

    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function toggleActive($id)
    {
        $product = EventProduct::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        $this->swalSuccess('Visibilité modifiée.');
    }

    private function resetForm()
    {
        $this->productId = null;
        $this->name = '';
        $this->description = '';
        $this->price = 0;
        $this->is_active = true;
        $this->image = null;
        $this->image_url = '';
        $this->variants = [];
    }

    public function render()
    {
        $products = EventProduct::where('event_id', $this->event->id)
            ->with('variants')
            ->withCount('orderItems')
            ->latest()
            ->paginate(15);

        return view('livewire.admin.event-products', [
            'products' => $products,
        ])->extends('layouts.admin', ['title' => 'Boutique — ' . $this->event->title])->section('content');
    }
}
