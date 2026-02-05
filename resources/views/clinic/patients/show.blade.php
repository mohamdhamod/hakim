@extends('layout.home.main')

@section('title', $patient->full_name)

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.workspace') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
            <li class="breadcrumb-item active">{{ $patient->file_number }}</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Main Content Column --}}
        <div class="col-lg-8">
            {{-- Action Center --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle bg-primary-subtle text-primary" style="width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-injured fs-3"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-bold">{{ $patient->full_name }}</h4>
                                <div class="d-flex align-items-center gap-3 small text-muted">
                                    <span><i class="fas fa-hashtag"></i> {{ $patient->file_number }}</span>
                                    @if($patient->age)
                                        <span><i class="fas fa-birthday-cake"></i> {{ $patient->age }} {{ __('translation.patient.years') }}</span>
                                    @endif
                                    @if($patient->gender)
                                        <span><i class="fas fa-{{ $patient->gender === 'male' ? 'mars' : 'venus' }}"></i> {{ __('translation.patient.' . $patient->gender) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newExaminationModal">
                                <i class="fas fa-plus me-2"></i>{{ __('translation.examination.new') }}
                            </button>
                            <a href="{{ route('clinic.patients.edit', $patient) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>{{ __('translation.common.edit') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Patient Information Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        {{ __('translation.patient.info') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-phone text-muted me-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted d-block">{{ __('translation.patient.phone') }}</small>
                                    <strong>{{ $patient->phone ?: '-' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-envelope text-muted me-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted d-block">{{ __('translation.patient.email') }}</small>
                                    <strong>{{ $patient->email ?: '-' }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-tint text-muted me-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted d-block">{{ __('translation.patient.blood_type') }}</small>
                                    <strong><span class="badge bg-danger">{{ $patient->blood_type ?: '-' }}</span></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-muted me-3 mt-1" style="width: 20px;"></i>
                                <div>
                                    <small class="text-muted d-block">{{ __('translation.patient.address') }}</small>
                                    <strong>{{ $patient->address ?: '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Examinations History --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-clipboard-list text-success me-2"></i>
                        {{ __('translation.examination.history') }} ({{ $patient->examinations->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    @forelse($patient->examinations as $examination)
                        <div class="list-group-item border-0 border-bottom">
                            <div class="d-flex justify-content-between align-items-start p-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-secondary me-2">{{ $examination->examination_number }}</span>
                                        <span class="badge bg-{{ $examination->status === 'completed' ? 'success' : 'warning' }}-subtle text-{{ $examination->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ __('translation.examination.status.' . $examination->status) }}
                                        </span>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        <i class="fas fa-calendar"></i> {{ $examination->examination_date->format('M d, Y') }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-clock"></i> {{ $examination->examination_date->format('H:i') }}
                                    </div>
                                    @if($examination->diagnosis)
                                        <div class="mb-1">
                                            <strong>{{ __('translation.examination.diagnosis') }}:</strong> {{ Str::limit($examination->diagnosis, 100) }}
                                        </div>
                                    @endif
                                    @if($examination->chief_complaint)
                                        <div class="text-muted small">
                                            {{ Str::limit($examination->chief_complaint, 150) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('clinic.examinations.show', $examination->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> {{ __('translation.common.view') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 text-muted">{{ __('translation.examination.no_examinations') }}</h5>
                            <p class="text-muted">{{ __('translation.examination.add_first_examination') }}</p>
                            <button type="button" class="btn btn-success mt-2" data-bs-toggle="modal" data-bs-target="#newExaminationModal">
                                <i class="fas fa-plus me-2"></i>{{ __('translation.examination.create_first') }}
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        {{ __('translation.patient.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-sm text-start" data-bs-toggle="modal" data-bs-target="#newExaminationModal">
                            <i class="fas fa-stethoscope me-2"></i>{{ __('translation.examination.new_for_patient') }}
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm text-start" data-bs-toggle="modal" data-bs-target="#medicalHistoryModal">
                            <i class="fas fa-heart-pulse me-2"></i>{{ __('translation.patient.edit_medical_history') }}
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm text-start" data-bs-toggle="modal" data-bs-target="#emergencyContactModal">
                            <i class="fas fa-phone-alt me-2"></i>{{ __('translation.patient.edit_emergency_contact') }}
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm text-start" data-bs-toggle="modal" data-bs-target="#notesModal">
                            <i class="fas fa-sticky-note me-2"></i>{{ __('translation.patient.edit_notes') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Medical History Card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-heart-pulse text-danger me-2"></i>
                        {{ __('translation.patient.medical_history') }}
                    </h6>
                    <button type="button" class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="modal" data-bs-target="#medicalHistoryModal">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if($patient->allergies || $patient->chronic_diseases || $patient->medical_history || $patient->family_history)
                        @if($patient->allergies)
                            <div class="alert alert-danger small mb-3 py-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>{{ __('translation.patient.allergies') }}:</strong><br>
                                {{ $patient->allergies }}
                            </div>
                        @endif
                        @if($patient->chronic_diseases)
                            <div class="mb-3">
                                <strong class="small text-muted">{{ __('translation.patient.chronic_diseases') }}</strong>
                                <p class="mb-0 small">{{ $patient->chronic_diseases }}</p>
                            </div>
                        @endif
                        @if($patient->medical_history)
                            <div class="mb-3">
                                <strong class="small text-muted">{{ __('translation.patient.medical_history_details') }}</strong>
                                <p class="mb-0 small">{{ $patient->medical_history }}</p>
                            </div>
                        @endif
                        @if($patient->family_history)
                            <div class="mb-0">
                                <strong class="small text-muted">{{ __('translation.patient.family_history') }}</strong>
                                <p class="mb-0 small">{{ $patient->family_history }}</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-notes-medical mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="small mb-2">{{ __('translation.patient.no_medical_history') }}</p>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#medicalHistoryModal">
                                <i class="fas fa-plus me-1"></i>{{ __('translation.common.add') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Emergency Contact --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-phone-alt text-warning me-2"></i>
                        {{ __('translation.patient.emergency_contact') }}
                    </h6>
                    <button type="button" class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="modal" data-bs-target="#emergencyContactModal">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
                        <p class="mb-1 fw-semibold">{{ $patient->emergency_contact_name ?? '-' }}</p>
                        <p class="mb-0 text-muted small">
                            <i class="fas fa-phone"></i> {{ $patient->emergency_contact_phone ?? '-' }}
                        </p>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-user-plus mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="small mb-2">{{ __('translation.patient.no_emergency_contact') }}</p>
                            <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#emergencyContactModal">
                                <i class="fas fa-plus me-1"></i>{{ __('translation.common.add') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-sticky-note text-info me-2"></i>
                        {{ __('translation.patient.notes') }}
                    </h6>
                    <button type="button" class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="modal" data-bs-target="#notesModal">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if($patient->notes)
                        <p class="mb-0 small">{{ $patient->notes }}</p>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-clipboard mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                            <p class="small mb-2">{{ __('translation.patient.no_notes') }}</p>
                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#notesModal">
                                <i class="fas fa-plus me-1"></i>{{ __('translation.common.add') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Medical History Modal --}}
<div class="modal fade" id="medicalHistoryModal" tabindex="-1" aria-labelledby="medicalHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('clinic.patients.update-medical-history', $patient) }}" method="POST" id="medicalHistoryForm">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="medicalHistoryModalLabel">
                        <i class="fas fa-heart-pulse text-danger me-2"></i>
                        {{ __('translation.patient.medical_history') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.patient.allergies') }}</label>
                            <textarea name="allergies" class="form-control" rows="3" placeholder="{{ __('translation.patient.allergies_placeholder') }}">{{ $patient->allergies }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.patient.chronic_diseases') }}</label>
                            <textarea name="chronic_diseases" class="form-control" rows="3" placeholder="{{ __('translation.patient.chronic_diseases_placeholder') }}">{{ $patient->chronic_diseases }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.patient.medical_history_details') }}</label>
                            <textarea name="medical_history" class="form-control" rows="4" placeholder="{{ __('translation.patient.medical_history_placeholder') }}">{{ $patient->medical_history }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.patient.family_history') }}</label>
                            <textarea name="family_history" class="form-control" rows="3" placeholder="{{ __('translation.patient.family_history_placeholder') }}">{{ $patient->family_history }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Emergency Contact Modal --}}
<div class="modal fade" id="emergencyContactModal" tabindex="-1" aria-labelledby="emergencyContactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('clinic.patients.update-emergency-contact', $patient) }}" method="POST" id="emergencyContactForm">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="emergencyContactModalLabel">
                        <i class="fas fa-phone-alt text-warning me-2"></i>
                        {{ __('translation.patient.emergency_contact') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('translation.patient.emergency_contact_name') }}</label>
                        <input type="text" name="emergency_contact_name" class="form-control" value="{{ $patient->emergency_contact_name }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('translation.patient.emergency_contact_phone') }}</label>
                        <input type="tel" name="emergency_contact_phone" class="form-control" value="{{ $patient->emergency_contact_phone }}">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Notes Modal --}}
<div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('clinic.patients.update-notes', $patient) }}" method="POST" id="notesForm">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="notesModalLabel">
                        <i class="fas fa-sticky-note text-info me-2"></i>
                        {{ __('translation.patient.notes') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <textarea name="notes" class="form-control" rows="6" placeholder="{{ __('translation.patient.notes_placeholder') }}">{{ $patient->notes }}</textarea>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- New Examination Modal --}}
<div class="modal fade" id="newExaminationModal" tabindex="-1" aria-labelledby="newExaminationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('clinic.examinations.store') }}" method="POST" id="newExaminationForm">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                
                <div class="modal-header border-0 bg-success text-white">
                    <h5 class="modal-title" id="newExaminationModalLabel">
                        <i class="fas fa-stethoscope me-2"></i>
                        {{ __('translation.examination.new_for_patient') }}: {{ $patient->full_name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row g-4">
                        {{-- Basic Info --}}
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('translation.examination.number') }}</label>
                                    <input type="text" class="form-control bg-light" value="{{ $examinationNumber ?? '' }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('translation.examination.date') }} <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="examination_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('translation.examination.status_label') }}</label>
                                    <select name="status" class="form-select">
                                        <option value="scheduled">{{ __('translation.examination.status.scheduled') }}</option>
                                        <option value="in_progress" selected>{{ __('translation.examination.status.in_progress') }}</option>
                                        <option value="completed">{{ __('translation.examination.status.completed') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Chief Complaint --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-comment-medical text-info me-2"></i>{{ __('translation.examination.chief_complaint') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.chief_complaint') }}</label>
                                            <textarea name="chief_complaint" class="form-control" rows="3" placeholder="{{ __('translation.examination.chief_complaint_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.present_illness_history') }}</label>
                                            <textarea name="present_illness_history" class="form-control" rows="3" placeholder="{{ __('translation.examination.present_illness_history_placeholder') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Vital Signs --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-heartbeat text-danger me-2"></i>{{ __('translation.examination.vital_signs') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.temperature') }} (°C)</label>
                                            <input type="number" name="temperature" class="form-control form-control-sm" step="0.1" min="30" max="45" placeholder="37.0">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.blood_pressure') }}</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="blood_pressure_systolic" class="form-control" placeholder="120" min="60" max="250">
                                                <span class="input-group-text">/</span>
                                                <input type="number" name="blood_pressure_diastolic" class="form-control" placeholder="80" min="40" max="150">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <label class="form-label small">{{ __('translation.examination.pulse_rate') }}</label>
                                            <input type="number" name="pulse_rate" class="form-control form-control-sm" min="30" max="200" placeholder="72">
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <label class="form-label small">{{ __('translation.examination.respiratory_rate') }}</label>
                                            <input type="number" name="respiratory_rate" class="form-control form-control-sm" min="8" max="60" placeholder="16">
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <label class="form-label small">{{ __('translation.examination.oxygen_saturation') }} (%)</label>
                                            <input type="number" name="oxygen_saturation" class="form-control form-control-sm" min="50" max="100" placeholder="98">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.weight') }} (kg)</label>
                                            <input type="number" name="weight" class="form-control form-control-sm" step="0.1" min="0.5" max="500" placeholder="70">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.height') }} (cm)</label>
                                            <input type="number" name="height" class="form-control form-control-sm" step="0.1" min="20" max="300" placeholder="170">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Physical Examination --}}
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-search text-primary me-2"></i>{{ __('translation.examination.physical_examination') }}</label>
                            <textarea name="physical_examination" class="form-control" rows="3" placeholder="{{ __('translation.examination.physical_examination_placeholder') }}"></textarea>
                        </div>

                        {{-- Diagnosis & Treatment --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-clipboard-check text-success me-2"></i>{{ __('translation.examination.diagnosis_treatment') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label">{{ __('translation.examination.diagnosis') }}</label>
                                            <textarea name="diagnosis" class="form-control" rows="2" placeholder="{{ __('translation.examination.diagnosis_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ __('translation.examination.treatment_plan') }}</label>
                                            <textarea name="treatment_plan" class="form-control" rows="2" placeholder="{{ __('translation.examination.treatment_plan_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ __('translation.examination.prescriptions') }}</label>
                                            <textarea name="prescriptions" class="form-control" rows="3" placeholder="{{ __('translation.examination.prescriptions_placeholder') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Lab & Imaging --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-flask text-warning me-2"></i>{{ __('translation.examination.lab_imaging') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.lab_tests_ordered') }}</label>
                                            <textarea name="lab_tests_ordered" class="form-control" rows="2" placeholder="{{ __('translation.examination.lab_tests_ordered_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.imaging_ordered') }}</label>
                                            <textarea name="imaging_ordered" class="form-control" rows="2" placeholder="{{ __('translation.examination.imaging_ordered_placeholder') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Follow Up --}}
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-calendar-check text-secondary me-2"></i>{{ __('translation.examination.follow_up_date') }}</label>
                                    <input type="date" name="follow_up_date" class="form-control">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">{{ __('translation.examination.follow_up_notes') }}</label>
                                    <input type="text" name="follow_up_notes" class="form-control" placeholder="{{ __('translation.examination.follow_up_notes_placeholder') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Doctor Notes --}}
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-notes-medical text-muted me-2"></i>{{ __('translation.examination.doctor_notes') }}</label>
                            <textarea name="doctor_notes" class="form-control" rows="2" placeholder="{{ __('translation.examination.doctor_notes_placeholder') }}"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>{{ __('translation.examination.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-open examination modal if redirected with openExaminationModal flag
    @if(session('openExaminationModal'))
    const examinationModal = new bootstrap.Modal(document.getElementById('newExaminationModal'));
    examinationModal.show();
    @endif

    // Handle form submissions via AJAX for patient modals
    ['medicalHistoryForm', 'emergencyContactForm', 'notesForm'].forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
                
                try {
                    const formData = new FormData(form);
                    const data = await ApiClient.request(form.action, {
                        method: 'POST',
                        data: formData,
                        showLoading: false
                    });
                    
                    if (data.success) {
                        const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                        modal.hide();
                        SwalUtil.toast(data.message || '{{ __("translation.common.saved_successfully") }}', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }
    });

    // Handle examination form submission
    const examinationForm = document.getElementById('newExaminationForm');
    if (examinationForm) {
        examinationForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = examinationForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
            
            try {
                const formData = new FormData(examinationForm);
                const data = await ApiClient.request(examinationForm.action, {
                    method: 'POST',
                    data: formData,
                    showLoading: false
                });
                
                if (data.success) {
                    const modal = bootstrap.Modal.getInstance(examinationForm.closest('.modal'));
                    modal.hide();
                    SwalUtil.toast(data.message || '{{ __("translation.examination.created_successfully") }}', 'success');
                    
                    if (data.redirect) {
                        setTimeout(() => window.location.href = data.redirect, 1000);
                    } else {
                        setTimeout(() => location.reload(), 1000);
                    }
                } else {
                    SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }
});
</script>
@endpush
