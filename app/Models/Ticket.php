<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'student_id',
        'parent_id',
        'user_id',
        'assigned_to',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'last_reply_at',
    ];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            $ticket->ticket_number = 'TKT-' . now()->format('Y') . '-' . str_pad(
                (Ticket::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT
            );
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

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
}