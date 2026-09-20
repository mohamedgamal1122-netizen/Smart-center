<?php
/**
 * جدول المدرسين — teachers
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('اسم المدرس');
            $table->string('phone', 20)->nullable()->comment('رقم الهاتف');
            $table->string('email')->nullable()->comment('البريد الإلكتروني');
            $table->string('specialization', 150)->nullable()->comment('التخصص');
            // نوع الحساب: بالحصة أو نسبة مئوية
            $table->enum('rate_type', ['per_session', 'percentage'])->default('per_session')->comment('نوع الحساب');
            $table->decimal('rate_value', 10, 2)->default(0)->comment('قيمة الحساب (سعر الحصة أو النسبة)');
            $table->date('start_date')->nullable()->comment('تاريخ البداية');
            $table->boolean('is_active')->default(true)->comment('هل المدرس نشط');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
