<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webhook extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'url',
        'events',
        'organization_id',
        'secret',
        'enabled',
        'retry_attempts',
        'timeout',
        'headers',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'events' => 'array',
        'headers' => 'array',
        'enabled' => 'boolean',
    ];

    /**
     * Get the organization that owns the webhook.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the logs for the webhook.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(WebhookLog::class);
    }

    /**
     * Scope a query to only include enabled webhooks.
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Scope a query to only include webhooks for a specific event.
     */
    public function scopeForEvent($query, string $event)
    {
        return $query->whereJsonContains('events', $event);
    }

    /**
     * Check if webhook is configured for a specific event.
     */
    public function hasEvent(string $event): bool
    {
        return in_array($event, $this->events ?? []);
    }
}
