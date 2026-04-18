<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property string $status
 * @property string|null $rejection_reason
 */
class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 
        'type', 
        'start_date', 
        'end_date', 
        'reason', 
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
