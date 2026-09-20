<?php
/**
 * جدول الحضور — attendances
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('class_sessions')->cascadeOnDelete()->comment('الحصة');
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->comment('الطالب');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present')->comment('حالة الحضور');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();

            $table->unique(['session_id', 'student_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
