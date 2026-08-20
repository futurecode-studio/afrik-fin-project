<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventDocument;
use App\Models\EventGallery;
use App\Models\EventProgramItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@africainedesfinances.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );

        $event = Event::firstOrCreate(
            ['slug' => 'marathon-acteurs-marche-financier-2026'],
            [
                'title' => 'Marathon des Acteurs du Marché Financier',
                'description' => 'Une demi-journée sportive et conviviale articulée autour d\'une grande marche collective de 10 km, accompagnée d\'exercices de fitness et de sensibilisation sur la thématique : "Santé & Finance : Investir dans son corps comme dans son portefeuille".',
                'content' => '<h2>Concept</h2><p>Le Marathon des acteurs du marché financier est une demi-journée sportive et conviviale articulée autour d\'une grande marche collective, accompagnée d\'exercices de fitness et de sensibilisation sur la thématique : <strong>"Santé & Finance : Investir dans son corps comme dans son portefeuille"</strong>.</p><h2>Objectifs</h2><ul><li>Sensibiliser sur les risques liés à la sédentarité</li><li>Encourager la pratique régulière d\'activités physiques</li><li>Favoriser le networking informel entre acteurs du marché</li><li>Renforcer l\'image du marché financier régional</li></ul><h2>Format</h2><ul><li>Parcours : 10 km (accessible à tous)</li><li>Départ groupé</li><li>Encadrement sécuritaire (police, pompiers, urgentistes, sécurité, encadreurs sportifs)</li><li>Séance d\'échauffement collectif</li><li>Ateliers de sensibilisation santé : Hygiène de vie, Nutrition, Gestion du stress</li><li>Stand de prise de constantes : Tension artérielle, IMC</li><li>Networking & rafraîchissements</li></ul><h2>Cible</h2><p>SGI, SGO, banques, compagnies d\'assurance, BRVM/ANB, investisseurs institutionnels et particuliers, partenaires médias et sponsors.</p>',
                'category' => 'Sport & Bien-être',
                'event_type' => 'physical',
                'starts_at' => now()->addDays(25)->setTime(6, 30),
                'ends_at' => now()->addDays(25)->setTime(11, 30),
                'registration_opens_at' => now()->subDays(30),
                'registration_closes_at' => now()->addDays(23)->setTime(23, 59),
                'location_name' => 'Place AMAZONE',
                'location_address' => 'Place AMAZONE, Cotonou, Bénin',
                'city' => 'Cotonou',
                'country' => 'Bénin',
                'capacity' => 200,
                'seo_title' => 'Marathon des Acteurs du Marché Financier 2026 | Africaine des Finances',
                'seo_description' => 'Rejoignez le Marathon des Acteurs du Marché Financier le 20 Juin 2026 à Cotonou. 10 km, fitness, networking et bien-être pour les professionnels de la finance.',
                'is_featured' => true,
                'status' => 'published',
                'created_by' => $admin->id,
            ]
        );

        // Programme
        $program = [
            ['title' => 'Accueil des participants', 'starts_at' => '06:30', 'ends_at' => '07:00', 'display_order' => 1, 'location_detail' => 'Zone départ Place AMAZONE'],
            ['title' => 'Échauffement collectif', 'starts_at' => '07:00', 'ends_at' => '07:30', 'display_order' => 2, 'location_detail' => 'Zone départ'],
            ['title' => 'Départ de la marche (10 km)', 'starts_at' => '07:30', 'ends_at' => '09:00', 'display_order' => 3, 'location_detail' => 'Parcours urbain sécurisé'],
            ['title' => 'Retour & hydratation', 'starts_at' => '09:00', 'ends_at' => '09:30', 'display_order' => 4, 'location_detail' => 'Zone arrivée'],
            ['title' => 'Séance fitness / aérobic', 'starts_at' => '09:30', 'ends_at' => '10:30', 'display_order' => 5, 'location_detail' => 'Espace fitness'],
            ['title' => 'Ateliers santé & networking', 'starts_at' => '10:30', 'ends_at' => '11:30', 'display_order' => 6, 'location_detail' => 'Stands thématiques'],
            ['title' => 'Clôture officielle', 'starts_at' => '11:30', 'ends_at' => '11:45', 'display_order' => 7, 'location_detail' => 'Podium'],
        ];

        foreach ($program as $item) {
            EventProgramItem::firstOrCreate(
                ['event_id' => $event->id, 'title' => $item['title'], 'starts_at' => $item['starts_at']],
                array_merge($item, ['event_id' => $event->id])
            );
        }

        // Documents
        $docs = [
            ['title' => 'Règlement de participation', 'file_type' => 'pdf', 'is_downloadable' => true, 'display_order' => 1],
            ['title' => 'Programme détaillé (PDF)', 'file_type' => 'pdf', 'is_downloadable' => true, 'display_order' => 2],
            ['title' => 'Fiche santé & recommandations', 'file_type' => 'pdf', 'is_downloadable' => true, 'display_order' => 3],
        ];

        foreach ($docs as $d) {
            EventDocument::firstOrCreate(
                ['event_id' => $event->id, 'title' => $d['title']],
                array_merge($d, [
                    'event_id' => $event->id,
                    'file_path' => 'events/documents/' . Str::slug($d['title']) . '.pdf',
                    'file_size' => 0,
                ])
            );
        }

        // —— Événements complémentaires (catalogue public) ——
        $extraEvents = [
            [
                'slug' => 'webinar-investir-brvm-debutants-2026',
                'title' => 'Webinaire : Investir à la BRVM pour débutants',
                'description' => 'Session en ligne pour comprendre le fonctionnement de la BRVM, ouvrir un compte-titres et construire un premier portefeuille diversifié.',
                'content' => '<p>Une introduction claire aux marchés actions UEMOA : cotations, ordres, risques et bonnes pratiques.</p>',
                'category' => 'Formation',
                'event_type' => 'online',
                'starts_at' => now()->addDays(18)->setTime(18, 30),
                'ends_at' => now()->addDays(18)->setTime(20, 0),
                'registration_opens_at' => now()->subDays(7),
                'registration_closes_at' => now()->addDays(17)->setTime(23, 59),
                'location_name' => 'Zoom / Live',
                'city' => 'En ligne',
                'country' => 'UEMOA',
                'online_platform' => 'zoom',
                'online_meeting_url' => 'https://zoom.us/j/81234567890',
                'online_meeting_id' => '812 3456 7890',
                'online_meeting_passcode' => 'ADF2026',
                'online_access_instructions' => 'Connectez-vous 10 minutes avant le début. Micro coupé à l’arrivée.',
                'capacity' => 300,
                'is_featured' => false,
                'status' => 'published',
            ],
            [
                'slug' => 'conference-perspectives-marches-uemoa-2026',
                'title' => 'Conférence : Perspectives des marchés UEMOA 2026',
                'description' => 'Analystes et gérants partagent leurs lectures sur les indices BRVM, le marché obligataire et les opportunités sectorielles.',
                'content' => '<p>Matinée d’analyses macro et micro, suivie d’un networking entre investisseurs et professionnels du marché.</p>',
                'category' => 'Conférence',
                'event_type' => 'hybrid',
                'starts_at' => now()->addDays(35)->setTime(9, 0),
                'ends_at' => now()->addDays(35)->setTime(13, 0),
                'registration_opens_at' => now()->subDays(14),
                'registration_closes_at' => now()->addDays(33)->setTime(23, 59),
                'location_name' => 'Hôtel du Lac',
                'location_address' => 'Boulevard de la Marina, Cotonou',
                'city' => 'Cotonou',
                'country' => 'Bénin',
                'online_platform' => 'zoom',
                'online_meeting_url' => 'https://zoom.us/j/89876543210',
                'online_meeting_id' => '898 7654 3210',
                'online_meeting_passcode' => 'UEMOA26',
                'online_access_instructions' => 'Accès hybride : salle Hôtel du Lac + Zoom pour les participants à distance.',
                'capacity' => 120,
                'is_featured' => false,
                'status' => 'draft',
            ],
            [
                'slug' => 'atelier-lecture-comptes-societes-cotees',
                'title' => 'Atelier : Lire les comptes des sociétés cotées',
                'description' => 'Atelier pratique pour décrypter bilans, comptes de résultat et indicateurs clés des entreprises BRVM.',
                'content' => '<p>Cas pratiques sur des titres de référence. Support PDF remis aux participants.</p>',
                'category' => 'Atelier',
                'event_type' => 'physical',
                'starts_at' => now()->addDays(50)->setTime(14, 0),
                'ends_at' => now()->addDays(50)->setTime(17, 30),
                'registration_opens_at' => now()->subDays(5),
                'registration_closes_at' => now()->addDays(48)->setTime(23, 59),
                'location_name' => 'Siège Africaine des Finances',
                'location_address' => 'Cot Agla c/3881, Cotonou',
                'city' => 'Cotonou',
                'country' => 'Bénin',
                'capacity' => 40,
                'is_featured' => false,
                'status' => 'draft',
            ],
            [
                'slug' => 'journee-education-financiere-abidjan-2025',
                'title' => 'Journée d’éducation financière — Abidjan',
                'description' => 'Événement passé : stands pédagogiques, quiz et conseils pour les particuliers investisseurs.',
                'content' => '<p>Retour sur une journée dédiée à la vulgarisation financière en Côte d’Ivoire.</p>',
                'category' => 'Éducation',
                'event_type' => 'physical',
                'starts_at' => now()->subMonths(2)->setTime(9, 0),
                'ends_at' => now()->subMonths(2)->setTime(17, 0),
                'registration_opens_at' => now()->subMonths(3),
                'registration_closes_at' => now()->subMonths(2)->subDay(),
                'location_name' => 'Palais de la Culture',
                'city' => 'Abidjan',
                'country' => 'Côte d’Ivoire',
                'capacity' => 500,
                'is_featured' => false,
                'status' => 'published',
            ],
        ];

        foreach ($extraEvents as $data) {
            Event::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'created_by' => $admin->id,
                    'seo_title' => $data['title'].' | Africaine des Finances',
                    'seo_description' => Str::limit(strip_tags($data['description']), 155),
                ])
            );
        }

        Event::whereIn('slug', [
            'conference-perspectives-marches-uemoa-2026',
            'atelier-lecture-comptes-societes-cotees',
        ])->update(['status' => 'draft', 'is_featured' => false]);

        $this->command->info('Event Seeder: Marathon 2026 + catalogue public créés avec succès !');
    }
}
