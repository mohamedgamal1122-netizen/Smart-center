<?php
/**
 * جدول الطلاب — students
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique()->comment('كود الطالب الفريد');
            $table->string('name', 150)->comment('اسم الطالب');
            $table->date('birth_date')->nullable()->comment('تاريخ الميلاد');
            $table->enum('gender', ['male', 'female'])->nullable()->comment('النوع');
            $table->string('grade', 100)->nullable()->comment('الصف الدراسي');
            $table->string('school', 150)->nullable()->comment('المدرسة');
            $table->string('phone', 20)->nullable()->comment('رقم الهاتف');
            $table->enum('status', ['active', 'paused', 'withdrawn'])->default('active')->comment('حالة الطالب');
            $table->date('enrollment_date')->nullable()->comment('تاريخ التسجيل');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->string('photo')->nullable()->comment('مسار صورة الطالب');
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('status');
            $table->index('grade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
