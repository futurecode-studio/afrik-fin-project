<?php

namespace App\Livewire\Admin;

use App\Models\ApiIntegration;
use App\Services\ApiCredentials;
use Livewire\Component;
use App\Livewire\Concerns\WithSweetAlert;

class ApiConfig extends Component
{
    use WithSweetAlert;
    public string $activeProvider = 'kkiapay';

    /** @var array<string, string> */
    public array $form = [];

    public bool $sandbox = true;

    public bool $isEnabled = false;

    public function mount(): void
    {
        $this->loadProvider($this->activeProvider);
    }

    public function selectProvider(string $provider): void
    {
        if (! isset(ApiCredentials::catalog()[$provider])) {
            return;
        }
        $this->activeProvider = $provider;
        $this->loadProvider($provider);
        $this->resetErrorBag();
    }

    public function loadProvider(string $provider): void
    {
        $meta = ApiCredentials::catalog()[$provider] ?? null;
        if (! $meta) {
            return;
        }

        $row = ApiIntegration::firstOrCreate(
            ['provider' => $provider],
            [
                'label' => $meta['label'],
                'is_enabled' => false,
                'sandbox' => (bool) ($meta['has_sandbox'] ?? true),
                'credentials' => [],
            ]
        );

        $this->sandbox = (bool) $row->sandbox;
        $this->isEnabled = (bool) $row->is_enabled;
        $this->form = [];

        foreach ($meta['fields'] as $key => $field) {
            // Ne jamais préremplir les secrets en clair — champ vide = conserver
            if ($field['secret'] ?? false) {
                $this->form[$key] = '';
            } else {
                $this->form[$key] = (string) ($row->credential($key) ?? '');
            }
        }
    }

    public function save(): void
    {
        $meta = ApiCredentials::catalog()[$this->activeProvider] ?? null;
        abort_unless($meta, 404);

        $rules = [
            'isEnabled' => 'boolean',
            'sandbox' => 'boolean',
        ];
        foreach ($meta['fields'] as $key => $field) {
            $rules["form.{$key}"] = 'nullable|string|max:2000';
        }
        $this->validate($rules);

        $row = ApiIntegration::firstOrCreate(
            ['provider' => $this->activeProvider],
            ['label' => $meta['label'], 'credentials' => []]
        );

        $credentials = $row->credentials ?? [];
        foreach ($meta['fields'] as $key => $field) {
            $incoming = trim((string) ($this->form[$key] ?? ''));
            if (($field['secret'] ?? false) && $incoming === '') {
                // conserver la valeur existante (DB ou laisser vide pour fallback env)
                continue;
            }
            if ($incoming === '') {
                unset($credentials[$key]);
            } else {
                $credentials[$key] = $incoming;
            }
        }

        $row->update([
            'label' => $meta['label'],
            'is_enabled' => $this->isEnabled,
            'sandbox' => $meta['has_sandbox'] ? $this->sandbox : false,
            'credentials' => $credentials,
        ]);

        ApiCredentials::forgetCache();
        ApiCredentials::applyToConfig();

        $this->swalSuccess($meta['label'].' enregistré. Les clés DB sont prioritaires sur le .env.');
        $this->loadProvider($this->activeProvider);
    }

    public function clearDbCredentials(): void
    {
        $row = ApiIntegration::where('provider', $this->activeProvider)->first();
        if ($row) {
            $row->update([
                'credentials' => [],
                'is_enabled' => false,
            ]);
        }
        ApiCredentials::forgetCache();
        ApiCredentials::applyToConfig();
        $this->swalSuccess('Clés DB effacées — le backend retombera sur le .env si présent.');
        $this->loadProvider($this->activeProvider);
    }

    public function render()
    {
        $board = ApiCredentials::statusBoard();
        $configuredCount = collect($board)->where('configured', true)->count();
        $active = collect($board)->firstWhere('provider', $this->activeProvider);

        return view('livewire.admin.api-config', [
            'board' => $board,
            'configuredCount' => $configuredCount,
            'active' => $active,
            'catalog' => ApiCredentials::catalog(),
        ])
            ->extends('layouts.admin', ['title' => 'Configuration API'])
            ->section('content');
    }
}
