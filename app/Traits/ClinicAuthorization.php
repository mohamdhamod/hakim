<?php

namespace App\Traits;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

trait ClinicAuthorization
{
    /**
     * Get the current doctor's clinic.
     */
    protected function getClinic()
    {
        return Auth::user()->clinic;
    }

    /**
     * Authorize clinic access - checks if user has an approved clinic.
     * Returns the clinic if authorized, or redirects/aborts if not.
     *
     * @param bool $jsonResponse Whether to return JSON response on failure
     * @return \App\Models\Clinic|null
     */
    protected function authorizeClinicAccess(bool $jsonResponse = false)
    {
        $clinic = $this->getClinic();

        if (!$clinic || !$clinic->isApproved()) {
            if ($jsonResponse) {
                abort(response()->json([
                    'success' => false,
                    'message' => __('translation.clinic.not_approved'),
                ], 403));
            }
            
            abort(redirect()->route('clinic.dashboard')
                ->with('error', __('translation.clinic.not_approved')));
        }

        return $clinic;
    }

    /**
     * Authorize patient access - checks if user has access to the patient.
     * Combines clinic authorization with patient ownership check.
     *
     * @param Patient $patient
     * @param bool $jsonResponse Whether to return JSON response on failure
     * @return \App\Models\Clinic
     */
    protected function authorizePatientAccess(Patient $patient, bool $jsonResponse = false)
    {
        $clinic = $this->getClinic();

        if (!$clinic || !$clinic->isApproved() || $patient->clinic_id !== $clinic->id) {
            if ($jsonResponse) {
                abort(response()->json([
                    'success' => false,
                    'message' => __('translation.clinic.not_approved'),
                ], 403));
            }
            
            abort(redirect()->route('clinic.dashboard')
                ->with('error', __('translation.clinic.not_approved')));
        }

        return $clinic;
    }

    /**
     * Authorize access to a model that has clinic_id.
     * Combines clinic authorization with ownership check.
     *
     * @param mixed $model A model with clinic_id attribute
     * @param bool $jsonResponse Whether to return JSON response on failure
     * @return \App\Models\Clinic
     */
    protected function authorizeClinicModel($model, bool $jsonResponse = false)
    {
        $clinic = $this->getClinic();

        if (!$clinic || !$clinic->isApproved() || $model->clinic_id !== $clinic->id) {
            if ($jsonResponse) {
                abort(response()->json([
                    'success' => false,
                    'message' => __('translation.clinic.not_approved'),
                ], 403));
            }
            
            abort(redirect()->route('clinic.dashboard')
                ->with('error', __('translation.clinic.not_approved')));
        }

        return $clinic;
    }
}
