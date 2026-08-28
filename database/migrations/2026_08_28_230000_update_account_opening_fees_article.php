<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SLUG = 'comment-ouvrir-compte-titres-brvm-guide-pratique';

    /** @var array<int, array{old: string, new: string}> */
    private const REPLACEMENTS = [
        [
            'old' => '<li><strong>Frais d\'ouverture de compte</strong> : Souvent gratuits ou entre 5 000 et 25 000 FCFA</li>',
            'new' => '<li><strong>Frais d\'ouverture de compte</strong> : Gratuit chez la plupart des SGI ; 11 000 FCFA uniquement chez Africabourse</li>',
        ],
        [
            'old' => <<<'HTML'
<h2 class="text-2xl font-bold mt-8 mb-4">Liste des principales SGI par pays</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">Côte d'Ivoire</h3>
<ul class="list-disc pl-6 mb-4">
<li>CGF Bourse</li>
<li>Hudson & Cie</li>
<li>NSIA Finance</li>
<li>Atlantique Finance</li>
<li>BNI Finances</li>
</ul>

<h3 class="text-xl font-semibold mt-6 mb-3">Sénégal</h3>
<ul class="list-disc pl-6 mb-4">
<li>CGF Bourse Sénégal</li>
<li>Impaxis Securities</li>
<li>Africabourse</li>
</ul>

<h3 class="text-xl font-semibold mt-6 mb-3">Burkina Faso</h3>
<ul class="list-disc pl-6 mb-4">
<li>Coris Bourse</li>
<li>Fidelis Finance</li>
</ul>

<h3 class="text-xl font-semibold mt-6 mb-3">Autres pays</h3>
<p class="mb-4">Des SGI sont également présentes au Bénin, Mali, Niger et Togo.</p>
HTML,
            'new' => <<<'HTML'
<h2 class="text-2xl font-bold mt-8 mb-4">Liste des principales SGI par pays</h2>

<h3 class="text-xl font-semibold mt-6 mb-3">Bénin</h3>
<ul class="list-disc pl-6 mb-4">
<li>UCA</li>
<li>Africabourse</li>
<li>SGI Bénin</li>
<li>BFS</li>
<li>AGI</li>
<li>BOA</li>
</ul>

<h3 class="text-xl font-semibold mt-6 mb-3">Autres pays</h3>
<p class="mb-4">Des SGI sont également présentes en Côte d'Ivoire (CGF Bourse, Hudson &amp; Cie, NSIA Finance, Atlantique Finance, BNI Finances), au Sénégal (CGF Bourse Sénégal, Impaxis Securities), au Burkina Faso (Coris Bourse, Fidelis Finance), au Mali, au Niger et au Togo.</p>
HTML,
        ],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('articles')) {
            return;
        }

        $article = DB::table('articles')->where('slug', self::SLUG)->first();

        if (! $article) {
            return;
        }

        $contenu = $article->contenu;
        $changed = false;

        foreach (self::REPLACEMENTS as $replacement) {
            if (str_contains($contenu, $replacement['old'])) {
                $contenu = str_replace($replacement['old'], $replacement['new'], $contenu);
                $changed = true;
            }
        }

        if (! $changed) {
            return;
        }

        DB::table('articles')
            ->where('slug', self::SLUG)
            ->update([
                'contenu' => $contenu,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('articles')) {
            return;
        }

        $article = DB::table('articles')->where('slug', self::SLUG)->first();

        if (! $article) {
            return;
        }

        $contenu = $article->contenu;
        $changed = false;

        foreach (self::REPLACEMENTS as $replacement) {
            if (str_contains($contenu, $replacement['new'])) {
                $contenu = str_replace($replacement['new'], $replacement['old'], $contenu);
                $changed = true;
            }
        }

        if (! $changed) {
            return;
        }

        DB::table('articles')
            ->where('slug', self::SLUG)
            ->update([
                'contenu' => $contenu,
                'updated_at' => now(),
            ]);
    }
};
