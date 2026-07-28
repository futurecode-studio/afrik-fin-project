<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ag_votes');
        Schema::dropIfExists('ag_resolutions');
        Schema::dropIfExists('ag_meetings');
    }

    public function down(): void
    {
        // Feature retirée — pas de recreation.
    }
};
