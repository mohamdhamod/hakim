<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PatientExportController extends Controller
{
    /**
     * Export patient medical record to PDF.
     */
    public function exportMedicalRecord($lang, Patient $patient)
    {
        $this->authorize('view', $patient);

        // Load all medical data
        $patient->load([
            'examinations' => function ($query) {
                $query->latest('examination_date')->limit(20);
            },
            'labTestResults' => function ($query) {
                $query->with('labTestType')->latest('test_date')->limit(20);
            },
            'vaccinationRecords' => function ($query) {
                $query->with('vaccinationType')->latest('vaccination_date');
            },
            'growthMeasurements' => function ($query) {
                $query->latest('measurement_date')->limit(10);
            },
            'chronicDiseases' => function ($query) {
                $query->with('chronicDiseaseType')->where('status', '!=', 'resolved');
            }
        ]);

        $clinic = $patient->clinic;
        $doctor = Auth::user();
        $exportDate = now();

        $pdf = Pdf::loadView('clinic.exports.medical-record', compact(
            'patient',
            'clinic',
            'doctor',
            'exportDate'
        ));

        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'medical-record-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export lab tests results to PDF.
     */
    public function exportLabTests($lang, Patient $patient)
    {
        $this->authorize('view', $patient);

        $labTests = $patient->labTestResults()
            ->with('labTestType', 'orderedBy')
            ->latest('test_date')
            ->get();

        $clinic = $patient->clinic;
        $exportDate = now();

        $pdf = Pdf::loadView('clinic.exports.lab-tests', compact(
            'patient',
            'labTests',
            'clinic',
            'exportDate'
        ));

        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'lab-tests-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export vaccination record to PDF.
     */
    public function exportVaccinations($lang, Patient $patient)
    {
        $this->authorize('view', $patient);

        $vaccinations = $patient->vaccinationRecords()
            ->with('vaccinationType', 'administeredBy')
            ->latest('vaccination_date')
            ->get();

        $clinic = $patient->clinic;
        $exportDate = now();

        $pdf = Pdf::loadView('clinic.exports.vaccinations', compact(
            'patient',
            'vaccinations',
            'clinic',
            'exportDate'
        ));

        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'vaccinations-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export growth chart to PDF.
     */
    public function exportGrowthChart($lang, Patient $patient)
    {
        $this->authorize('view', $patient);

        $measurements = $patient->growthMeasurements()
            ->with('measuredBy')
            ->chronological()
            ->get();

        $clinic = $patient->clinic;
        $exportDate = now();

        $pdf = Pdf::loadView('clinic.exports.growth-chart', compact(
            'patient',
            'measurements',
            'clinic',
            'exportDate'
        ));

        $pdf->setPaper('a4', 'landscape');
        
        $filename = 'growth-chart-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
