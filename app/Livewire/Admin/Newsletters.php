<?php

namespace App\Livewire\Admin;

use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Concerns\WithSweetAlert;

class Newsletters extends Component
{
    use WithSweetAlert;
    use WithPagination;

    public $activeTab = 'subscribers';
    
    // Pour la création de campagnes
    public $showCampaignModal = false;
    public $title = '';
    public $subject = '';
    public $content = '';
    
    // Pour la gestion des abonnés
    public $showSubscriberModal = false;
    public $subscriberEmail = '';
    public $subscriberName = '';

    protected $rules = [
        'title' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'content' => 'required|string',
    ];

    protected $subscriberRules = [
        'subscriberEmail' => 'required|email|unique:newsletter_subscribers,email',
        'subscriberName' => 'nullable|string|max:255',
    ];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function createCampaign()
    {
        $this->validate();

        $subscribers = NewsletterSubscriber::where('is_active', true)->count();

        Newsletter::create([
            'title' => $this->title,
            'subject' => $this->subject,
            'content' => $this->content,
            'status' => 'draft',
            'recipients_count' => $subscribers,
        ]);

        $this->reset(['title', 'subject', 'content', 'showCampaignModal']);
        $this->swalSuccess('Campagne créée avec succès!');
    }

    public function deleteCampaign($id)
    {
        Newsletter::findOrFail($id)->delete();
        $this->swalSuccess('Campagne supprimée avec succès!');
    }

    public function addSubscriber()
    {
        $this->validate($this->subscriberRules);

        NewsletterSubscriber::create([
            'email' => $this->subscriberEmail,
            'name' => $this->subscriberName,
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        $this->reset(['subscriberEmail', 'subscriberName', 'showSubscriberModal']);
        $this->swalSuccess('Abonné ajouté avec succès!');
    }

    public function toggleSubscriberStatus($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->update([
            'is_active' => !$subscriber->is_active,
            'unsubscribed_at' => !$subscriber->is_active ? now() : null,
        ]);
        $this->swalSuccess('Statut de l\'abonné mis à jour!');
    }

    public function deleteSubscriber($id)
    {
        NewsletterSubscriber::findOrFail($id)->delete();
        $this->swalSuccess('Abonné supprimé avec succès!');
    }

    public function render()
    {
        $campaigns = Newsletter::latest()->paginate(10);
        $subscribers = NewsletterSubscriber::latest()->paginate(10);
        
        return view('livewire.admin.newsletters', [
            'campaigns' => $campaigns,
            'subscribers' => $subscribers,
        ])
            ->extends('layouts.admin', ['title' => 'Newsletters'])
            ->section('content');
    }
}
