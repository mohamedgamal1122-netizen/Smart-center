<?php
/**
 * جدول تسجيل الطالب في مجموعة — enrollments
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->comment('الطالب');
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete()->comment('المجموعة');
            $table->date('enrollment_date')->nullable()->comment('تاريخ التسجيل');
            $table->decimal('agreed_fee', 10, 2)->default(0)->comment('الرسوم المتفق عليها');
            $table->decimal('discount', 10, 2)->default(0)->comment('الخصم');
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active')->comment('حالة التسجيل');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();

            $table->unique(['student_id', 'group_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
