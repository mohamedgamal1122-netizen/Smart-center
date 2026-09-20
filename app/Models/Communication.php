<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable = [
        'student_id',
        'parent_id',
        'user_id',
        'type',
        'subject',
        'description',
        'priority',
        'status',
        'followup_date',
        'attachment_path',
        'internal_notes',
    ];

    protected static function booted()
    {
        static::creating(function ($communication) {
            $communication->communication_number = 'COM-' . now()->format('Y') . '-' . str_pad(
                (Communication::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT
            );
            $communication->user_id = auth()->id() ?? $communication->user_id;
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}