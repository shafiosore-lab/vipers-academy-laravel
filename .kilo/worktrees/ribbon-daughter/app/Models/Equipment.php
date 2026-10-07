<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'sku',
        'quantity',
        'min_quantity',
        'condition',
        'purchase_date',
        'purchase_price',
        'sponsor',
        'sponsor_compliant',
        'expiry_date',
        'location',
        'status',
        'organization_id',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'purchase_price' => 'decimal:2',
        'sponsor_compliant' => 'boolean',
    ];

    // Status constants
    const STATUS_AVAILABLE = 'available';
    const STATUS_DISTRIBUTED = 'distributed';
    const STATUS_RESERVED = 'reserved';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_RETIRED = 'retired';

    // Condition constants
    const CONDITION_NEW = 'new';
    const CONDITION_GOOD = 'good';
    const CONDITION_FAIR = 'fair';
    const CONDITION_POOR = 'poor';
    const CONDITION_DAMAGED = 'damaged';

    /**
     * Get the category this equipment belongs to
     */
    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'category_id');
    }

    /**
     * Get distributions for this equipment
     */
    public function distributions()
    {
        return $this->hasMany(EquipmentDistribution::class);
    }

    /**
     * Get organization
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Scope for available equipment
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope for low stock items
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= min_quantity');
    }

    /**
     * Scope for sponsor compliant items
     */
    public function scopeSponsorCompliant($query)
    {
        return $query->where('sponsor_compliant', true);
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_AVAILABLE => 'Available',
            self::STATUS_DISTRIBUTED => 'Distributed',
            self::STATUS_RESERVED => 'Reserved',
            self::STATUS_MAINTENANCE => 'Under Maintenance',
            self::STATUS_RETIRED => 'Retired',
        ];
    }

    /**
     * Get condition options
     */
    public static function getConditionOptions()
    {
        return [
            self::CONDITION_NEW => 'New',
            self::CONDITION_GOOD => 'Good',
            self::CONDITION_FAIR => 'Fair',
            self::CONDITION_POOR => 'Poor',
            self::CONDITION_DAMAGED => 'Damaged',
        ];
    }

    /**
     * Check if equipment needs restocking
     */
    public function needsRestocking()
    {
        return $this->quantity <= $this->min_quantity;
    }

    /**
     * Get distributed quantity
     */
    public function getDistributedQuantity()
    {
        return $this->distributions()
            ->where('status', 'active')
            ->sum('quantity');
    }

    /**
     * Get available quantity
     */
    public function getAvailableQuantity()
    {
        return $this->quantity - $this->getDistributedQuantity();
    }

    /**
     * Check if equipment is expiring soon
     */
    public function isExpiringSoon($days = 30)
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->diffInDays(now()) <= $days;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            self::STATUS_AVAILABLE => 'success',
            self::STATUS_DISTRIBUTED => 'primary',
            self::STATUS_RESERVED => 'warning',
            self::STATUS_MAINTENANCE => 'info',
            self::STATUS_RETIRED => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get condition badge class
     */
    public function getConditionBadgeClass()
    {
        return match($this->condition) {
            self::CONDITION_NEW => 'success',
            self::CONDITION_GOOD => 'info',
            self::CONDITION_FAIR => 'warning',
            self::CONDITION_POOR => 'danger',
            self::CONDITION_DAMAGED => 'dark',
            default => 'secondary',
        };
    }
}
