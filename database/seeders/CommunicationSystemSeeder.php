<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\DB;

class CommunicationSystemSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'new_student_registration',
                'event_type' => 'new_student_registration',
                'channel' => 'in_app',
                'subject_ar' => 'تسجيل طالب جديد',
                'subject_en' => 'New Student Registration',
                'body_ar' => 'تم تسجيل طالب جديد: {{student_name}} في المجموعة {{group_name}}',
                'body_en' => 'A new student has been registered: {{student_name}} in group {{group_name}}',
                'available_variables' => json_encode(['student_name', 'group_name', 'report_url']),
                'is_active' => true,
                'role_target' => 'admin',
                'description' => 'يُرسل عند تسجيل طالب جديد',
            ],
            [
                'name' => 'lesson_reminder',
                'event_type' => 'lesson_reminder',
                'channel' => 'in_app',
                'subject_ar' => 'تذكير بوجودة درس',
                'subject_en' => 'Lesson Reminder',
                'body_ar' => 'تذكير: عندكوا درس غداً {{lesson_date}} الساعة {{lesson_time}} للمجموعة {{group_name}}',
                'body_en' => 'Reminder: You have a lesson tomorrow {{lesson_date}} at {{lesson_time}} for group {{group_name}}',
                'available_variables' => json_encode(['student_name', 'group_name', 'lesson_date', 'lesson_time']),
                'is_active' => true,
                'role_target' => 'student,parent',
                'description' => 'تذكير بوجودة درس قادم',
            ],
            [
                'name' => 'payment_due_reminder',
                'event_type' => 'payment_due_reminder',
                'channel' => 'in_app',
                'subject_ar' => 'تذكير بسداد المدفوعات',
                'subject_en' => 'Payment Due Reminder',
                'body_ar' => 'لديكم مبلغ مستحق: {{amount}} بتاريخ {{due_date}}',
                'body_en' => 'You have an outstanding amount: {{amount}} due on {{due_date}}',
                'available_variables' => json_encode(['parent_name', 'student_name', 'amount', 'due_date', 'payment_url']),
                'is_active' => true,
                'role_target' => 'parent',
                'description' => 'تذكير باستحقاق دفع',
            ],
            [
                'name' => 'new_exam_result',
                'event_type' => 'new_exam_result',
                'channel' => 'in_app',
                'subject_ar' => 'نتيجة اختبار جديدة',
                'subject_en' => 'New Exam Result',
                'body_ar' => 'تم رفع نتيجة اختبار {{exam_name}} للطالب {{student_name}}: {{score}}%',
                'body_en' => 'New exam result uploaded: {{exam_name}} for student {{student_name}}: {{score}}%',
                'available_variables' => json_encode(['student_name', 'exam_name', 'score', 'report_url']),
                'is_active' => true,
                'role_target' => 'parent',
                'description' => 'عند رفع نتيجة اختبار',
            ],
            [
                'name' => 'student_absence',
                'event_type' => 'student_absence',
                'channel' => 'in_app',
                'subject_ar' => 'غياب الطالب',
                'subject_en' => 'Student Absence',
                'body_ar' => 'الطالب {{student_name}} غاب عن درس المجموعة {{group_name}} في تاريخ {{lesson_date}}',
                'body_en' => 'Student {{student_name}} was absent from group {{group_name}} lesson on {{lesson_date}}',
                'available_variables' => json_encode(['student_name', 'group_name', 'lesson_date']),
                'is_active' => true,
                'role_target' => 'parent',
                'description' => 'عند غياب طالب',
            ],
            [
                'name' => 'ticket_created',
                'event_type' => 'ticket_created',
                'channel' => 'in_app',
                'subject_ar' => 'تم إنشاء تذكرة دعم',
                'subject_en' => 'Support Ticket Created',
                'body_ar' => 'تم إنشاء تذكرة دعم جديدة (#{{ticket_number}}) بعنوان "{{ticket_title}}"',
                'body_en' => 'A new support ticket (#{{ticket_number}}) has been created with title "{{ticket_title}}"',
                'available_variables' => json_encode(['ticket_number', 'ticket_title']),
                'is_active' => true,
                'role_target' => 'admin,user',
                'description' => 'عند إنشاء تذكرة دعم',
            ],
            [
                'name' => 'task_assigned',
                'event_type' => 'task_assigned',
                'channel' => 'in_app',
                'subject_ar' => 'تم تعيين مهمة لك',
                'subject_en' => 'New Task Assigned',
                'body_ar' => 'تم تعيين مهمة جديدة لك: {{task_title}} - الموعد النهائي: {{due_date}}',
                'body_en' => 'A new task has been assigned to you: {{task_title}} - Due date: {{due_date}}',
                'available_variables' => json_encode(['task_title', 'due_date']),
                'is_active' => true,
                'role_target' => 'user',
                'description' => 'عند تعيين مهمة',
            ],
            [
                'name' => 'lead_assigned',
                'event_type' => 'lead_assigned',
                'channel' => 'in_app',
                'subject_ar' => 'تم تعيين عميل محتمى جديد',
                'subject_en' => 'New Lead Assigned',
                'body_ar' => 'تم تعيين عميل محتمى جديد لك: {{lead_name}} ({{source}})',
                'body_en' => 'A new lead has been assigned to you: {{lead_name}} ({{source}})',
                'available_variables' => json_encode(['lead_name', 'source']),
                'is_active' => true,
                'role_target' => 'user',
                'description' => 'عند تعيين عميل محتمى',
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::create($template);
        }
    }
}