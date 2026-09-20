<?php
/**
 * جدول المصروفات — expenses
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['rent', 'electricity', 'water', 'salaries', 'supplies', 'marketing', 'maintenance', 'other'])->default('other')->comment('فئة المصروف');
            $table->decimal('amount', 10, 2)->comment('المبلغ');
            $table->date('expense_date')->comment('تاريخ المصروف');
            $table->enum('payment_method', ['cash', 'transfer', 'card', 'other'])->default('cash')->comment('طريقة الدفع');
            $table->text('description')->nullable()->comment('الوصف');
            $table->string('receipt_path')->nullable()->comment('مسار الإيصال/المرفق');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('أنشئ بواسطة');
            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('expense_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
