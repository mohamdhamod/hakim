@extends('layout.home.main')

@section('title', __('translation.patient.edit'))

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.workspace') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clinic.patients.show', $patient) }}">{{ $patient->file_number }}</a></li>
            <li class="breadcrumb-item active">{{ __('translation.common.edit') }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">{{ __('translation.patient.edit') }}: {{ $patient->full_name }}</h1>
        <p class="text-muted mb-0">{{ __('translation.patient.edit_patient_info') }}</p>
    </div>

    <form id="patient-form" action="{{ route('clinic.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            {{-- Basic Information --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-user text-primary me-2"></i>
                            {{ __('translation.patient.basic_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.file_number') }}</label>
                                <input type="text" class="form-control" value="{{ $patient->file_number }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.full_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control" value="{{ $patient->full_name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('translation.patient.birth_year') }}</label>
                                <select name="date_of_birth" class="form-select">
                                    <option value="">{{ __('translation.patient.select_year') }}</option>
                                    @for($year = date('Y'); $year >= 1920; $year--)
                                        <option value="{{ $year }}-01-01" {{ $patient->date_of_birth?->format('Y') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('translation.patient.gender') }}</label>
                                <select name="gender" class="form-select">
                                    <option value="">{{ __('translation.common.select') }}</option>
                                    <option value="male" {{ $patient->gender === 'male' ? 'selected' : '' }}>{{ __('translation.patient.male') }}</option>
                                    <option value="female" {{ $patient->gender === 'female' ? 'selected' : '' }}>{{ __('translation.patient.female') }}</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('translation.patient.blood_type') }}</label>
                                <select name="blood_type" class="form-select">
                                    <option value="">{{ __('translation.common.select') }}</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ $patient->blood_type === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.phone') }}</label>
                                <input type="tel" name="phone" class="form-control" value="{{ $patient->phone }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ $patient->email }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('translation.patient.address') }}</label>
                                <textarea name="address" class="form-control" rows="2">{{ $patient->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                    {{-- Medical History --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-heart-pulse text-danger me-2"></i>
                                {{ __('translation.patient.medical_history') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.patient.allergies') }}</label>
                                    <textarea name="allergies" class="form-control" rows="3">{{ $patient->allergies }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.patient.chronic_diseases') }}</label>
                                    <textarea name="chronic_diseases" class="form-control" rows="3">{{ $patient->chronic_diseases }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('translation.patient.medical_history_details') }}</label>
                                    <textarea name="medical_history" class="form-control" rows="4">{{ $patient->medical_history }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('translation.patient.family_history') }}</label>
                                    <textarea name="family_history" class="form-control" rows="3">{{ $patient->family_history }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Emergency Contact & Notes --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-telephone text-warning me-2"></i>
                                {{ __('translation.patient.emergency_contact') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('translation.patient.emergency_contact_name') }}</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ $patient->emergency_contact_name }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('translation.patient.emergency_contact_phone') }}</label>
                                <input type="tel" name="emergency_contact_phone" class="form-control" value="{{ $patient->emergency_contact_phone }}">
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-transparent border-0 py-3">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-sticky text-info me-2"></i>
                                {{ __('translation.patient.notes') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <textarea name="notes" class="form-control" rows="5">{{ $patient->notes }}</textarea>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i>{{ __('translation.common.save_changes') }}
                        </button>
                        <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-2"></i>{{ __('translation.common.cancel') }}
                        </a>
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
    const form = document.getElementById('patient-form');
    if (form && window.handleSubmit) {
        handleSubmit(form);
    }
});
</script>
@endpush
