<?php

namespace App\Notifications;

use App\Models\PatientChronicDisease;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class FollowupDueNotification extends Notification
{
    use Queueable;

    protected $chronicDisease;
    protected $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct(PatientChronicDisease $chronicDisease)
    {
        $this->chronicDisease = $chronicDisease;
        $this->patient = $chronicDisease->patient;
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
        $diseaseName = $this->chronicDisease->chronicDiseaseType->name;
        $dueDate = $this->chronicDisease->next_followup_date->format('Y-m-d');

        return (new MailMessage)
            ->subject(__('translation.followup_due_alert'))
            ->greeting(__('translation.hello') . ' ' . $notifiable->name)
            ->line(__('translation.followup_due_message', [
                'patient' => $patientName,
                'disease' => $diseaseName,
                'date' => $dueDate
            ]))
            ->action(__('translation.view_patient'), route('clinic.patients.show', $this->patient))
            ->line(__('translation.please_schedule_followup'));
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'followup_due',
            'patient_id' => $this->patient->id,
            'patient_name' => $this->patient->full_name,
            'chronic_disease_id' => $this->chronicDisease->id,
            'disease_name' => $this->chronicDisease->chronicDiseaseType->name,
            'due_date' => $this->chronicDisease->next_followup_date->toDateString(),
            'severity' => $this->chronicDisease->severity,
        ];
    }
}
