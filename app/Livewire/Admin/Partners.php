<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Partner;
use Illuminate\Support\Str;
use App\Livewire\Concerns\WithSweetAlert;

class Partners extends Component
{
    use WithSweetAlert;
    use WithPagination, WithFileUploads;

    public $search = '';
    public string $filterType = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $partnerId;

    public $nom;
    public $type = 'SGI';
    public $country = '';
    public $city = '';
    public $agreement_number = '';
    public $contact;
    public $email;
    public $website;
    public $logo;
    public $logo_url;
    public $description;
    public $admin_notes = '';
    public $is_active = true;
    public $is_featured = false;
    public $order = 0;

    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        $logoRequired = 'nullable';

        return [
            'nom' => 'required|string|max:255',
            'type' => 'required|string|in:SGO,SGI,Autre',
            'country' => 'nullable|string|max:80',
            'city' => 'nullable|string|max:80',
            'agreement_number' => 'nullable|string|max:120',
            'contact' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => $logoRequired . '|image|max:2048',
            'logo_url' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est obligatoire',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'type.required' => 'La catégorie est obligatoire',
            'type.in' => 'La catégorie doit être SGO, SGI ou Autre',
            'contact.max' => 'Le contact ne doit pas dépasser 100 caractères',
            'email.email' => 'L\'adresse email doit être valide',
            'email.max' => 'L\'email ne doit pas dépasser 255 caractères',
            'website.url' => 'L\'URL du site web doit être valide',
            'website.max' => 'L\'URL ne doit pas dépasser 255 caractères',
            'description.max' => 'La description ne doit pas dépasser 1000 caractères',
            'order.integer' => 'L\'ordre doit être un nombre entier',
            'order.min' => 'L\'ordre doit être positif',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $partners = Partner::query()
            ->when($this->search, function ($query) {
                $like = '%'.$this->search.'%';
                $query->where(function ($q) use ($like) {
                    $q->where('nom', 'like', $like)
                        ->orWhere('contact', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('agreement_number', 'like', $like);
                });
            })
            ->when($this->filterType !== '', fn ($q) => $q->where('type', $this->filterType))
            ->orderBy('order')
            ->paginate(10);

        return view('livewire.admin.partners', [
            'partners' => $partners,
            'sgiCount' => Partner::sgi()->count(),
            'sgoCount' => Partner::sgo()->count(),
        ])
            ->extends('layouts.admin', ['title' => 'Partenaires SGI / SGO'])
            ->section('content');
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
        $partner = Partner::findOrFail($id);
        
        $this->partnerId = $partner->id;
        $this->nom = $partner->nom;
        $this->type = $partner->type ?? 'Autre';
        $this->country = $partner->country ?? '';
        $this->city = $partner->city ?? '';
        $this->agreement_number = $partner->agreement_number ?? '';
        $this->contact = $partner->contact;
        $this->email = $partner->email;
        $this->website = $partner->website;
        $this->logo_url = $partner->logo;
        $this->description = $partner->description;
        $this->admin_notes = $partner->admin_notes ?? '';
        $this->is_active = $partner->is_active;
        $this->is_featured = (bool) $partner->is_featured;
        $this->order = $partner->order;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $logoPath = $this->logo_url;

        if ($this->logo) {
            $filename = Str::slug($this->nom) . '-' . time() . '.' . $this->logo->getClientOriginalExtension();
            $logoPath = $this->logo->storeAs('partners', $filename, 'public');
        }

        $partnerData = [
            'nom' => $this->nom,
            'type' => $this->type,
            'country' => $this->country ?: null,
            'city' => $this->city ?: null,
            'agreement_number' => $this->agreement_number ?: null,
            'contact' => $this->contact,
            'email' => $this->email,
            'website' => $this->website,
            'logo' => $logoPath,
            'description' => $this->description,
            'admin_notes' => $this->admin_notes ?: null,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'order' => $this->order ?? 0,
        ];

        if ($this->editMode) {
            $partner = Partner::findOrFail($this->partnerId);
            if (!$this->logo) {
                unset($partnerData['logo']);
            }
            $partner->update($partnerData);
            $this->swalSuccess('Partenaire modifié avec succès');
        } else {
            Partner::create($partnerData);
            $this->swalSuccess('Partenaire créé avec succès');
        }

        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->resetPage();
        
        $this->dispatch('partner-saved');
    }

    public function confirmDelete($id)
    {
        $this->partnerId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $partner = Partner::findOrFail($this->partnerId);
        
        if ($partner->logo && \Storage::disk('public')->exists('partners/' . $partner->logo)) {
            \Storage::disk('public')->delete('partners/' . $partner->logo);
        }
        
        $partner->delete();
        
        $this->swalSuccess('Partenaire supprimé avec succès');
        $this->showDeleteModal = false;
        $this->partnerId = null;
        
        $this->dispatch('partner-saved');
    }

    public function toggleActive($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->update(['is_active' => !$partner->is_active]);
        
        $status = $partner->is_active ? 'activé' : 'désactivé';
        $this->swalSuccess('Partenaire ' . $status . ' avec succès');
    }

    public function resetForm()
    {
        $this->partnerId = null;
        $this->nom = '';
        $this->type = 'SGI';
        $this->country = '';
        $this->city = '';
        $this->agreement_number = '';
        $this->contact = '';
        $this->email = '';
        $this->website = '';
        $this->logo = null;
        $this->logo_url = '';
        $this->description = '';
        $this->admin_notes = '';
        $this->is_active = true;
        $this->is_featured = false;
        $this->order = 0;
    }
}