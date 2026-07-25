<?php

namespace App\Livewire\Admin;

use App\Models\SiteService;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class SiteServices extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public string $search = '';

    public bool $showModal = false;

    public bool $showDeleteModal = false;

    public bool $editMode = false;

    public ?int $serviceId = null;

    public string $title = '';

    public string $slug = '';

    public string $icon = 'handshake';

    public string $subtitle = '';

    public string $excerpt = '';

    public string $content = '';

    public string $featuresText = '';

    public string $price_label = '';

    public string $duration_label = '';

    public string $image_url = '';

    public string $cta_label = 'En savoir plus';

    public string $cta_url = '';

    public bool $is_active = true;

    public bool $is_featured = false;

    public int $order = 0;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:80',
            'subtitle' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'featuresText' => 'nullable|string',
            'price_label' => 'nullable|string|max:255',
            'duration_label' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:500',
            'cta_label' => 'nullable|string|max:120',
            'cta_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal(): void
{
        $this->showModal = false;
    }

    public function edit(int $id): void
    {
        $service = SiteService::findOrFail($id);
        $this->serviceId = $service->id;
        $this->title = $service->title;
        $this->slug = $service->slug;
        $this->icon = $service->icon ?? 'handshake';
        $this->subtitle = $service->subtitle ?? '';
        $this->excerpt = $service->excerpt ?? '';
        $this->content = $service->content ?? '';
        $this->featuresText = implode("\n", $service->features ?? []);
        $this->price_label = $service->price_label ?? '';
        $this->duration_label = $service->duration_label ?? '';
        $this->image_url = $service->image_url ?? '';
        $this->cta_label = $service->cta_label ?? 'En savoir plus';
        $this->cta_url = $service->cta_url ?? '';
        $this->is_active = (bool) $service->is_active;
        $this->is_featured = (bool) $service->is_featured;
        $this->order = (int) $service->order;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $features = collect(preg_split('/\r\n|\r|\n/', $this->featuresText))
            ->map(fn ($l) => trim($l))
            ->filter()
            ->values()
            ->all();

        $data = [
            'title' => $this->title,
            'slug' => $this->slug !== '' ? Str::slug($this->slug) : Str::slug($this->title),
            'icon' => $this->icon ?: 'handshake',
            'subtitle' => $this->subtitle ?: null,
            'excerpt' => $this->excerpt ?: null,
            'content' => $this->content ?: null,
            'features' => $features,
            'price_label' => $this->price_label ?: null,
            'duration_label' => $this->duration_label ?: null,
            'image_url' => $this->image_url ?: null,
            'cta_label' => $this->cta_label ?: 'En savoir plus',
            'cta_url' => $this->cta_url ?: null,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
        ];

        if ($this->editMode) {
            SiteService::findOrFail($this->serviceId)->update($data);
            $this->swalSuccess('Service modifié avec succès');
        } else {
            SiteService::create($data);
            $this->swalSuccess('Service créé avec succès');
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->serviceId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        SiteService::findOrFail($this->serviceId)->delete();
        $this->swalSuccess('Service supprimé');
        $this->showDeleteModal = false;
        $this->serviceId = null;
    }

    public function toggleActive(int $id): void
    {
        $s = SiteService::findOrFail($id);
        $s->update(['is_active' => ! $s->is_active]);
        $this->swalSuccess('Statut mis à jour');
    }

    public function resetForm(): void
    {
        $this->serviceId = null;
        $this->title = '';
        $this->slug = '';
        $this->icon = 'handshake';
        $this->subtitle = '';
        $this->excerpt = '';
        $this->content = '';
        $this->featuresText = '';
        $this->price_label = '';
        $this->duration_label = '';
        $this->image_url = '';
        $this->cta_label = 'En savoir plus';
        $this->cta_url = '';
        $this->is_active = true;
        $this->is_featured = false;
        $this->order = 0;
    }

    public function render()
    {
        $services = SiteService::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('slug', 'like', '%'.$this->search.'%'))
            ->orderBy('order')
            ->paginate(10);

        return view('livewire.admin.site-services', compact('services'))
            ->extends('layouts.admin', ['title' => 'Services'])
            ->section('content');
    }
}
