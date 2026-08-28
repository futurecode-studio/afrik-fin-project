<?php

namespace Database\Seeders;

use App\Models\Formation;
use App\Models\FormationModule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FidisCertificatBancaireSeeder extends Seeder
{
    public const SLUG = 'certificat-professionnel-technique-bancaire-financiere';

    /** @var list<string> */
    public const MODULES = [
        'Gestion Bancaire',
        'Analyse Financière Appliquée',
        'Conformité & Réglementation Bancaire',
        'Technique de vente',
        'Technique de Recouvrement',
        'Structuration de Dossiers de crédit (TPE/ME/CORPORATE)',
        'Introduction à la Gestion des Risques Bancaires (Crédit et Opérationnels)',
    ];

    public function run(): void
    {
        $admin = User::query()->first();
        if (! $admin) {
            $this->command?->warn('Aucun utilisateur — formation Fidis non créée.');

            return;
        }

        $formation = Formation::query()->updateOrCreate(
            ['slug' => self::SLUG],
            [
                'user_id' => $admin->id,
                'titre' => 'Certificat professionnel technique bancaire & financière',
                'description_courte' => '<p>Programme certifiant Fidis Invest (Promo 42) — technique bancaire et financière, agréé FDFP.</p>',
                'description_complete' => '<h2>Certificat professionnel technique bancaire &amp; financière</h2>
<p>Formation professionnelle proposée par <strong>Fidis Invest</strong> (Finances – Investissement – Distribution), en partenariat avec Africaine des Finances, KSBC et CEFOPEP.</p>
<p>Programme <strong>Promo 42</strong>, orienté métiers bancaires et financiers : gestion, analyse, conformité, vente, recouvrement, structuration de crédit et gestion des risques.</p>
<p><strong>Agréé FDFP</strong> — éligible aux dispositifs de financement de la formation professionnelle.</p>',
                'niveau' => 'intermediaire',
                'duree' => 'Programme certifiant',
                'prix' => 0,
                'price_label' => 'Voir catalogue',
                'is_free' => false,
                'image_url' => 'assets/images/formations/fidis-certificat-bancaire.png',
                'statut' => 'publie',
                'published_at' => now(),
            ]
        );

        foreach (self::MODULES as $index => $titre) {
            FormationModule::query()->updateOrCreate(
                [
                    'formation_id' => $formation->id,
                    'slug' => Str::slug($titre),
                ],
                [
                    'titre' => $titre,
                    'description' => null,
                    'ordre' => $index + 1,
                    'duree_estimee' => null,
                    'is_active' => true,
                ]
            );
        }
    }
}
