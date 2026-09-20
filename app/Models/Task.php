<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'assigned_to',
        'created_by',
        'student_id',
        'parent_id',
        'ticket_id',
        'lead_id',
        'description',
        'due_date',
        'priority',
        'status',
        'reminder_at',
        'is_reminded',
    ];

    protected $casts = [
        'due_date' => 'date',
        'reminder_at' => 'datetime',
        'is_reminded' => 'boolean',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parent::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}