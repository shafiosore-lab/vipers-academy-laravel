<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportLog extends Model
{
    protected $fillable = [
        'user_id',
        'export_type',
        'report_name',
        'file_format',
        'file_path',
        'file_size',
        'record_count',
        'email_recipients',
        'ip_address',
        'status',
        'error_message',
    ];

    protected $casts = [
        'email_recipients' => 'array',
        'file_size' => 'integer',
        'record_count' => 'integer',
    ];

    /**
     * Get the user who performed the export
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful exports
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed exports
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Log a new export activity
     */
    public static function logExport(array $data): self
    {
        return self::create([
            'user_id' => $data['user_id'] ?? null,
            'export_type' => $data['export_type'],
            'report_name' => $data['report_name'],
            'file_format' => $data['file_format'],
            'file_path' => $data['file_path'] ?? null,
            'file_size' => $data['file_size'] ?? null,
            'record_count' => $data['record_count'] ?? 0,
            'email_recipients' => $data['email_recipients'] ?? null,
            'ip_address' => $data['ip_address'] ?? request()->ip(),
            'status' => $data['status'],
            'error_message' => $data['error_message'] ?? null,
        ]);
    }
}
