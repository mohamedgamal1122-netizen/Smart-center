<?php
/**
 * جدول الحصص — class_sessions
 * (الاسم class_sessions لتجنب التضارب مع جدول sessions الخاص بالمصادقة)
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete()->comment('المجموعة');
            $table->date('date')->comment('تاريخ الحصة');
            $table->time('start_time')->nullable()->comment('وقت البداية');
            $table->time('end_time')->nullable()->comment('وقت النهاية');
            $table->enum('status', ['completed', 'cancelled', 'postponed', 'makeup'])->default('completed')->comment('حالة الحصة');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();

            $table->index('group_id');
            $table->index('date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
