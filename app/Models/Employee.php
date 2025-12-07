<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'gender',
        'dob',
        'national_id',
        'address',
        'phone',
        'personal_email',
        'job_title',
        'department',
        'manager_id',
        'work_email',
        'work_phone',
        'employment_type',
        'hire_date',
        'end_date',
        'status',
        'profile_photo_path',
    ];

    protected $casts = [
        'dob' => 'date',
        'hire_date' => 'date',
        'end_date' => 'date',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }
}
