<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Traits\ClinicAuthorization;
use Illuminate\Support\Facades\Auth;

class PatientExportController extends Controller
{
    use ClinicAuthorization;

    /**
     * Print comprehensive patient report (all data in one printable page).
     */
    public function printComprehensiveReport($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        // Load all medical data
        $patient->load([
            'examinations' => function ($query) {
                $query->with('doctor')->latest('examination_date');
            },
            'labTestResults' => function ($query) {
                $query->with('labTestType', 'orderedBy')->latest('test_date');
            },
            'vaccinationRecords' => function ($query) {
                $query->with('vaccinationType', 'administeredBy')->latest('vaccination_date');
            },
            'growthMeasurements' => function ($query) {
                $query->with('measuredBy')->latest('measurement_date');
            },
            'chronicDiseases' => function ($query) {
                $query->with(['chronicDiseaseType', 'monitoringRecords' => function($q) {
                    $q->latest('monitoring_date');
                }]);
            }
        ]);

        $clinic = $patient->clinic;
        $doctor = Auth::user();
        $exportDate = now();

        return view('clinic.exports.comprehensive-report', compact(
            'patient',
            'clinic',
            'doctor',
            'exportDate'
        ));
    }
}
