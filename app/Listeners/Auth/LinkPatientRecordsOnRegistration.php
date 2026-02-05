<?php

namespace App\Listeners\Auth;

use App\Services\PatientIntegrationService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class LinkPatientRecordsOnRegistration
{
    protected PatientIntegrationService $integrationService;

    public function __construct(PatientIntegrationService $integrationService)
    {
        $this->integrationService = $integrationService;
    }

    /**
     * Handle user registration event.
     * Links any existing patient records to the newly registered user.
     */
    public function handle(Registered|Verified $event): void
    {
        $user = $event->user;
        
        // Only process for patients
        if ($user->isPatient()) {
            $this->integrationService->linkUserToPatientRecords($user);
        }
    }
}
