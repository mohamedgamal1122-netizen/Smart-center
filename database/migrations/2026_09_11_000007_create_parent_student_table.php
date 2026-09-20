<?php
/**
 * جدول الربط بين أولياء الأمور والطلاب — parent_student
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete()->comment('ولي الأمر');
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->comment('الطالب');
            $table->timestamps();

            $table->unique(['parent_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_student');
    }
};
