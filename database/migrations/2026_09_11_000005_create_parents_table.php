<?php
/**
 * جدول أولياء الأمور — parents
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('اسم ولي الأمر');
            $table->string('phone_primary', 20)->comment('رقم الهاتف الأساسي');
            $table->string('phone_secondary', 20)->nullable()->comment('رقم هاتف ثانوي');
            $table->string('relation', 50)->nullable()->comment('صلة القرابة (أب/أم/...)');
            $table->string('email')->nullable()->comment('البريد الإلكتروني');
            $table->text('address')->nullable()->comment('العنوان');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
