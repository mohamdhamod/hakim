<?php

namespace App\Mail;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClinicStaffInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Clinic $clinic;
    public User $inviter;
    public string $inviteeEmail;
    public string $password;
    public bool $isNewUser;
    public ?User $invitee;

    public function __construct(
        Clinic $clinic,
        User $inviter,
        string $inviteeEmail,
        string $password,
        bool $isNewUser = true,
        ?User $invitee = null
    ) {
        $this->clinic = $clinic;
        $this->inviter = $inviter;
        $this->inviteeEmail = $inviteeEmail;
        $this->password = $password;
        $this->isNewUser = $isNewUser;
        $this->invitee = $invitee;
    }

    public function build(): self
    {
        return $this
            ->subject(__('translation.clinic.staff_invitation_subject', ['clinic' => $this->clinic->display_name]))
            ->view('emails.clinic-staff-invitation');
    }
}
