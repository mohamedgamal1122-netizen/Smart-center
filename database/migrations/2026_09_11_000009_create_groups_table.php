<?php
/**
 * جدول المجموعات — groups
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('اسم المجموعة');
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete()->comment('المادة');
            $table->string('grade', 100)->nullable()->comment('الصف الدراسي');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete()->comment('المدرس');
            $table->string('room', 50)->nullable()->comment('القاعة / الغرفة');
            $table->json('days')->nullable()->comment('أيام الحصص [Saturday, Monday, ...]');
            $table->time('start_time')->nullable()->comment('وقت البداية');
            $table->time('end_time')->nullable()->comment('وقت النهاية');
            $table->unsignedInteger('max_students')->default(30)->comment('الحد الأقصى للطلاب');
            $table->decimal('monthly_fee', 10, 2)->default(0)->comment('الرسوم الشهرية');
            $table->date('start_date')->nullable()->comment('تاريخ بداية المجموعة');
            $table->enum('status', ['open', 'full', 'paused'])->default('open')->comment('حالة المجموعة');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();
            $table->softDeletes();

            $table->index('subject_id');
            $table->index('teacher_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
