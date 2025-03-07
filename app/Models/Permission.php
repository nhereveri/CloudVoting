<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'can_vote',
        'is_supervisor',
        'is_admin',
    ];

    protected $casts = [
        'can_vote' => 'boolean',
        'is_supervisor' => 'boolean',
        'is_admin' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}