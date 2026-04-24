<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Partner;
use Illuminate\Support\Str;

class Partners extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $partnerId;

    public $nom;
    public $contact;
    public $email;
    public $website;
    public $logo;
    public $logo_url;
    public $description;
    public $is_active = true;
    public $order = 0;

    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        $logoRequired = !$this->editMode && empty($this->logo_url) ? 'required' : 'nullable';

        return [
            'nom' => 'required|string|max:255',
            'contact' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => $logoRequired . '|image|max:2048',
            'logo_url' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est obligatoire',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
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

    public function updatedNom()
    {
        if (!$this->editMode && empty($this->slug)) {
            $this->slug = Str::slug($this->nom);
        }
    }

    public function render()
    {
        $partners = Partner::when($this->search, function ($query) {
            $query->where('nom', 'like', '%' . $this->search . '%')
                 ->orWhere('contact', 'like', '%' . $this->search . '%')
                 ->orWhere('email', 'like', '%' . $this->search . '%');
        })->orderBy('order')->paginate(10);

        return view('livewire.admin.partners', [
            'partners' => $partners,
        ])
            ->extends('layouts.admin', ['title' => 'Partenaires'])
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
        $this->resetForm();
        $this->resetValidation();
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        
        $this->partnerId = $partner->id;
        $this->nom = $partner->nom;
        $this->contact = $partner->contact;
        $this->email = $partner->email;
        $this->website = $partner->website;
        $this->logo_url = $partner->logo;
        $this->description = $partner->description;
        $this->is_active = $partner->is_active;
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
            'contact' => $this->contact,
            'email' => $this->email,
            'website' => $this->website,
            'logo' => $logoPath,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'order' => $this->order ?? 0,
        ];

        if ($this->editMode) {
            $partner = Partner::findOrFail($this->partnerId);
            if (!$this->logo) {
                unset($partnerData['logo']);
            }
            $partner->update($partnerData);
            session()->flash('message', 'Partenaire modifié avec succès');
        } else {
            Partner::create($partnerData);
            session()->flash('message', 'Partenaire créé avec succès');
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
        
        session()->flash('message', 'Partenaire supprimé avec succès');
        $this->showDeleteModal = false;
        $this->partnerId = null;
        
        $this->dispatch('partner-saved');
    }

    public function toggleActive($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->update(['is_active' => !$partner->is_active]);
        
        $status = $partner->is_active ? 'activé' : 'désactivé';
        session()->flash('message', 'Partenaire ' . $status . ' avec succès');
    }

    public function resetForm()
    {
        $this->partnerId = null;
        $this->nom = '';
        $this->contact = '';
        $this->email = '';
        $this->website = '';
        $this->logo = null;
        $this->logo_url = '';
        $this->description = '';
        $this->is_active = true;
        $this->order = 0;
    }
}