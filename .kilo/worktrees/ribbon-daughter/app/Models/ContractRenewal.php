<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ContractRenewal Model
 *
 * Tracks contract renewals
 */
class ContractRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_contract_id',
        'previous_contract_id',
        'new_contract_id',
        'salary_increase',
        'salary_increase_percentage',
        'renewal_type',
        'notes',
    ];

    protected $casts = [
        'salary_increase' => 'decimal:2',
        'salary_increase_percentage' => 'decimal:2',
    ];

    // Renewal type constants
    const TYPE_AUTOMATIC = 'automatic';
    const TYPE_NEGOTIATED = 'negotiated';
    const TYPE_UPGRADE = 'upgrade';

    /**
     * Get renewal type options
     */
    public static function getRenewalTypes(): array
    {
        return [
            self::TYPE_AUTOMATIC => 'Automatic Renewal',
            self::TYPE_NEGOTIATED => 'Negotiated Renewal',
            self::TYPE_UPGRADE => 'Contract Upgrade',
        ];
    }

    // Relationships
    public function playerContract(): BelongsTo
    {
        return $this->belongsTo(PlayerContract::class, 'player_contract_id');
    }

    public function previousContract(): BelongsTo
    {
        return $this->belongsTo(PlayerContract::class, 'previous_contract_id');
    }

    public function newContract(): BelongsTo
    {
        return $this->belongsTo(PlayerContract::class, 'new_contract_id');
    }

    /**
     * Get renewals for a player
     */
    public static function getHistoryForPlayer(int $playerId): \Illuminate\Database\Eloquent\Collection
    {
        return self::whereHas('previousContract', function ($query) use ($playerId) {
            $query->where('player_id', $playerId);
        })->with(['previousContract', 'newContract'])
          ->orderBy('created_at', 'desc')
          ->get();
    }
}
