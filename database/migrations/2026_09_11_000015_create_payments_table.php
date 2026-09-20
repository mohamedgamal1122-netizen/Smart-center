<?php
/**
 * جدول المدفوعات — payments
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete()->comment('الطالب');
            $table->foreignId('group_id')->nullable()->constrained('groups')->nullOnDelete()->comment('المجموعة');
            $table->unsignedTinyInteger('month')->comment('الشهر (1-12)');
            $table->unsignedSmallInteger('year')->comment('السنة');
            $table->decimal('required_amount', 10, 2)->default(0)->comment('المبلغ المطلوب');
            $table->decimal('discount', 10, 2)->default(0)->comment('الخصم');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('المبلغ المدفوع');
            $table->date('payment_date')->nullable()->comment('تاريخ الدفع');
            $table->enum('payment_method', ['cash', 'transfer', 'card', 'other'])->default('cash')->comment('طريقة الدفع');
            $table->string('receipt_number', 50)->unique()->comment('رقم الإيصال');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('أنشئ بواسطة');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->boolean('is_cancelled')->default(false)->comment('هل تم إلغاء الدفع');
            $table->text('cancel_reason')->nullable()->comment('سبب الإلغاء');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_id', 'month', 'year']);
            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
