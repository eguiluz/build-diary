<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\CalendarEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly CalendarEvent $event
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $eventDate = $this->event->event_date->format('d/m/Y');
        $eventTime = $this->event->event_time ?? 'Todo el día';

        return (new MailMessage)
            ->subject(__('app.notifications.event_reminder.subject', ['title' => $this->event->title]))
            ->greeting(__('app.notifications.event_reminder.greeting'))
            ->line(__('app.notifications.event_reminder.line1', ['title' => $this->event->title]))
            ->line(__('app.notifications.event_reminder.line2', ['date' => $eventDate, 'time' => $eventTime]))
            ->when($this->event->description, function (MailMessage $mail) {
                return $mail->line($this->event->description);
            })
            ->action(__('app.notifications.event_reminder.action'), url('/admin/calendar'))
            ->salutation(__('app.notifications.event_reminder.salutation'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'event_date' => $this->event->event_date->toDateString(),
            'event_time' => $this->event->event_time,
            'type' => 'event_reminder',
        ];
    }
}
