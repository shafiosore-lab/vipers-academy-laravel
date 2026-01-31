<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'ip_address',
        'user_agent',
        'gps_location',
        'metadata'
    ];

    protected $casts = [
        'gps_location' => 'array',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $model = null, $modelId = null, $metadata = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'gps_location' => request()->header('X-GPS-Location') ? json_decode(request()->header('X-GPS-Location'), true) : null,
            'metadata' => $metadata
        ]);
    }
}
