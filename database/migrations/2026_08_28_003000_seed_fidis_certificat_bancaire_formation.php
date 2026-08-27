<?php

use App\Models\Formation;
use App\Models\FormationModule;
use App\Models\User;
use Database\Seeders\FidisCertificatBancaireSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('formations') || ! Schema::hasTable('formation_modules')) {
            return;
        }

        $admin = User::query()->first();
        if (! $admin) {
            return;
        }

        $formation = Formation::query()->updateOrCreate(
            ['slug' => FidisCertificatBancaireSeeder::SLUG],
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
                'prix' => 20000,
                'is_free' => false,
                'image_url' => 'assets/images/formations/fidis-certificat-bancaire.png',
                'statut' => 'publie',
                'published_at' => now(),
            ]
        );

        foreach (FidisCertificatBancaireSeeder::MODULES as $index => $titre) {
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

    public function down(): void
    {
        if (! Schema::hasTable('formations')) {
            return;
        }

        Formation::query()
            ->where('slug', FidisCertificatBancaireSeeder::SLUG)
            ->delete();
    }
};
