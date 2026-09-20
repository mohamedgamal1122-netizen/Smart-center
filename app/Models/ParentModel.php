<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * نموذج ولي الأمر
 * الاسم ParentModel لتجنب التضارب مع الكلمة المحجوزة Parent في بعض السياقات
 * جدول قاعدة البيانات هو parents
 */
class ParentModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'name',
        'phone_primary',
        'phone_secondary',
        'relation',
        'email',
        'address',
        'notes',
    ];

    // ── العلاقات ──

    /** الطلاب المرتبطون بولي الأمر (many-to-many) */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
            ->withTimestamps();
    }
}
