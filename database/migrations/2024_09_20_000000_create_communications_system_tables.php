<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // جدول التواصلات الداخلية
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->string('communication_number')->unique(); // رقم التواصل الفريد
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // الموظف المسؤول
            $table->enum('type', ['call', 'message', 'meeting', 'complaint', 'inquiry', 'enrollment_request', 'reschedule_request', 'followup']);
            $table->string('subject')->nullable();
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['new', 'in_progress', 'contacted', 'resolved', 'closed'])->default('new');
            $table->date('followup_date')->nullable();
            $table->string('attachment_path')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });

        // جدول التذاكر
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // الموظف المخصص
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['technical', 'billing', 'academic', 'complaint', 'inquiry', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['new', 'in_progress', 'contacted', 'resolved', 'closed'])->default('new');
            $table->dateTime('last_reply_at')->nullable();
            $table->timestamps();
        });

        // جدول التعليقات الخاصة بالتذاكر
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('is_internal')->default(true);
            $table->text('comment');
            $table->timestamps();
        });

        // جدول المرفقات
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('path');
            $table->string('filename');
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });

        // جدول المهام
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('ticket_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('lead_id')->nullable()->constrained()->onDelete('set null');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['todo', 'in_progress', 'completed', 'cancelled'])->default('todo');
            $table->dateTime('reminder_at')->nullable();
            $table->boolean('is_reminded')->default(false);
            $table->timestamps();
        });

        // جدول العملاء المحتمين (Leads)
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('child_name')->nullable(); // اسم الطالب المحتمى
            $table->string('child_level')->nullable(); // المرحلة الدراسية
            $table->string('subject')->nullable(); // المادة المطلوبة
            $table->string('branch')->nullable(); // الفرع المطلوب
            $table->enum('source', ['referral', 'social_media', 'ad', 'walk_in', 'other'])->default('other');
            $table->text('notes')->nullable();
            $table->date('followup_date')->nullable();
            $table->string('status')->default('new'); // new, contacted, qualified, converted, rejected
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // الموظف المسؤول
            $table->timestamps();
        });

        // جدول نماذج الإشعارات
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('event_type')->unique(); // unique event handler
            $table->enum('channel', ['in_app', 'email', 'sms', 'whatsapp', 'push']);
            $table->string('subject_ar')->nullable();
            $table->string('subject_en')->nullable();
            $table->text('body_ar');
            $table->text('body_en');
            $table->text('available_variables')->nullable(); // JSON
            $table->boolean('is_active')->default(true);
            $table->string('role_target')->nullable(); // admin, receptionist, accountant, teacher, student, parent
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_comments');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('communications');
    }
};