<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var array<int, array{platform: string, url: string, order: int}> */
    private const LINKS = [
        [
            'platform' => 'facebook',
            'url' => 'https://www.facebook.com/share/14mMJJEDv8P/?mibextid=wwXIfr',
            'order' => 1,
        ],
        [
            'platform' => 'instagram',
            'url' => 'https://www.instagram.com/africainedesfinances?igsi=MW1hMzVicDcxd3M3',
            'order' => 2,
        ],
        [
            'platform' => 'linkedin',
            'url' => 'https://www.linkedin.com/company/africaine-des-finances/',
            'order' => 3,
        ],
        [
            'platform' => 'tiktok',
            'url' => 'https://www.tiktok.com/@africainedesfinances?_r=1&_t=ZS-99GrnkEfSjQm',
            'order' => 4,
        ],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('social_links')) {
            return;
        }

        $now = now();

        foreach (self::LINKS as $link) {
            DB::table('social_links')->updateOrInsert(
                ['platform' => $link['platform']],
                [
                    'url' => $link['url'],
                    'is_active' => true,
                    'order' => $link['order'],
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('social_links')) {
            return;
        }

        DB::table('social_links')
            ->whereIn('platform', collect(self::LINKS)->pluck('platform')->all())
            ->delete();
    }
};
