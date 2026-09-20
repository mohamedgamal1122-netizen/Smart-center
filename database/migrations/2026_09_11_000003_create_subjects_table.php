<?php
/**
 * جدول المواد الدراسية — subjects
 * يخزن المواد مع المرحلة وحالة التفعيل
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('اسم المادة');
            $table->string('stage', 100)->nullable()->comment('المرحلة الدراسية');
            $table->text('description')->nullable()->comment('وصف المادة');
            $table->boolean('is_active')->default(true)->comment('هل المادة نشطة');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
