<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'applicant_name',
        'email',
        'phone',
        'resume_path',
        'cover_letter',
        'status',
        'applied_at',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
