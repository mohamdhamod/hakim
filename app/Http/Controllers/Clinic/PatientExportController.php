<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Traits\ClinicAuthorization;
use Illuminate\Support\Facades\Auth;
use Mpdf\Mpdf;

class PatientExportController extends Controller
{
    use ClinicAuthorization;

    /**
     * Create mPDF instance with Arabic support.
     */
    protected function createPdf($orientation = 'P')
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $orientation,
            'default_font' => 'dejavusans',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'directionality' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
        ]);

        return $mpdf;
    }

    /**
     * Export patient medical record to PDF.
     */
    public function exportMedicalRecord($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

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

        $html = view('clinic.exports.medical-record', compact(
            'patient',
            'clinic',
            'doctor',
            'exportDate'
        ))->render();

        $mpdf = $this->createPdf('P');
        $mpdf->WriteHTML($html);
        
        $filename = 'medical-record-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export lab tests results to PDF.
     */
    public function exportLabTests($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $labTests = $patient->labTestResults()
            ->with('labTestType', 'orderedBy')
            ->latest('test_date')
            ->get();

        $clinic = $patient->clinic;
        $doctor = Auth::user();
        $exportDate = now();

        $html = view('clinic.exports.lab-tests', compact(
            'patient',
            'labTests',
            'clinic',
            'doctor',
            'exportDate'
        ))->render();

        $mpdf = $this->createPdf('P');
        $mpdf->WriteHTML($html);
        
        $filename = 'lab-tests-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export vaccination record to PDF.
     */
    public function exportVaccinations($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $vaccinations = $patient->vaccinationRecords()
            ->with('vaccinationType', 'administeredBy')
            ->latest('vaccination_date')
            ->get();

        $clinic = $patient->clinic;
        $doctor = Auth::user();
        $exportDate = now();

        $html = view('clinic.exports.vaccinations', compact(
            'patient',
            'vaccinations',
            'clinic',
            'doctor',
            'exportDate'
        ))->render();

        $mpdf = $this->createPdf('P');
        $mpdf->WriteHTML($html);
        
        $filename = 'vaccinations-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export growth chart to PDF.
     */
    public function exportGrowthChart($lang, Patient $patient)
    {
        $this->authorizePatientAccess($patient);

        $measurements = $patient->growthMeasurements()
            ->with('measuredBy')
            ->chronological()
            ->get();

        $clinic = $patient->clinic;
        $doctor = Auth::user();
        $exportDate = now();

        $html = view('clinic.exports.growth-chart', compact(
            'patient',
            'measurements',
            'clinic',
            'doctor',
            'exportDate'
        ))->render();

        $mpdf = $this->createPdf('L'); // Landscape
        $mpdf->WriteHTML($html);
        
        $filename = 'growth-chart-' . $patient->file_number . '-' . date('Y-m-d') . '.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
