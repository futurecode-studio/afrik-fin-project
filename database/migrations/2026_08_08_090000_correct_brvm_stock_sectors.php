<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('stocks')->where('symbol', 'BIIC')->update(['sector' => 'Finance']);
        DB::table('stocks')->where('symbol', 'NTLC')->update(['sector' => 'Industrie']);
    }

    public function down(): void
    {
        DB::table('stocks')->where('symbol', 'BIIC')->update(['sector' => null]);
        DB::table('stocks')->where('symbol', 'NTLC')->update(['sector' => null]);
    }
};
