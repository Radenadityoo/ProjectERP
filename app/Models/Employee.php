<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string|null $employee_id
 * @property string $name
 * @property string|null $gender
 * @property \Illuminate\Support\Carbon|null $dob
 * @property string|null $national_id
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $personal_email
 * @property string|null $job_title
 * @property string|null $department
 * @property int|null $manager_id
 * @property string|null $work_email
 * @property string|null $work_phone
 * @property string|null $employment_type
 * @property \Illuminate\Support\Carbon|null $hire_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string|null $status
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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
