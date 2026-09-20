<?php
/**
 * جدول الإشعارات الداخلية — notifications
 * ملاحظة: هذا جدول إشعارات مخصص للسنتر، يختلف عن جدول laravel الافتراضي.
 * إذا كان جدول notifications الافتراضي موجود، سيتم استخدامه مع تعديل.
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // احذف جدول notifications الافتراضي إن وُجد (من cache migration) لتجنب التضارب
        // في لارافيل 12 جدول notifications غير موجود افتراضياً، لكن نتأكد
        if (Schema::hasTable('notifications')) {
            Schema::dropIfExists('notifications');
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->comment('عنوان الإشعار');
            $table->text('message')->comment('نص الإشعار');
            $table->string('type', 50)->default('info')->comment('نوع الإشعار (info/warning/success/error)');
            $table->boolean('is_read')->default(false)->comment('هل تمت قراءته');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('المستخدم المستهدف (null = للجميع)');
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_read');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
