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

// Appointments (public booking - guests can book)
Route::post('/appointments', [Controllers\AppointmentController::class, 'store'])->name('appointments.store');

// User appointments (requires auth)
Route::middleware(['auth', 'verified'])->group(function () {
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
    
    // Routes requiring approved clinic
    Route::middleware(['clinic.approved'])->group(function () {
        // Clinic Settings
        Route::get('/settings', [Clinic\DashboardController::class, 'settings'])->name('clinic.settings');
        Route::put('/settings', [Clinic\DashboardController::class, 'updateSettings'])->name('clinic.settings.update');

        // Patients Management
        Route::get('/patients/search', [Clinic\PatientController::class, 'search'])->name('clinic.patients.search');
        Route::get('/patients/{patient}/details', [Clinic\WorkspaceController::class, 'patientDetails'])->name('clinic.patients.details');
        Route::patch('/patients/{patient}/medical-history', [Clinic\PatientController::class, 'updateMedicalHistory'])->name('clinic.patients.update-medical-history');
        Route::patch('/patients/{patient}/emergency-contact', [Clinic\PatientController::class, 'updateEmergencyContact'])->name('clinic.patients.update-emergency-contact');
        Route::patch('/patients/{patient}/notes', [Clinic\PatientController::class, 'updateNotes'])->name('clinic.patients.update-notes');
        Route::resource('patients', Clinic\PatientController::class)->names('clinic.patients');

        // Appointments Management (for doctors)
        Route::get('/appointments', [Clinic\WorkspaceController::class, 'allAppointments'])->name('clinic.appointments.index');
        Route::get('/appointments/{appointment}/details', [Clinic\WorkspaceController::class, 'appointmentDetails'])->name('clinic.appointments.details');
        Route::post('/appointments/{appointment}/confirm', [Clinic\WorkspaceController::class, 'confirmAppointment'])->name('clinic.appointments.confirm');
        Route::post('/appointments/{appointment}/complete', [Clinic\WorkspaceController::class, 'completeAppointment'])->name('clinic.appointments.complete');
        Route::post('/appointments/{appointment}/cancel', [Clinic\WorkspaceController::class, 'cancelAppointment'])->name('clinic.appointments.cancel');
        Route::post('/appointments/{appointment}/register-patient', [Clinic\WorkspaceController::class, 'registerPatientFromAppointment'])->name('clinic.appointments.register-patient');

        // Examinations Management
        Route::get('/examinations/today', [Clinic\ExaminationController::class, 'today'])->name('clinic.examinations.today');
        Route::post('/examinations/{examination}/complete', [Clinic\ExaminationController::class, 'complete'])->name('clinic.examinations.complete');
        Route::get('/examinations/{examination}/print', [Clinic\ExaminationController::class, 'print'])->name('clinic.examinations.print');
        Route::post('/examinations/{examination}/attachments', [Clinic\ExaminationController::class, 'uploadAttachment'])->name('clinic.examinations.attachments.upload');
        Route::delete('/attachments/{attachment}', [Clinic\ExaminationController::class, 'deleteAttachment'])->name('clinic.attachments.delete');
        Route::resource('examinations', Clinic\ExaminationController::class)->except(['create'])->names('clinic.examinations');
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


