<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'preferred_notification_channel',
    ];

    // Relationships
    public function players()
    {
        return $this->hasMany(Player::class, 'guardian_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Methods
    public function getTotalOutstandingBalance()
    {
        $total = 0;
        foreach ($this->players as $player) {
            $total += $player->getCurrentOutstandingBalance();
        }
        return $total;
    }

    public function getPlayersWithBalances()
    {
        $players = [];
        foreach ($this->players as $player) {
            $balance = $player->getCurrentOutstandingBalance();
            if ($balance > 0) {
                $players[] = [
                    'player' => $player,
                    'balance' => $balance,
                    'monthly_fee' => $player->fee_category === 'A' ? 200 : 500,
                ];
            }
        }
        return $players;
    }

    public function shouldReceiveReminder()
    {
        return $this->getTotalOutstandingBalance() > 0;
    }
}
