<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
| This file contains the backend routes
| Protected by middleware and user role admin
|
*/


Route::get('/',[Controllers\Dashboard\DashboardController::class,'index'])->name('dashboard');

Route::resource('roles',Controllers\Dashboard\RoleController::class);

// Users Management
Route::resource('users', Controllers\Dashboard\UsersController::class);
Route::put('users/change_password/{id}', [Controllers\Dashboard\UsersController::class, 'change_password'])
    ->name('users.change_password');

// Clinics Management (Admin)
Route::get('clinics/pending', [Controllers\Dashboard\ClinicsController::class, 'pending'])->name('clinics.pending');
Route::post('clinics/{clinic}/approve', [Controllers\Dashboard\ClinicsController::class, 'approve'])->name('clinics.approve');
Route::post('clinics/{clinic}/reject', [Controllers\Dashboard\ClinicsController::class, 'reject'])->name('clinics.reject');
Route::resource('clinics', Controllers\Dashboard\ClinicsController::class)->only(['index', 'show']);

Route::resource('config_titles',Controllers\Dashboard\ConfigTitlesController::class);

Route::resource('config_images',Controllers\Dashboard\ConfigImagesController::class);
Route::resource('config_email_links',Controllers\Dashboard\ConfigEmailsLinksController::class);

Route::resource('configurations',Controllers\Dashboard\ConfigurationsController::class);
Route::post('configurations/{id?}/updateActiveStatus',[Controllers\Dashboard\ConfigurationsController::class,'updateActiveStatus'])->name('configurations.updateActiveStatus');

Route::resource('countries',Controllers\Dashboard\CountryController::class);
Route::post('countries/{id?}/updateActiveStatus',[Controllers\Dashboard\CountryController::class,'updateActiveStatus'])->name('countries.updateActiveStatus');



// Specialties Management
Route::resource('specialties', Controllers\Dashboard\SpecialtiesController::class);
Route::post('specialties/{id?}/updateActiveStatus', [Controllers\Dashboard\SpecialtiesController::class, 'updateActiveStatus'])->name('specialties.updateActiveStatus');

// Chronic Disease Types Management
Route::resource('chronic_disease_types', Controllers\Dashboard\ChronicDiseaseTypesController::class);
Route::post('chronic_disease_types/{id?}/updateActiveStatus', [Controllers\Dashboard\ChronicDiseaseTypesController::class, 'updateActiveStatus'])->name('chronic_disease_types.updateActiveStatus');

// Lab Test Types Management
Route::resource('lab_test_types', Controllers\Dashboard\LabTestTypesController::class);
Route::post('lab_test_types/{id?}/updateActiveStatus', [Controllers\Dashboard\LabTestTypesController::class, 'updateActiveStatus'])->name('lab_test_types.updateActiveStatus');

// Vaccination Types Management
Route::resource('vaccination_types', Controllers\Dashboard\VaccinationTypesController::class);
Route::post('vaccination_types/{id?}/updateActiveStatus', [Controllers\Dashboard\VaccinationTypesController::class, 'updateActiveStatus'])->name('vaccination_types.updateActiveStatus');
