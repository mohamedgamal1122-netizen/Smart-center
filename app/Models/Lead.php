<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'child_name',
        'child_level',
        'subject',
        'branch',
        'source',
        'notes',
        'followup_date',
        'status',
        'user_id',
    ];

    protected $casts = [
        'followup_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}