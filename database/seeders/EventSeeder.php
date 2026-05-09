<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventDocument;
use App\Models\EventGallery;
use App\Models\EventProduct;
use App\Models\EventProductVariant;
use App\Models\EventProgramItem;
use App\Models\EventSpeaker;
use App\Models\EventTicketType;
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
                'starts_at' => '2026-06-20 06:30:00',
                'ends_at' => '2026-06-20 11:30:00',
                'registration_opens_at' => '2026-01-01 00:00:00',
                'registration_closes_at' => '2026-06-18 23:59:59',
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

        // Speakers / Intervenants
        $speakers = [
            ['name' => 'Coach Sportif Principal', 'role' => 'Coach sportif & échauffement', 'bio' => 'Coach certifié en activité physique et préparation mentale.', 'company' => 'AASCOT BRVM BENIN', 'display_order' => 1],
            ['name' => 'Nutritionniste UEMOA', 'role' => 'Atelier nutrition', 'bio' => 'Expert en nutrition appliquée aux cadres et professions sédentaires.', 'company' => 'ONG Santé & Nutrition', 'display_order' => 2],
            ['name' => 'Médecin coordonnateur', 'role' => 'Stand médical & constantes', 'bio' => 'Médecin spécialisé en médecine du sport et prévention cardiovasculaire.', 'company' => 'Centre Médical Partenaire', 'display_order' => 3],
            ['name' => 'Responsable Bien-être ADF', 'role' => 'Modérateur & networking', 'bio' => 'Chargé du programme bien-être et de la cohésion institutionnelle.', 'company' => 'Africaine des Finances', 'display_order' => 4],
        ];

        foreach ($speakers as $s) {
            EventSpeaker::firstOrCreate(
                ['event_id' => $event->id, 'name' => $s['name']],
                array_merge($s, ['event_id' => $event->id])
            );
        }

        // Types de billets
        $ticketTypes = [
            ['name' => 'Marcheur Standard', 'description' => 'Inscription gratuite avec accès au parcours 10 km, échauffement collectif et rafraîchissements.', 'price' => 0, 'quantity' => 150, 'display_order' => 1],
            ['name' => 'Pack VIP + Kit', 'description' => 'Accès complet + T-shirt officiel + casquette + goodies + priorité stand médical.', 'price' => 10000, 'quantity' => 50, 'display_order' => 2],
        ];

        foreach ($ticketTypes as $tt) {
            EventTicketType::firstOrCreate(
                ['event_id' => $event->id, 'name' => $tt['name']],
                array_merge($tt, ['event_id' => $event->id])
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

        // Produits dérivés
        $products = [
            [
                'name' => 'T-shirt Marathon ADF',
                'description' => 'T-shirt technique respirant 100% polyester, logo ADF sérigraphié.',
                'price' => 5000,
                'is_active' => true,
                'variants' => [
                    ['variant_name' => 'S', 'size' => 'S', 'stock_quantity' => 30],
                    ['variant_name' => 'M', 'size' => 'M', 'stock_quantity' => 40],
                    ['variant_name' => 'L', 'size' => 'L', 'stock_quantity' => 40],
                    ['variant_name' => 'XL', 'size' => 'XL', 'stock_quantity' => 20],
                    ['variant_name' => 'XXL', 'size' => 'XXL', 'stock_quantity' => 10],
                ],
            ],
            [
                'name' => 'Casquette ADF',
                'description' => 'Casquette snapback brodée logo ADF, ajustable.',
                'price' => 3500,
                'is_active' => true,
                'variants' => [
                    ['variant_name' => 'Unique', 'size' => 'Unique', 'stock_quantity' => 50],
                ],
            ],
            [
                'name' => 'Porte-clé ADF',
                'description' => 'Porte-clé métallique avec logo ADF et numéro d\'édition limitée.',
                'price' => 1500,
                'is_active' => true,
                'variants' => [
                    ['variant_name' => 'Unique', 'size' => 'Unique', 'stock_quantity' => 100],
                ],
            ],
        ];

        foreach ($products as $p) {
            $variants = $p['variants'];
            unset($p['variants']);
            $product = EventProduct::firstOrCreate(
                ['event_id' => $event->id, 'name' => $p['name']],
                array_merge($p, ['event_id' => $event->id])
            );
            foreach ($variants as $v) {
                EventProductVariant::firstOrCreate(
                    ['product_id' => $product->id, 'variant_name' => $v['variant_name']],
                    array_merge($v, ['product_id' => $product->id, 'sku' => strtoupper(Str::slug($product->name)) . '-' . $v['variant_name']])
                );
            }
        }

        $this->command->info('Event Seeder: Marathon 2026 créé avec succès !');
    }
}
