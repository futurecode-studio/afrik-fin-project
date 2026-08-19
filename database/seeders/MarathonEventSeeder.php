<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventGallery;
use App\Models\User;
use Illuminate\Database\Seeder;

class MarathonEventSeeder extends Seeder
{
    public function run(): void
    {
        $existing = Event::where('slug', 'marathon-acteurs-marche-financier-2026')->first();
        if ($existing) {
            $this->command?->info('Marathon event already exists — skipping.');
            return;
        }

        $admin = User::first();
        if (! $admin) {
            $this->command?->error('No user found for created_by. Aborting.');
            return;
        }

        $event = Event::create([
            'title' => 'Marathon des Acteurs du Marché Financier au Bénin',
            'slug' => 'marathon-acteurs-marche-financier-2026',
            'description' => 'Une rencontre dédiée aux acteurs et passionnés du marché financier régional, autour du partage d\'expériences, de la sensibilisation et de la promotion de la culture financière.',
            'content' => '<p>Le Marathon des Acteurs du Marché Financier est un événement phare organisé par Africaine des Finances pour rassembler investisseurs, épargnants et professionnels du marché financier régional UEMOA.</p><p>Au programme : stands d\'information, ateliers pratiques, rencontres avec les acteurs agréés (SGI, SGO) et activités sportives pour promouvoir la culture financière auprès du grand public.</p>',
            'category' => 'Rencontre',
            'event_type' => 'physical',
            'starts_at' => '2026-06-20 08:00:00',
            'ends_at' => '2026-06-20 18:00:00',
            'location_name' => 'Place Amazone',
            'location_address' => 'Place Amazone, Cotonou',
            'city' => 'Cotonou',
            'country' => 'Bénin',
            'capacity' => 0,
            'registration_count' => 0,
            'featured_image' => 'assets/images/events/marathon/affiche-marathon.png',
            'is_featured' => true,
            'status' => 'completed',
            'created_by' => $admin->id,
        ]);

        $photos = [
            ['image_path' => 'assets/images/events/marathon/marathon-01.png', 'caption' => 'Stand Africaine des Finances', 'is_featured' => true, 'display_order' => 1],
            ['image_path' => 'assets/images/events/marathon/marathon-02.png', 'caption' => 'Les roll-ups et kakémonos du stand', 'is_featured' => false, 'display_order' => 2],
            ['image_path' => 'assets/images/events/marathon/marathon-03.png', 'caption' => 'Séance d\'échauffement collectif', 'is_featured' => false, 'display_order' => 3],
            ['image_path' => 'assets/images/events/marathon/marathon-04.png', 'caption' => 'Les participants en action', 'is_featured' => false, 'display_order' => 4],
            ['image_path' => 'assets/images/events/marathon/marathon-05.png', 'caption' => 'Marathonien aujourd\'hui, investisseur BRVM demain', 'is_featured' => false, 'display_order' => 5],
        ];

        foreach ($photos as $photo) {
            EventGallery::create(array_merge($photo, ['event_id' => $event->id]));
        }

        $this->command?->info("Marathon event created with {$event->id} + 5 gallery photos.");
    }
}
