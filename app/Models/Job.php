<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'career_jobs';

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'salary',
        'location',
        'type',
        'department',
        'application_deadline',
        'status',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }
}
