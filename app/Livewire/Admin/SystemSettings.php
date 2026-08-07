<?php

namespace App\Livewire\Admin;

use Livewire\Component;

/**
 * Hub Paramètres Système (Stitch) — liens vers config admin.
 */
class SystemSettings extends Component
{
    public function render()
    {
        $cards = [
            ['title' => 'Équipe', 'desc' => 'Dirigeants & collaborateurs', 'route' => 'admin.team', 'icon' => 'badge'],
            ['title' => 'Services du site', 'desc' => 'Offre publiée sur /services', 'route' => 'admin.site-services', 'icon' => 'widgets'],
            ['title' => 'Réseaux sociaux', 'desc' => 'Liens footer / partage', 'route' => 'admin.social-links', 'icon' => 'share'],
            ['title' => 'Rôles & permissions', 'desc' => 'Contrôle d’accès admin', 'route' => 'admin.roles', 'icon' => 'admin_panel_settings'],
            ['title' => 'Configuration API', 'desc' => 'Clés et intégrations', 'route' => 'admin.api-config', 'icon' => 'vpn_key'],
            ['title' => 'Statistiques', 'desc' => 'Indicateurs du site', 'route' => 'admin.statistics', 'icon' => 'analytics'],
        ];

        $cards = collect($cards)->filter(function ($c) {
            try {
                route($c['route']);

                return true;
            } catch (\Throwable) {
                return false;
            }
        })->values();

        return view('livewire.admin.system-settings', compact('cards'))
            ->extends('layouts.admin', ['title' => 'Paramètres Système'])
            ->section('content');
    }
}
