<?php
/**
 * جدول نتائج الاختبارات — exam_results
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete()->comment('الاختبار');
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->comment('الطالب');
            $table->decimal('score', 8, 2)->comment('الدرجة المحققة');
            $table->string('grade', 10)->nullable()->comment('التقدير (ممتاز/جيد/...)');
            $table->unsignedInteger('rank')->nullable()->comment('الترتيب');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
