<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\TeamMember;
use Illuminate\Support\Str;
use App\Livewire\Concerns\WithSweetAlert;

class TeamMembers extends Component
{
    use WithSweetAlert;
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $memberId;

    public $nom;
    public $poste;
    public $attributs;
    public $description;
    public $contact;
    public $email;
    public $photo;
    public $photo_url;
    public $is_active = true;
    public $is_leadership = false;
    public $order = 0;

    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'poste' => 'required|string|max:255',
            'attributs' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:2000',
            'contact' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'photo' => 'nullable|image|max:2048',
            'photo_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_leadership' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est obligatoire',
            'nom.max' => 'Le nom ne doit pas dépasser 255 caractères',
            'poste.required' => 'Le poste est obligatoire',
            'poste.max' => 'Le poste ne doit pas dépasser 255 caractères',
            'attributs.max' => 'Les attributs ne doivent pas dépasser 500 caractères',
            'description.max' => 'La description ne doit pas dépasser 2000 caractères',
            'contact.max' => 'Le contact ne doit pas dépasser 50 caractères',
            'email.email' => 'L\'adresse email doit être valide',
            'email.max' => 'L\'email ne doit pas dépasser 255 caractères',
            'order.integer' => 'L\'ordre doit être un nombre entier',
            'order.min' => 'L\'ordre doit être positif',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $members = TeamMember::when($this->search, function ($query) {
            $query->where('nom', 'like', '%'.$this->search.'%')
                ->orWhere('poste', 'like', '%'.$this->search.'%');
        })->orderBy('order')->paginate(10);

        return view('livewire.admin.team-members', [
            'members' => $members,
        ])
            ->extends('layouts.admin', ['title' => 'Équipe'])
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
        $member = TeamMember::findOrFail($id);

        $this->memberId = $member->id;
        $this->nom = $member->nom;
        $this->poste = $member->poste;
        $this->attributs = $member->attributs;
        $this->description = $member->description;
        $this->contact = $member->contact;
        $this->email = $member->email;
        $this->photo_url = $member->photo;
        $this->is_active = $member->is_active;
        $this->is_leadership = (bool) $member->is_leadership;
        $this->order = $member->order;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $photoPath = $this->photo_url;

        if ($this->photo) {
            $filename = Str::slug($this->nom).'-'.time().'.'.$this->photo->getClientOriginalExtension();
            $photoPath = $this->photo->storeAs('team', $filename, 'public');
        }

        $memberData = [
            'nom' => $this->nom,
            'poste' => $this->poste,
            'attributs' => $this->attributs,
            'description' => $this->description,
            'contact' => $this->contact,
            'email' => $this->email,
            'photo' => $photoPath,
            'is_active' => $this->is_active,
            'is_leadership' => $this->is_leadership,
            'order' => $this->order ?? 0,
        ];

        if ($this->editMode) {
            $member = TeamMember::findOrFail($this->memberId);
            if (! $this->photo) {
                unset($memberData['photo']);
            }
            $member->update($memberData);
            $this->swalSuccess('Membre modifié avec succès');
        } else {
            TeamMember::create($memberData);
            $this->swalSuccess('Membre créé avec succès');
        }

        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->resetPage();

        $this->dispatch('member-saved');
    }

    public function confirmDelete($id)
    {
        $this->memberId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $member = TeamMember::findOrFail($this->memberId);

        if ($member->photo) {
            $path = str_starts_with($member->photo, 'team/')
                ? $member->photo
                : 'team/'.$member->photo;
            if (\Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
        }

        $member->delete();

        $this->swalSuccess('Membre supprimé avec succès');
        $this->showDeleteModal = false;
        $this->memberId = null;

        $this->dispatch('member-saved');
    }

    public function toggleActive($id)
    {
        $member = TeamMember::findOrFail($id);
        $member->update(['is_active' => ! $member->is_active]);

        $status = $member->is_active ? 'activé' : 'désactivé';
        $this->swalSuccess('Membre '.$status.' avec succès');
    }

    public function toggleLeadership($id)
    {
        $member = TeamMember::findOrFail($id);
        $member->update(['is_leadership' => ! $member->is_leadership]);

        $status = $member->is_leadership ? 'ajouté à l’équipe dirigeante' : 'retiré de l’équipe dirigeante';
        $this->swalSuccess('Membre '.$status);
    }

    public function resetForm()
    {
        $this->memberId = null;
        $this->nom = '';
        $this->poste = '';
        $this->attributs = '';
        $this->description = '';
        $this->contact = '';
        $this->email = '';
        $this->photo = null;
        $this->photo_url = '';
        $this->is_active = true;
        $this->is_leadership = false;
        $this->order = 0;
    }
}
