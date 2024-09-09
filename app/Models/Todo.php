<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'task',
        'status',
    ];

    const STATUS_NOT_PENDING = 'PENDING';
    const STATUS_COMPLETED = 'COMPLETED';

    public static function statusOptions()
    {
        return [
            self::STATUS_NOT_PENDING => 'PENDING',
            self::STATUS_COMPLETED => 'COMPLETED',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
