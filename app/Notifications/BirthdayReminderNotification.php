<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class BirthdayReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Person $person,
        public readonly int $daysUntilBirthday
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
        $birthdayDate = $this->person->birthday?->format('d/m');
        $age = $this->person->birthday?->diffInYears(now()) + 1;

        $daysText = $this->daysUntilBirthday === 0
            ? __('app.notifications.birthday_reminder.today')
            : __('app.notifications.birthday_reminder.in_days', ['days' => $this->daysUntilBirthday]);

        return (new MailMessage)
            ->subject(__('app.notifications.birthday_reminder.subject', ['name' => $this->person->name]))
            ->greeting(__('app.notifications.birthday_reminder.greeting'))
            ->line(__('app.notifications.birthday_reminder.line1', [
                'name' => $this->person->name,
                'days' => $daysText,
            ]))
            ->line(__('app.notifications.birthday_reminder.line2', [
                'date' => $birthdayDate,
                'age' => $age,
            ]))
            ->action(__('app.notifications.birthday_reminder.action'), url('/admin/people/'.$this->person->id))
            ->salutation(__('app.notifications.birthday_reminder.salutation'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'person_id' => $this->person->id,
            'person_name' => $this->person->name,
            'birthday' => $this->person->birthday?->toDateString(),
            'days_until' => $this->daysUntilBirthday,
            'type' => 'birthday_reminder',
        ];
    }
}
