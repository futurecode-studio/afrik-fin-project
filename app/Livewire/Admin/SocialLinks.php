<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\SocialLink;
use App\Livewire\Concerns\WithSweetAlert;

class SocialLinks extends Component
{
    use WithSweetAlert;
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $linkId;

    public $platform = 'facebook';
    public $url = '';
    public $is_active = true;
    public $order = 0;

    public function rules(): array
    {
        return [
            'platform'  => 'required|string|in:' . implode(',', array_keys(SocialLink::PLATFORMS)),
            'url'       => 'required|url|max:500',
            'is_active' => 'boolean',
            'order'     => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'platform.required' => 'La plateforme est obligatoire',
            'platform.in'       => 'Plateforme non supportée',
            'url.required'      => "L'URL est obligatoire",
            'url.url'           => "L'URL doit être valide (https://...)",
            'order.integer'     => "L'ordre doit être un nombre entier",
        ];
    }

    public function render()
    {
        $links = SocialLink::orderBy('order')->orderBy('platform')->get();

        return view('livewire.admin.social-links', [
            'links'     => $links,
            'platforms' => SocialLink::PLATFORMS,
        ])
            ->extends('layouts.admin', ['title' => 'Réseaux Sociaux'])
            ->section('content');
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
        $link = SocialLink::findOrFail($id);

        $this->linkId    = $link->id;
        $this->platform  = $link->platform;
        $this->url       = $link->url;
        $this->is_active = $link->is_active;
        $this->order     = $link->order;

        $this->editMode  = true;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'platform'  => $this->platform,
            'url'       => $this->url,
            'is_active' => $this->is_active,
            'order'     => $this->order ?? 0,
        ];

        if ($this->editMode) {
            SocialLink::findOrFail($this->linkId)->update($data);
            $this->swalSuccess('Lien modifié avec succès');
        } else {
            SocialLink::create($data);
            $this->swalSuccess('Lien créé avec succès');
        }

        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        cache()->forget('social_links_active');
    }

    public function confirmDelete(int $id): void
    {
        $this->linkId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        SocialLink::findOrFail($this->linkId)->delete();
        $this->swalSuccess('Lien supprimé avec succès');
        $this->showDeleteModal = false;
        $this->linkId = null;
        cache()->forget('social_links_active');
    }

    public function toggleActive(int $id): void
    {
        $link = SocialLink::findOrFail($id);
        $link->update(['is_active' => !$link->is_active]);
        $status = $link->is_active ? 'activé' : 'désactivé';
        $this->swalSuccess("Lien $status avec succès");
        cache()->forget('social_links_active');
    }

    private function resetForm(): void
    {
        $this->linkId    = null;
        $this->platform  = 'facebook';
        $this->url       = '';
        $this->is_active = true;
        $this->order     = 0;
    }
}
