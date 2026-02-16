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
        $clinic = $this->authorizePatientAccess($patient);

        // Load only medical data belonging to this clinic
        $patient->load([
            'examinations' => function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->id)->with('doctor')->latest('examination_date');
            },
            'labTestResults' => function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->id)->with('labTestType', 'orderedBy')->latest('test_date');
            },
            'vaccinationRecords' => function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->id)->with('vaccinationType', 'administeredBy')->latest('vaccination_date');
            },
            'growthMeasurements' => function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->id)->with('measuredBy')->latest('measurement_date');
            },
            'chronicDiseases' => function ($query) use ($clinic) {
                $query->where('clinic_id', $clinic->id)->with(['chronicDiseaseType', 'monitoringRecords' => function($q) use ($clinic) {
                    $q->where('clinic_id', $clinic->id)->latest('monitoring_date');
                }]);
            }
        ]);

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
