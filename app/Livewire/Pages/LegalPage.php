<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class LegalPage extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $allowed = ['mentions-legales', 'cgu', 'confidentialite', 'cookies', 'rgpd', 'conditions-formations', 'disclaimer'];
        abort_unless(in_array($slug, $allowed, true), 404);
        $this->slug = $slug;
    }

    public function render()
    {
        $titles = [
            'mentions-legales' => 'Mentions légales',
            'cgu' => 'Conditions générales d’utilisation',
            'confidentialite' => 'Politique de confidentialité',
            'cookies' => 'Politique cookies',
            'rgpd' => 'Protection des données (RGPD)',
            'conditions-formations' => 'Conditions des formations',
            'disclaimer' => 'Avertissement investissement',
        ];

        return view('livewire.pages.legal-page', [
            'pageTitle' => $titles[$this->slug],
        ])
            ->extends('layouts.site', ['title' => $titles[$this->slug]])
            ->section('content');
    }
}
