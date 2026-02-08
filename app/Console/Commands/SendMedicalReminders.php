<?php

namespace App\Console\Commands;

use App\Models\VaccinationRecord;
use App\Models\PatientChronicDisease;
use App\Models\User;
use App\Notifications\MissedVaccinationNotification;
use App\Notifications\FollowupDueNotification;
use Illuminate\Console\Command;

class SendMedicalReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'medical:send-reminders 
                            {--type=all : Type of reminders (vaccinations, followups, all)}
                            {--days=7 : Days ahead to check}';

    /**
     * The console command description.
     */
    protected $description = 'Send reminders for missed vaccinations and upcoming followups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $daysAhead = $this->option('days');

        $this->info("Checking medical reminders (Type: {$type}, Days: {$daysAhead})...");

        $remindersSent = 0;

        if (in_array($type, ['vaccinations', 'all'])) {
            $remindersSent += $this->checkMissedVaccinations();
        }

        if (in_array($type, ['followups', 'all'])) {
            $remindersSent += $this->checkUpcomingFollowups($daysAhead);
        }

        $this->info("Total reminders sent: {$remindersSent}");
        
        return Command::SUCCESS;
    }

    /**
     * Check and notify for missed vaccinations.
     */
    private function checkMissedVaccinations()
    {
        $missedVaccinations = VaccinationRecord::where('status', 'scheduled')
            ->whereNotNull('next_dose_due_date')
            ->where('next_dose_due_date', '<', now())
            ->with(['patient.clinic', 'vaccinationType'])
            ->get();

        $count = 0;

        foreach ($missedVaccinations as $vaccination) {
            $clinic = $vaccination->patient->clinic;
            
            if (!$clinic) {
                continue;
            }

            // Notify clinic doctors
            $clinicDoctors = User::where('clinic_id', $clinic->id)
                ->where('role', 'doctor')
                ->get();

            foreach ($clinicDoctors as $doctor) {
                $doctor->notify(new MissedVaccinationNotification($vaccination));
                $count++;
            }

            $this->line("✓ Missed vaccination alert: {$vaccination->patient->full_name} - {$vaccination->vaccinationType->name}");
        }

        return $count;
    }

    /**
     * Check and notify for upcoming followups.
     */
    private function checkUpcomingFollowups($daysAhead)
    {
        $upcomingFollowups = PatientChronicDisease::where('status', 'active')
            ->whereNotNull('next_followup_date')
            ->whereBetween('next_followup_date', [now(), now()->addDays($daysAhead)])
            ->with(['patient.clinic', 'chronicDiseaseType'])
            ->get();

        $count = 0;

        foreach ($upcomingFollowups as $disease) {
            $clinic = $disease->patient->clinic;
            
            if (!$clinic) {
                continue;
            }

            // Notify clinic doctors
            $clinicDoctors = User::where('clinic_id', $clinic->id)
                ->where('role', 'doctor')
                ->get();

            foreach ($clinicDoctors as $doctor) {
                $doctor->notify(new FollowupDueNotification($disease));
                $count++;
            }

            $this->line("✓ Followup reminder: {$disease->patient->full_name} - {$disease->chronicDiseaseType->name}");
        }

        return $count;
    }
}
