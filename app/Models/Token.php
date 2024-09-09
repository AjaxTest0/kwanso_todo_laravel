<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow Laravel's naming conventions
    protected $table = 'user_tokens';

    // Specify the primary key field if it's not 'id'
    protected $primaryKey = 'id';

    // Disable auto-incrementing if the primary key is not an auto-incrementing integer
    public $incrementing = false;

    // Disable timestamps if you don't have created_at and updated_at columns
    public $timestamps = true;

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'email',
        'is_used',
        'token',
        'expiry',
    ];

    // Cast the expiry attribute to a DateTime instance
    protected $casts = [
        'expiry' => 'datetime',
        'is_used' => 'boolean',
    ];
}
