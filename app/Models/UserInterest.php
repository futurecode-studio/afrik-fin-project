<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInterest extends Model
{
    protected $fillable = [
        'user_id',
        'interest_key',
    ];

    /** Catalogue d’intérêts proposés à l’onboarding. */
    public static function catalog(): array
    {
        return [
            'actions' => ['label' => 'Actions BRVM', 'group' => 'Marchés', 'icon' => 'candlestick_chart'],
            'obligations' => ['label' => 'Obligations', 'group' => 'Marchés', 'icon' => 'receipt_long'],
            'fcp' => ['label' => 'FCP / OPCVM', 'group' => 'Marchés', 'icon' => 'account_balance'],
            'indices' => ['label' => 'Indices', 'group' => 'Marchés', 'icon' => 'monitoring'],
            'formations' => ['label' => 'Formations', 'group' => 'Apprendre', 'icon' => 'school'],
            'analyses' => ['label' => 'Analyses & actualités', 'group' => 'Apprendre', 'icon' => 'newspaper'],
            'evenements' => ['label' => 'Événements', 'group' => 'Apprendre', 'icon' => 'event'],
            'conseil' => ['label' => 'Conseil financier', 'group' => 'Services', 'icon' => 'support_agent'],
            'finance' => ['label' => 'Secteur Finance', 'group' => 'Secteurs', 'icon' => 'account_balance_wallet'],
            'telecom' => ['label' => 'Télécoms', 'group' => 'Secteurs', 'icon' => 'cell_tower'],
            'agriculture' => ['label' => 'Agriculture', 'group' => 'Secteurs', 'icon' => 'agriculture'],
            'industrie' => ['label' => 'Industrie', 'group' => 'Secteurs', 'icon' => 'factory'],
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
