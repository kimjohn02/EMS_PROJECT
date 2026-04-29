<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


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
