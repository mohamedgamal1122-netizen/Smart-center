<?php
/**
 * جدول سجل التدقيق — audit_logs
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('المستخدم');
            $table->string('action', 50)->comment('الحدث (created/updated/deleted/...)');
            $table->string('model_type')->nullable()->comment('نوع النموذج');
            $table->unsignedBigInteger('model_id')->nullable()->comment('معرف السجل');
            $table->json('old_values')->nullable()->comment('القيم القديمة');
            $table->json('new_values')->nullable()->comment('القيم الجديدة');
            $table->string('ip', 45)->nullable()->comment('عنوان IP');
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
