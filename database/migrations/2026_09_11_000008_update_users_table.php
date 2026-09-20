<?php
/**
 * تعديل جدول المستخدمين — إضافة الدور والهاتف وحالة النشاط
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'receptionist', 'accountant', 'teacher'])->default('receptionist')->after('password')->comment('دور المستخدم');
            $table->string('phone', 20)->nullable()->after('role')->comment('رقم الهاتف');
            $table->boolean('is_active')->default(true)->after('phone')->comment('هل الحساب نشط');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'is_active']);
        });
    }
};
