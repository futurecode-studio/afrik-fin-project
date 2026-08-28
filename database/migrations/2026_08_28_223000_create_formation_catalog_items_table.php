<?php

use App\Models\Formation;
use Database\Seeders\FidisCertificatBancaireSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formation_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('title')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (! Schema::hasTable('formations')) {
            return;
        }

        $formationId = Formation::query()
            ->where('slug', FidisCertificatBancaireSeeder::SLUG)
            ->value('id');

        if (! $formationId) {
            return;
        }

        $items = [
            ['title' => 'Responsable financier', 'image' => 'assets/images/formations/catalog/fidis/01-responsable-financier.png'],
            ['title' => 'Sécurité financière', 'image' => 'assets/images/formations/catalog/fidis/02-securite-financiere.png'],
            ['title' => 'Pratique de l’audit interne', 'image' => 'assets/images/formations/catalog/fidis/03-audit-interne-pratique.png'],
            ['title' => 'Contrôle de gestion', 'image' => 'assets/images/formations/catalog/fidis/04-controle-de-gestion.png'],
            ['title' => 'Audit interne & contrôle permanent', 'image' => 'assets/images/formations/catalog/fidis/05-audit-interne-controle-permanent.png'],
            ['title' => 'Analyse financière corporate', 'image' => 'assets/images/formations/catalog/fidis/06-analyse-financiere-corporate.png'],
        ];

        foreach ($items as $index => $item) {
            DB::table('formation_catalog_items')->insert([
                'formation_id' => $formationId,
                'image_path' => $item['image'],
                'title' => $item['title'],
                'caption' => null,
                'display_order' => $index + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('formation_catalog_items');
    }
};
