<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property string $status
 */
class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'type', 
        'start_date', 
        'end_date', 
        'reason', 
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
