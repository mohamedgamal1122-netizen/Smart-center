<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SystemNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $subject;
    public $body;
    public $eventType;
    public $variables;

    public function __construct(string $subject, string $body, string $eventType = null, array $variables = [])
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->eventType = $eventType;
        $this->variables = $variables;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->subject)
            ->line($this->body)
            ->action('عرض التفاصيل', url('/notifications'));
    }

    public function toArray($notifiable)
    {
        return [
            'subject' => $this->subject,
            'body' => $this->body,
            'event_type' => $this->eventType,
            'variables' => $this->variables,
        ];
    }
}