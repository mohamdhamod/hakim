@extends('layout.home.main')

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">{{ __('translation.examination.edit') }}: {{ $examination->examination_number }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinic.examinations.index') }}">{{ __('translation.examination.examinations') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinic.examinations.show', $examination->id) }}">{{ $examination->examination_number }}</a></li>
                        <li class="breadcrumb-item active">{{ __('translation.common.edit') }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('clinic.examinations.show', $examination->id) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('translation.common.back') }}
                </a>
            </div>
        </div>

        <form action="{{ route('clinic.examinations.update', $examination->id) }}" method="POST" id="editExaminationForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    {{-- Basic Info --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-primary text-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-info-circle me-2"></i>
                                {{ __('translation.examination.basic_info') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('translation.examination.number') }}</label>
                                    <input type="text" class="form-control bg-light" value="{{ $examination->examination_number }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('translation.examination.date') }} <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="examination_date" class="form-control @error('examination_date') is-invalid @enderror" 
                                        value="{{ old('examination_date', $examination->examination_date->format('Y-m-d\TH:i')) }}" required>
                                    @error('examination_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('translation.examination.status_label') }}</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="scheduled" {{ old('status', $examination->status) == 'scheduled' ? 'selected' : '' }}>{{ __('translation.examination.status.scheduled') }}</option>
                                        <option value="in_progress" {{ old('status', $examination->status) == 'in_progress' ? 'selected' : '' }}>{{ __('translation.examination.status.in_progress') }}</option>
                                        <option value="completed" {{ old('status', $examination->status) == 'completed' ? 'selected' : '' }}>{{ __('translation.examination.status.completed') }}</option>
                                        <option value="cancelled" {{ old('status', $examination->status) == 'cancelled' ? 'selected' : '' }}>{{ __('translation.examination.status.cancelled') }}</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Chief Complaint --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-chat-left-text text-info me-2"></i>
                                {{ __('translation.examination.chief_complaint') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.chief_complaint') }}</label>
                                    <textarea name="chief_complaint" class="form-control @error('chief_complaint') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.chief_complaint_placeholder') }}">{{ old('chief_complaint', $examination->chief_complaint) }}</textarea>
                                    @error('chief_complaint')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.present_illness_history') }}</label>
                                    <textarea name="present_illness_history" class="form-control @error('present_illness_history') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.present_illness_history_placeholder') }}">{{ old('present_illness_history', $examination->present_illness_history) }}</textarea>
                                    @error('present_illness_history')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Vital Signs --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-heart-pulse text-danger me-2"></i>
                                {{ __('translation.examination.vital_signs') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <label class="form-label small">{{ __('translation.examination.temperature') }} (°C)</label>
                                    <input type="number" name="temperature" class="form-control form-control-sm @error('temperature') is-invalid @enderror" 
                                        step="0.1" min="30" max="45" placeholder="37.0" value="{{ old('temperature', $examination->temperature) }}">
                                    @error('temperature')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small">{{ __('translation.examination.blood_pressure') }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="blood_pressure_systolic" class="form-control @error('blood_pressure_systolic') is-invalid @enderror" 
                                            placeholder="120" min="60" max="250" value="{{ old('blood_pressure_systolic', $examination->blood_pressure_systolic) }}">
                                        <span class="input-group-text">/</span>
                                        <input type="number" name="blood_pressure_diastolic" class="form-control @error('blood_pressure_diastolic') is-invalid @enderror" 
                                            placeholder="80" min="40" max="150" value="{{ old('blood_pressure_diastolic', $examination->blood_pressure_diastolic) }}">
                                    </div>
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label small">{{ __('translation.examination.pulse_rate') }}</label>
                                    <input type="number" name="pulse_rate" class="form-control form-control-sm @error('pulse_rate') is-invalid @enderror" 
                                        min="30" max="200" placeholder="72" value="{{ old('pulse_rate', $examination->pulse_rate) }}">
                                    @error('pulse_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label small">{{ __('translation.examination.respiratory_rate') }}</label>
                                    <input type="number" name="respiratory_rate" class="form-control form-control-sm @error('respiratory_rate') is-invalid @enderror" 
                                        min="8" max="60" placeholder="16" value="{{ old('respiratory_rate', $examination->respiratory_rate) }}">
                                    @error('respiratory_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-2 col-6">
                                    <label class="form-label small">{{ __('translation.examination.oxygen_saturation') }} (%)</label>
                                    <input type="number" name="oxygen_saturation" class="form-control form-control-sm @error('oxygen_saturation') is-invalid @enderror" 
                                        min="50" max="100" placeholder="98" value="{{ old('oxygen_saturation', $examination->oxygen_saturation) }}">
                                    @error('oxygen_saturation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small">{{ __('translation.examination.weight') }} (kg)</label>
                                    <input type="number" name="weight" class="form-control form-control-sm @error('weight') is-invalid @enderror" 
                                        step="0.1" min="0.5" max="500" placeholder="70" value="{{ old('weight', $examination->weight) }}">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small">{{ __('translation.examination.height') }} (cm)</label>
                                    <input type="number" name="height" class="form-control form-control-sm @error('height') is-invalid @enderror" 
                                        step="0.1" min="20" max="300" placeholder="170" value="{{ old('height', $examination->height) }}">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Physical Examination --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-search text-primary me-2"></i>
                                {{ __('translation.examination.physical_examination') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <textarea name="physical_examination" class="form-control @error('physical_examination') is-invalid @enderror" rows="4" 
                                placeholder="{{ __('translation.examination.physical_examination_placeholder') }}">{{ old('physical_examination', $examination->physical_examination) }}</textarea>
                            @error('physical_examination')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Diagnosis & Treatment --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-clipboard-check text-success me-2"></i>
                                {{ __('translation.examination.diagnosis_treatment') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">{{ __('translation.examination.diagnosis') }}</label>
                                    <textarea name="diagnosis" class="form-control @error('diagnosis') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.diagnosis_placeholder') }}">{{ old('diagnosis', $examination->diagnosis) }}</textarea>
                                    @error('diagnosis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('translation.examination.treatment_plan') }}</label>
                                    <textarea name="treatment_plan" class="form-control @error('treatment_plan') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.treatment_plan_placeholder') }}">{{ old('treatment_plan', $examination->treatment_plan) }}</textarea>
                                    @error('treatment_plan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('translation.examination.prescriptions') }}</label>
                                    <textarea name="prescriptions" class="form-control @error('prescriptions') is-invalid @enderror" rows="4" 
                                        placeholder="{{ __('translation.examination.prescriptions_placeholder') }}">{{ old('prescriptions', $examination->prescriptions) }}</textarea>
                                    @error('prescriptions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lab & Imaging --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-droplet text-warning me-2"></i>
                                {{ __('translation.examination.lab_imaging') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.lab_tests_ordered') }}</label>
                                    <textarea name="lab_tests_ordered" class="form-control @error('lab_tests_ordered') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.lab_tests_ordered_placeholder') }}">{{ old('lab_tests_ordered', $examination->lab_tests_ordered) }}</textarea>
                                    @error('lab_tests_ordered')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.lab_tests_results') }}</label>
                                    <textarea name="lab_tests_results" class="form-control @error('lab_tests_results') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.lab_tests_results_placeholder') }}">{{ old('lab_tests_results', $examination->lab_tests_results) }}</textarea>
                                    @error('lab_tests_results')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.imaging_ordered') }}</label>
                                    <textarea name="imaging_ordered" class="form-control @error('imaging_ordered') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.imaging_ordered_placeholder') }}">{{ old('imaging_ordered', $examination->imaging_ordered) }}</textarea>
                                    @error('imaging_ordered')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.imaging_results') }}</label>
                                    <textarea name="imaging_results" class="form-control @error('imaging_results') is-invalid @enderror" rows="3" 
                                        placeholder="{{ __('translation.examination.imaging_results_placeholder') }}">{{ old('imaging_results', $examination->imaging_results) }}</textarea>
                                    @error('imaging_results')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Follow Up --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-calendar-check text-secondary me-2"></i>
                                {{ __('translation.examination.follow_up') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('translation.examination.follow_up_date') }}</label>
                                    <input type="date" name="follow_up_date" class="form-control @error('follow_up_date') is-invalid @enderror" 
                                        value="{{ old('follow_up_date', $examination->follow_up_date?->format('Y-m-d')) }}">
                                    @error('follow_up_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">{{ __('translation.examination.follow_up_notes') }}</label>
                                    <input type="text" name="follow_up_notes" class="form-control @error('follow_up_notes') is-invalid @enderror" 
                                        placeholder="{{ __('translation.examination.follow_up_notes_placeholder') }}" value="{{ old('follow_up_notes', $examination->follow_up_notes) }}">
                                    @error('follow_up_notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Doctor Notes --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-journal-medical text-muted me-2"></i>
                                {{ __('translation.examination.doctor_notes') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <textarea name="doctor_notes" class="form-control @error('doctor_notes') is-invalid @enderror" rows="3" 
                                placeholder="{{ __('translation.examination.doctor_notes_placeholder') }}">{{ old('doctor_notes', $examination->doctor_notes) }}</textarea>
                            @error('doctor_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Patient Info --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-primary text-white border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-person me-2"></i>
                                {{ __('translation.examination.patient_info') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>{{ __('translation.patient.file_number') }}:</strong>
                                <p class="text-primary mb-0">{{ $examination->patient->file_number }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('translation.patient.name') }}:</strong>
                                <p class="mb-0">{{ $examination->patient->full_name }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('translation.patient.age') }}:</strong>
                                <p class="mb-0">{{ $examination->patient->age ?? '-' }} {{ $examination->patient->age ? __('translation.common.years') : '' }}</p>
                            </div>
                            <a href="{{ route('clinic.patients.show', $examination->patient->file_number) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-person-badge me-2"></i>{{ __('translation.patient.view_profile') }}
                            </a>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4 sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-lg me-2"></i>{{ __('translation.common.save_changes') }}
                                </button>
                                <a href="{{ route('clinic.examinations.show', $examination->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg me-2"></i>{{ __('translation.common.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editExaminationForm');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>{{ __('translation.common.saving') }}';
        
        try {
            const formData = new FormData(form);
            const response = await ApiClient.request(form.action, {
                method: 'POST',
                body: formData
            });
            
            if (response.success) {
                SwalUtil.toast('success', response.message || '{{ __('translation.examination.updated_successfully') }}');
                setTimeout(() => {
                    window.location.href = response.redirect || '{{ route('clinic.examinations.show', $examination->id) }}';
                }, 1000);
            } else {
                SwalUtil.toast('error', response.message || '{{ __('translation.common.error_occurred') }}');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            // If it's a validation error, the page will redirect with errors
            // Otherwise submit the form normally
            form.submit();
        }
    });
});
</script>
@endpush
