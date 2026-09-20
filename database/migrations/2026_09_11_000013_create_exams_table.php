<?php
/**
 * جدول الاختبارات — exams
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->comment('عنوان الاختبار');
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete()->comment('المجموعة');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete()->comment('المادة');
            $table->date('exam_date')->nullable()->comment('تاريخ الاختبار');
            $table->decimal('max_score', 8, 2)->default(100)->comment('الدرجة العظمى');
            $table->enum('exam_type', ['quiz', 'monthly', 'final'])->default('quiz')->comment('نوع الاختبار');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
