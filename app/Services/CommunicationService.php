<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Str;

class CommunicationService
{
    /**
     * Send a notification based on an event type
     */
    public static function sendNotification(
        string $eventType,
        array $variables = [],
        array $userIds = null,
        ?string $locale = null
    ): void {
        // الحصول على قالب الإشعار
        $template = NotificationTemplate::where('event_type', $eventType)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return;
        }

        // تحديد اللغة
        $locale = $locale ?? app()->getLocale();

        // استبدال المتغيرات
        $body = self::interpolate($template->getBody($locale), $variables);
        $subject = self::interpolate($template->getSubject($locale) ?? '', $variables);

        // الحصول على المستخدمين المستهدفين
        $users = collect();
        if ($userIds) {
            $users = User::whereIn('id', $userIds)->get();
        } else {
            // إذا لم يتم تحديد مستخدمين، استخدم الدور المستهدف
            $roles = explode(',', $template->role_target);
            $users = User::whereHas('roles', function ($q) use ($roles) {
                $q->whereIn('name', $roles);
            })->get();
        }

        foreach ($users as $user) {
            $user->notify(new SystemNotification($subject, $body, $eventType, $variables));
        }
    }

    /**
     * Create a new communication record
     */
    public static function createCommunication(
        ?int $studentId,
        ?int $parentId,
        ?int $userId,
        string $type,
        string $subject,
        string $description,
        string $priority = 'medium',
        ?string $attachmentPath = null,
        ?string $internalNotes = null
    ): \App\Models\Communication {
        return \App\Models\Communication::create([
            'student_id' => $studentId,
            'parent_id' => $parentId,
            'user_id' => $userId,
            'type' => $type,
            'subject' => $subject,
            'description' => $description,
            'priority' => $priority,
            'followup_date' => now()->addDays(3),
            'attachment_path' => $attachmentPath,
            'internal_notes' => $internalNotes,
        ]);
    }

    /**
     * Create a new ticket
     */
    public static function createTicket(
        string $title,
        string $description,
        string $category,
        ?int $studentId = null,
        ?int $parentId = null,
        ?int $assignedTo = null
    ): \App\Models\Ticket {
        $ticket = \App\Models\Ticket::create([
            'ticket_number' => self::generateTicketNumber(),
            'student_id' => $studentId,
            'parent_id' => $parentId,
            'assigned_to' => $assignedTo,
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'priority' => 'medium',
            'status' => 'new',
        ]);

        // Send notification
        self::sendNotification('ticket_created', [
            'ticket_number' => $ticket->ticket_number,
            'ticket_title' => $ticket->title,
        ]);

        return $ticket;
    }

    /**
     * Generate unique ticket number
     */
    private static function generateTicketNumber(): string
    {
        return 'TKT-' . strtoupper(Str::random(6)) . '-' . now()->format('YmdHis');
    }

    /**
     * Create a new task
     */
    public static function createTask(
        string $title,
        ?int $assignedTo,
        ?int $createdBy,
        ?string $description = null,
        ?string $dueDate = null,
        string $priority = 'medium',
        ?int $studentId = null,
        ?int $parentId = null,
        ?int $ticketId = null,
        ?int $leadId = null
    ): \App\Models\Task {
        $task = \App\Models\Task::create([
            'title' => $title,
            'assigned_to' => $assignedTo,
            'created_by' => $createdBy,
            'description' => $description,
            'due_date' => $dueDate,
            'priority' => $priority,
            'status' => 'todo',
            'student_id' => $studentId,
            'parent_id' => $parentId,
            'ticket_id' => $ticketId,
            'lead_id' => $leadId,
        ]);

        // Send notification if assigned
        if ($assignedTo) {
            self::sendNotification('task_assigned', [
                'task_title' => $task->title,
                'due_date' => $dueDate ?? 'غير محدد',
            ], [$assignedTo]);
        }

        return $task;
    }

    /**
     * Create a new lead
     */
    public static function createLead(
        string $name,
        ?string $phone,
        ?string $email,
        string $source,
        ?string $childName = null,
        ?string $childLevel = null,
        ?string $subject = null,
        ?string $branch = null
    ): \App\Models\Lead {
        $lead = \App\Models\Lead::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'child_name' => $childName,
            'child_level' => $childLevel,
            'subject' => $subject,
            'branch' => $branch,
            'source' => $source,
            'status' => 'new',
        ]);

        return $lead;
    }

    /**
     * Interpolate variables in a template string
     */
    private static function interpolate(string $template, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
}