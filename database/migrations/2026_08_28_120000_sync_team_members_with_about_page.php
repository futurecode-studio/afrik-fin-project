<?php

use App\Models\TeamMember;
use App\Support\TeamCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('team_members')) {
            return;
        }

        $keepNames = collect(TeamCatalog::members())->pluck('name')->all();

        TeamMember::query()
            ->whereNotIn('nom', $keepNames)
            ->delete();

        foreach (TeamCatalog::databaseRecords() as $record) {
            TeamMember::query()->updateOrCreate(
                ['nom' => $record['nom']],
                $record
            );
        }
    }

    public function down(): void
    {
        // Pas de restauration des anciens membres.
    }
};
