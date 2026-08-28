<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use App\Support\TeamCatalog;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        foreach (TeamCatalog::databaseRecords() as $record) {
            TeamMember::query()->updateOrCreate(
                ['nom' => $record['nom']],
                $record
            );
        }

        $keepNames = collect(TeamCatalog::members())->pluck('name')->all();

        TeamMember::query()
            ->whereNotIn('nom', $keepNames)
            ->delete();
    }
}
