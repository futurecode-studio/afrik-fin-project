<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE investment_appointments MODIFY COLUMN investment_type ENUM('actions_brvm', 'obligations', 'fcp', 'gestion_mandat', 'institutionnel', 'mise_en_relation') NOT NULL COMMENT 'Type de demande'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE investment_appointments MODIFY COLUMN investment_type ENUM('actions_brvm', 'obligations', 'fcp') NOT NULL COMMENT 'Type d\\'investissement'");
    }
};
