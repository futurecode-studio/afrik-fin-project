<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use App\Models\EventGallery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Events extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $eventId;

    // Champs formulaire
    public $title;
    public $slug;
    public $description;
    public $content;
    public $category;
    public $event_type = 'physical';
    public $starts_at;
    public $ends_at;
    public $registration_opens_at;
    public $registration_closes_at;
    public $location_name;
    public $location_address;
    public $city;
    public $country;
    public $capacity = 0;
    public $featured_image;
    public $featured_image_url = '';
    public $galleryImages = [];
    public $existingGallery = [];
    public $seo_title;
    public $seo_description;
    public $is_featured = false;
    public $status = 'draft';

    protected $paginationTheme = 'tailwind';

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:events,slug,' . ($this->eventId ?? 'NULL') . ',id,deleted_at,NULL',
            'description' => 'nullable|string|max:2000',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'event_type' => 'required|in:physical,online,hybrid',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'registration_opens_at' => 'nullable|date',
            'registration_closes_at' => 'nullable|date|after_or_equal:registration_opens_at',
            'location_name' => 'nullable|string|max:255',
            'location_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'capacity' => 'nullable|integer|min:0',
            'featured_image' => 'nullable|image|max:2048',
            'galleryImages.*' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled,archived',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Le titre est obligatoire.',
            'slug.unique' => 'Ce slug est déjà utilisé.',
            'starts_at.required' => 'La date de début est obligatoire.',
            'ends_at.after_or_equal' => 'La date de fin doit être postérieure à la date de début.',
            'status.required' => 'Le statut est obligatoire.',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedTitle()
    {
        if (!$this->editMode || empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
        $this->dispatch('event-modal-opened');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->dispatch('event-modal-closed');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $this->eventId = $event->id;
        $this->title = $event->title;
        $this->slug = $event->slug;
        $this->description = $event->description;
        $this->content = $event->content;
        $this->category = $event->category;
        $this->event_type = $event->event_type;
        $this->starts_at = $event->starts_at?->format('Y-m-d\TH:i');
        $this->ends_at = $event->ends_at?->format('Y-m-d\TH:i');
        $this->registration_opens_at = $event->registration_opens_at?->format('Y-m-d\TH:i');
        $this->registration_closes_at = $event->registration_closes_at?->format('Y-m-d\TH:i');
        $this->location_name = $event->location_name;
        $this->location_address = $event->location_address;
        $this->city = $event->city;
        $this->country = $event->country;
        $this->capacity = $event->capacity;
        $this->featured_image_url = $event->featured_image;
        $this->existingGallery = $event->galleries()->orderBy('display_order')->get()->toArray();
        $this->seo_title = $event->seo_title;
        $this->seo_description = $event->seo_description;
        $this->is_featured = $event->is_featured;
        $this->status = $event->status;
        $this->editMode = true;
        $this->showModal = true;
        $this->dispatch('event-modal-opened');
    }

    public function save()
    {
        $this->validate();

        if ($this->featured_image) {
            $path = $this->featured_image->store('events/featured', 'public');
            $this->featured_image_url = $path;
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'description' => $this->description,
            'content' => $this->content,
            'category' => $this->category,
            'event_type' => $this->event_type,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'registration_opens_at' => $this->registration_opens_at,
            'registration_closes_at' => $this->registration_closes_at,
            'location_name' => $this->location_name,
            'location_address' => $this->location_address,
            'city' => $this->city,
            'country' => $this->country,
            'capacity' => (int) ($this->capacity ?? 0),
            'featured_image' => $this->featured_image_url,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'is_featured' => (bool) $this->is_featured,
            'status' => $this->status,
            'created_by' => Auth::id(),
        ];

        if ($this->editMode) {
            $event = Event::findOrFail($this->eventId);
            $event->update($data);
        } else {
            $event = Event::create($data);
        }

        // Sauvegarder les nouvelles images de galerie
        foreach ($this->galleryImages as $upload) {
            $path = $upload->store('events/gallery', 'public');
            $event->galleries()->create([
                'image_path' => $path,
                'caption' => '',
                'is_featured' => false,
                'display_order' => $event->galleries()->count(),
            ]);
        }

        session()->flash('message', $this->editMode ? 'Événement modifié avec succès.' : 'Événement créé avec succès.');

        $this->closeModal();
        $this->dispatch('event-saved');
    }

    public function confirmDelete($id)
    {
        $this->eventId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Event::findOrFail($this->eventId)->delete();
        session()->flash('message', 'Événement supprimé avec succès.');
        $this->showDeleteModal = false;
        $this->eventId = null;
        $this->dispatch('event-saved');
    }

    public function restore($id)
    {
        $event = Event::withTrashed()->findOrFail($id);
        $event->restore();
        session()->flash('message', 'Événement restauré avec succès.');
        $this->dispatch('event-saved');
    }

    public function duplicate($id)
    {
        $event = Event::findOrFail($id);
        $clone = $event->replicate();
        $clone->slug = Str::slug($event->title . '-copy-' . uniqid());
        $clone->status = 'draft';
        $clone->registration_count = 0;
        $clone->save();
        session()->flash('message', 'Événement dupliqué avec succès.');
    }

    public function toggleFeatured($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['is_featured' => !$event->is_featured]);
        session()->flash('message', 'Mise en avant modifiée.');
    }

    public function removeGalleryImage($galleryId)
    {
        $gallery = EventGallery::find($galleryId);
        if ($gallery) {
            $gallery->delete();
            $this->existingGallery = array_values(array_filter($this->existingGallery, fn($g) => $g['id'] != $galleryId));
            session()->flash('message', 'Image supprimée.');
        }
    }

    private function resetForm()
    {
        $this->eventId = null;
        $this->title = '';
        $this->slug = '';
        $this->description = '';
        $this->content = '';
        $this->category = '';
        $this->event_type = 'physical';
        $this->starts_at = '';
        $this->ends_at = '';
        $this->registration_opens_at = '';
        $this->registration_closes_at = '';
        $this->location_name = '';
        $this->location_address = '';
        $this->city = '';
        $this->country = '';
        $this->capacity = 0;
        $this->featured_image = null;
        $this->featured_image_url = '';
        $this->galleryImages = [];
        $this->existingGallery = [];
        $this->seo_title = '';
        $this->seo_description = '';
        $this->is_featured = false;
        $this->status = 'draft';
    }

    public function render()
    {
        $events = Event::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('city', 'like', '%' . $this->search . '%')
                    ->orWhere('category', 'like', '%' . $this->search . '%');
            })
            ->withCount(['registrations' => fn($q) => $q->active()])
            ->withTrashed()
            ->latest('starts_at')
            ->paginate(10);

        return view('livewire.admin.events', [
            'events' => $events,
        ])->extends('layouts.admin', ['title' => 'Gestion des Événements'])->section('content');
    }
}
