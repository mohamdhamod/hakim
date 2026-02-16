<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\Auth\OtpLoginController;
use App\Http\Controllers\Auth\RegisterLinkController;
use App\Http\Controllers\Clinic;
use App\Http\Controllers\Patient;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider under {locale} prefix.
| So "/" here means "/{locale}/"
|
*/

// Home route - main landing page (becomes /{locale}/)
Route::get('/', [Controllers\HomeController::class, 'index'])->name('home');

// Browse Clinics page (public)
Route::get('/clinics', [Controllers\ClinicHomeController::class, 'index'])->name('home.clinics');

// Clinic details (public)
Route::get('/clinics/{clinic}', [Controllers\ClinicHomeController::class, 'show'])->name('clinics.show');

// Appointments (requires auth)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/appointments', [Controllers\AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/my-appointments', [Controllers\AppointmentController::class, 'myAppointments'])->name('appointments.index');
    Route::post('/appointments/{appointment}/cancel', [Controllers\AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

// ==========================================
// Patient Routes (For Patients)
// ==========================================
Route::middleware(['auth', 'verified'])->prefix('patient')->name('patient.')->group(function () {
    // Patient Dashboard
    Route::get('/', [Patient\DashboardController::class, 'index'])->name('dashboard');
    
    // Appointments
    Route::get('/appointments', [Patient\DashboardController::class, 'appointments'])->name('appointments');
    Route::delete('/appointments/{appointment}', [Patient\DashboardController::class, 'cancelAppointment'])->name('appointments.cancel');
    
    // Medical History
    Route::get('/medical-history', [Patient\DashboardController::class, 'medicalHistory'])->name('medical-history');
    
    // Browse Clinics
    Route::get('/clinics', [Patient\DashboardController::class, 'clinics'])->name('clinics');
    Route::get('/clinics/{clinic}', [Patient\DashboardController::class, 'showClinic'])->name('clinic.show');
    
    // Book Appointment
    Route::post('/book-appointment', [Patient\DashboardController::class, 'bookAppointment'])->name('book-appointment');
});

// ==========================================
// Clinic Management Routes (For Doctors)
// ==========================================
// ==========================================
Route::middleware(['auth', 'verified'])->prefix('clinic')->group(function () {
    
    // Workspace (main chat-style interface)
    Route::get('/', [Clinic\WorkspaceController::class, 'index'])->name('clinic.workspace');

    // AI Assistant page
    Route::get('/ai-assistant', [Clinic\WorkspaceController::class, 'aiAssistant'])->name('clinic.ai-assistant');
    
    // Routes requiring approved clinic (doctor only)
    Route::middleware(['clinic.approved'])->group(function () {
        // Workspace AJAX endpoints
        Route::get('/workspace/patients', [Clinic\WorkspaceController::class, 'searchPatients'])->name('clinic.workspace.patients');
        Route::get('/workspace/appointments', [Clinic\WorkspaceController::class, 'searchAppointments'])->name('clinic.workspace.appointments');
        Route::post('/patients/request-access', [Clinic\PatientController::class, 'requestAccess'])->name('clinic.patients.request-access');

        // Clinic Settings
        Route::get('/settings', [Clinic\DashboardController::class, 'settings'])->name('clinic.settings');
        Route::put('/settings', [Clinic\DashboardController::class, 'updateSettings'])->name('clinic.settings.update');

        // Team Management (doctor only) - actions redirect to profile
        Route::post('/team/invite', [Clinic\ClinicTeamController::class, 'invite'])->name('clinic.team.invite');
        Route::patch('/team/{clinicUser}/toggle-status', [Clinic\ClinicTeamController::class, 'toggleStatus'])->name('clinic.team.toggle-status');
        Route::delete('/team/{clinicUser}', [Clinic\ClinicTeamController::class, 'remove'])->name('clinic.team.remove');
        Route::post('/team/{clinicUser}/resend', [Clinic\ClinicTeamController::class, 'resendInvitation'])->name('clinic.team.resend');
    });

    // Routes accessible by both doctors and clinic patient editors
    Route::middleware(['clinic.staff'])->group(function () {
        // Patients Management
        Route::get('/patients/search', [Clinic\PatientController::class, 'search'])->name('clinic.patients.search');
        Route::patch('/patients/{patient}/medical-history', [Clinic\PatientController::class, 'updateMedicalHistory'])->name('clinic.patients.update-medical-history');
        Route::patch('/patients/{patient}/emergency-contact', [Clinic\PatientController::class, 'updateEmergencyContact'])->name('clinic.patients.update-emergency-contact');
        Route::patch('/patients/{patient}/notes', [Clinic\PatientController::class, 'updateNotes'])->name('clinic.patients.update-notes');
        
        // Patient All Records Pages
        Route::get('/patients/{patient}/all-examinations', [Clinic\PatientController::class, 'allExaminations'])->name('clinic.patients.all-examinations');
        Route::get('/patients/{patient}/all-lab-tests', [Clinic\PatientController::class, 'allLabTests'])->name('clinic.patients.all-lab-tests');
        Route::get('/patients/{patient}/all-vaccinations', [Clinic\PatientController::class, 'allVaccinations'])->name('clinic.patients.all-vaccinations');
        Route::get('/patients/{patient}/all-chronic-diseases', [Clinic\PatientController::class, 'allChronicDiseases'])->name('clinic.patients.all-chronic-diseases');
        Route::get('/patients/{patient}/all-growth-measurements', [Clinic\PatientController::class, 'allGrowthMeasurements'])->name('clinic.patients.all-growth-measurements');
        
        Route::resource('patients', Clinic\PatientController::class)->except(['edit'])->names('clinic.patients');

        // Lab Tests Management
        Route::post('/patients/{patient}/lab-tests', [Clinic\LabTestController::class, 'store'])->name('patients.lab-tests.store');
        Route::put('/patients/{patient}/lab-tests/{lab_test}', [Clinic\LabTestController::class, 'update'])->name('patients.lab-tests.update');
        Route::delete('/patients/{patient}/lab-tests/{lab_test}', [Clinic\LabTestController::class, 'destroy'])->name('patients.lab-tests.destroy');

        // Vaccinations Management
        Route::post('/patients/{patient}/vaccinations', [Clinic\VaccinationController::class, 'store'])->name('patients.vaccinations.store');
        Route::put('/patients/{patient}/vaccinations/{vaccination}', [Clinic\VaccinationController::class, 'update'])->name('patients.vaccinations.update');
        Route::delete('/patients/{patient}/vaccinations/{vaccination}', [Clinic\VaccinationController::class, 'destroy'])->name('patients.vaccinations.destroy');

        // Growth Charts Management
        Route::post('/patients/{patient}/growth-charts', [Clinic\GrowthChartController::class, 'store'])->name('patients.growth-charts.store');
        Route::put('/patients/{patient}/growth-charts/{growth_chart}', [Clinic\GrowthChartController::class, 'update'])->name('patients.growth-charts.update');
        Route::delete('/patients/{patient}/growth-charts/{growth_chart}', [Clinic\GrowthChartController::class, 'destroy'])->name('patients.growth-charts.destroy');

        // PDF Export Routes
        Route::get('/patients/{patient}/print/comprehensive', [Clinic\PatientExportController::class, 'printComprehensiveReport'])->name('patients.print.comprehensive');

        // Chronic Diseases Management
        Route::post('/patients/{patient}/chronic-diseases', [Clinic\ChronicDiseaseController::class, 'store'])->name('patients.chronic-diseases.store');
        Route::get('/patients/{patient}/chronic-diseases/{patientChronicDisease}', [Clinic\ChronicDiseaseController::class, 'show'])->name('patients.chronic-diseases.show');
        Route::put('/patients/{patient}/chronic-diseases/{patientChronicDisease}', [Clinic\ChronicDiseaseController::class, 'update'])->name('patients.chronic-diseases.update');
        Route::delete('/patients/{patient}/chronic-diseases/{patientChronicDisease}', [Clinic\ChronicDiseaseController::class, 'destroy'])->name('patients.chronic-diseases.destroy');
        Route::post('/patients/{patient}/chronic-diseases/{patientChronicDisease}/monitoring', [Clinic\ChronicDiseaseController::class, 'storeMonitoring'])->name('patients.chronic-diseases.monitoring.store');

        // Appointments Management (view for both, actions for doctors)
        Route::get('/appointments', [Clinic\WorkspaceController::class, 'allAppointments'])->name('clinic.appointments.index');
        Route::get('/appointments/{appointment}/details', [Clinic\WorkspaceController::class, 'appointmentDetails'])->name('clinic.appointments.details');
    });

    // Doctor-only routes for examinations
    Route::middleware(['clinic.approved'])->group(function () {
        // Appointment Actions (doctor only)
        Route::post('/appointments/{appointment}/confirm', [Clinic\WorkspaceController::class, 'confirmAppointment'])->name('clinic.appointments.confirm');
        Route::post('/appointments/{appointment}/complete', [Clinic\WorkspaceController::class, 'completeAppointment'])->name('clinic.appointments.complete');
        Route::post('/appointments/{appointment}/cancel', [Clinic\WorkspaceController::class, 'cancelAppointment'])->name('clinic.appointments.cancel');
        Route::post('/appointments/{appointment}/register-patient', [Clinic\WorkspaceController::class, 'registerPatientFromAppointment'])->name('clinic.appointments.register-patient');

        // Examinations Management (doctor only for create/edit)
        Route::get('/examinations/{examination}/print', [Clinic\ExaminationController::class, 'print'])->name('clinic.examinations.print');
        Route::resource('examinations', Clinic\ExaminationController::class)->only(['store', 'update', 'destroy'])->names('clinic.examinations');
    });
});


Route::middleware(['guest'])->group(function () {
    Route::post('/register/start', [RegisterLinkController::class, 'start'])
        ->middleware(['throttle:login-otp-request'])
        ->name('register.start');

    Route::get('/register/complete', [RegisterLinkController::class, 'complete'])
        ->name('register.complete');

    Route::get('/login/otp', function () {
        return view('auth.login-otp');
    })->name('login.otp');

    Route::post('/login/otp', [OtpLoginController::class, 'request'])
        ->middleware(['throttle:login-otp-request'])
        ->name('login.otp.request');

    Route::post('/login/otp/verify', [OtpLoginController::class, 'verify'])
        ->middleware(['throttle:login-otp-verify'])
        ->name('login.otp.verify');
});

Route::resource('about-us', Controllers\AboutController::class);
Route::get('/privacy-policy', [Controllers\PrivacyPolicyController::class, 'index'])->name('privacy-policy.index');
Route::get('/terms-conditions', [Controllers\TermsConditionsController::class, 'index'])->name('terms-conditions.index');
Route::get('/check-login-status', [Controllers\HomeController::class, 'checkLoginStatus'])->name('check.login.status');


Route::get('/profile', function () {
    return view('auth.profile');
})->middleware(['auth', 'verified'])->name('profile.index');

// Update clinic info for doctors
Route::put('/profile/clinic', [Controllers\ProfileController::class, 'updateClinic'])
    ->middleware(['auth', 'verified'])
    ->name('profile.clinic.update');


