<?php

namespace App\Notifications;

use App\Models\VaccinationRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class MissedVaccinationNotification extends Notification
{
    use Queueable;

    protected $vaccinationRecord;
    protected $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct(VaccinationRecord $vaccinationRecord)
    {
        $this->vaccinationRecord = $vaccinationRecord;
        $this->patient = $vaccinationRecord->patient;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $patientName = $this->patient->full_name;
        $vaccineName = $this->vaccinationRecord->vaccinationType->name;
        $dueDate = $this->vaccinationRecord->next_dose_due_date->format('Y-m-d');

        return (new MailMessage)
            ->subject(__('translation.missed_vaccination_alert'))
            ->greeting(__('translation.hello') . ' ' . $notifiable->name)
            ->line(__('translation.vaccination_missed_message', [
                'patient' => $patientName,
                'vaccine' => $vaccineName,
                'date' => $dueDate
            ]))
            ->action(__('translation.view_patient'), route('clinic.patients.show', $this->patient))
            ->line(__('translation.please_schedule_immediately'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'missed_vaccination',
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->full_name,
            'vaccination_record_id' => $this->vaccinationRecord->id,
            'vaccination_type' => $this->vaccinationRecord->vaccinationType->name,
            'due_date' => $this->vaccinationRecord->next_dose_due_date->toDateString(),
            'days_overdue' => now()->diffInDays($this->vaccinationRecord->next_dose_due_date),
        ];
    }
}
