<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $department_id
 * @property string $employee_id
 * @property string|null $phone
 * @property string $position
 * @property \Illuminate\Support\Carbon $date_hired
 * @property string $status
 * @property string|null $address
 * @property string|null $profile_photo
 * @property \App\Models\User $user
 * @property \App\Models\Department|null $department
 */
class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'department_id',
        'employee_id',
        'phone',
        'position',
        'date_hired',
        'status',
        'address',
        'profile_photo',
    ];

    protected $casts = [
        'date_hired' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
