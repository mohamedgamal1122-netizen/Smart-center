<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * نموذج ولي الأمر — alias لـ ParentModel
 * يُستخدم الاسم Parent مباشرة في الكود، و ParentModel متاح أيضاً
 */
class Parent extends Model
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

    /** الطلاب المرتبطون بولي الأمر */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'parent_student', 'parent_id', 'student_id')
            ->withTimestamps();
    }
}
