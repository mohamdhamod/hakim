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
                            
                            {{-- Export Dropdown --}}
                            <div class="btn-group">
                                <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-file-export me-2"></i>{{ __('translation.export.menu') }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('patients.export.medical-record', $patient) }}" target="_blank">
                                            <i class="fas fa-file-medical me-2"></i>{{ __('translation.patient.medical_history') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('patients.export.lab-tests', $patient) }}" target="_blank">
                                            <i class="fas fa-flask me-2"></i>{{ __('translation.lab_tests') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('patients.export.vaccinations', $patient) }}" target="_blank">
                                            <i class="fas fa-syringe me-2"></i>{{ __('translation.vaccinations') }}
                                        </a>
                                    </li>
                                    @if($patient->age < 18)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('patients.export.growth-chart', $patient) }}" target="_blank">
                                            <i class="fas fa-chart-line me-2"></i>{{ __('translation.growth_chart') }}
                                        </a>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" onclick="window.print()">
                                            <i class="fas fa-print me-2"></i>{{ __('translation.export.print') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            
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

            {{-- Lab Tests Section --}}
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-flask text-primary me-2"></i>
                        {{ __('translation.lab_tests') }}
                        @if($patient->labTestResults && $patient->labTestResults->count() > 0)
                            <span class="badge bg-primary ms-2">{{ $patient->labTestResults->count() }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newLabTestModal">
                        <i class="fas fa-plus me-2"></i>{{ __('translation.add_lab_test') }}
                    </button>
                </div>
                <div class="card-body p-0">
                    @if($patient->labTestResults && $patient->labTestResults->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('translation.test_name') }}</th>
                                        <th>{{ __('translation.result') }}</th>
                                        <th>{{ __('translation.normal_range') }}</th>
                                        <th>{{ __('translation.test_date') }}</th>
                                        <th>{{ __('translation.status') }}</th>
                                        <th width="100">{{ __('translation.common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->labTestResults->take(5) as $labTest)
                                        <tr>
                                            <td>
                                                <strong>{{ $labTest->labTestType->name }}</strong>
                                                @if($labTest->labTestType->category)
                                                    <br><small class="text-muted">{{ $labTest->labTestType->category }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold {{ $labTest->is_abnormal ? 'text-danger' : 'text-success' }}">
                                                    {{ $labTest->result_value }} 
                                                    @if($labTest->labTestType->unit)
                                                        {{ $labTest->labTestType->unit }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="small text-muted">
                                                {{ $labTest->labTestType->normal_range_text ?? 
                                                   ($labTest->labTestType->normal_range_min && $labTest->labTestType->normal_range_max 
                                                    ? $labTest->labTestType->normal_range_min . ' - ' . $labTest->labTestType->normal_range_max 
                                                    : '-') }}
                                            </td>
                                            <td class="small">{{ $labTest->test_date->format('M d, Y') }}</td>
                                            <td>
                                                @if($labTest->is_abnormal)
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        <i class="fas fa-exclamation-circle"></i> {{ __('translation.abnormal') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success">
                                                        <i class="fas fa-check-circle"></i> {{ __('translation.normal') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewLabTest({{ $labTest->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($patient->labTestResults->count() > 5)
                            <div class="p-3 text-center border-top">
                                <a href="{{ route('patients.lab-tests.index', $patient) }}" class="btn btn-sm btn-outline-primary">
                                    {{ __('translation.view_all') }} ({{ $patient->labTestResults->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">🧪</div>
                            <h6 class="text-muted mb-2">{{ __('translation.no_lab_tests') }}</h6>
                            <p class="small text-muted mb-3">{{ __('translation.track_patient_lab_results') }}</p>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newLabTestModal">
                                <i class="fas fa-plus me-2"></i>{{ __('translation.add_first_test') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Vaccinations Section --}}
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f093fb10 0%, #f5576c10 100%);">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-syringe text-danger me-2"></i>
                        {{ __('translation.vaccinations') }}
                        @if($patient->vaccinationRecords && $patient->vaccinationRecords->count() > 0)
                            <span class="badge bg-danger ms-2">{{ $patient->vaccinationRecords->count() }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#newVaccinationModal">
                        <i class="fas fa-plus me-2"></i>{{ __('translation.add_vaccination') }}
                    </button>
                </div>
                <div class="card-body">
                    @if($patient->vaccinationRecords && $patient->vaccinationRecords->count() > 0)
                        <div class="row g-3">
                            @foreach($patient->vaccinationRecords->take(6) as $vaccination)
                                <div class="col-md-6">
                                    <div class="card border h-100" style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-start justify-content-between mb-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-semibold">{{ $vaccination->vaccinationType->name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-shield-virus"></i> {{ $vaccination->vaccinationType->disease_prevented }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-{{ $vaccination->status === 'completed' ? 'success' : ($vaccination->status === 'missed' ? 'danger' : 'warning') }}">
                                                    {{ __('translation.' . $vaccination->status) }}
                                                </span>
                                            </div>
                                            <div class="small text-muted mt-2">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fas fa-calendar fa-fw me-2"></i>
                                                    <span>{{ $vaccination->vaccination_date->format('M d, Y') }}</span>
                                                </div>
                                                @if($vaccination->dose_number > 1)
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="fas fa-layer-group fa-fw me-2"></i>
                                                        <span>{{ __('translation.dose') }} {{ $vaccination->dose_number }}</span>
                                                    </div>
                                                @endif
                                                @if($vaccination->next_dose_due_date)
                                                    <div class="d-flex align-items-center text-warning">
                                                        <i class="fas fa-clock fa-fw me-2"></i>
                                                        <span>{{ __('translation.next_dose') }}: {{ $vaccination->next_dose_due_date->format('M d, Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($patient->vaccinationRecords->count() > 6)
                            <div class="text-center mt-3">
                                <a href="{{ route('patients.vaccinations.index', $patient) }}" class="btn btn-sm btn-outline-danger">
                                    {{ __('translation.view_all') }} ({{ $patient->vaccinationRecords->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">💉</div>
                            <h6 class="text-muted mb-2">{{ __('translation.no_vaccinations') }}</h6>
                            <p class="small text-muted mb-3">{{ __('translation.track_immunization_history') }}</p>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#newVaccinationModal">
                                <i class="fas fa-plus me-2"></i>{{ __('translation.add_first_vaccination') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Growth Chart Section --}}
            @if($patient->date_of_birth && $patient->age < 18)
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #4facfe10 0%, #00f2fe10 100%);">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-chart-line text-info me-2"></i>
                        {{ __('translation.growth_chart') }}
                        @if($patient->growthMeasurements && $patient->growthMeasurements->count() > 0)
                            <span class="badge bg-info ms-2">{{ $patient->growthMeasurements->count() }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#newGrowthMeasurementModal">
                        <i class="fas fa-plus me-2"></i>{{ __('translation.add_measurement') }}
                    </button>
                </div>
                <div class="card-body">
                    @if($patient->growthMeasurements && $patient->growthMeasurements->count() > 0)
                        @php
                            $latest = $patient->growthMeasurements->first();
                        @endphp
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 rounded" style="background: rgba(13, 110, 253, 0.1);">
                                    <div class="small text-muted mb-1">{{ __('translation.weight') }}</div>
                                    <h4 class="mb-0 text-primary">{{ $latest->weight }} <small>kg</small></h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 rounded" style="background: rgba(13, 202, 240, 0.1);">
                                    <div class="small text-muted mb-1">{{ __('translation.height') }}</div>
                                    <h4 class="mb-0 text-info">{{ $latest->height }} <small>cm</small></h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 rounded" style="background: rgba(102, 16, 242, 0.1);">
                                    <div class="small text-muted mb-1">{{ __('translation.bmi') }}</div>
                                    <h4 class="mb-0" style="color: #6610f2;">{{ round($latest->bmi, 1) }}</h4>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 rounded" style="background: rgba(220, 53, 69, 0.1);">
                                    <div class="small text-muted mb-1">{{ __('translation.head_circumference') }}</div>
                                    <h4 class="mb-0 text-danger">{{ $latest->head_circumference ?? '-' }} <small>cm</small></h4>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <canvas id="growthChart" style="max-height: 300px;"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">📏</div>
                            <h6 class="text-muted mb-2">{{ __('translation.no_measurements') }}</h6>
                            <p class="small text-muted mb-3">{{ __('translation.track_child_growth') }}</p>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#newGrowthMeasurementModal">
                                <i class="fas fa-plus me-2"></i>{{ __('translation.add_first_measurement') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Chronic Diseases Section --}}
            <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #fa709a10 0%, #fee14010 100%);">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-heartbeat text-warning me-2"></i>
                        {{ __('translation.chronic_diseases') }}
                        @if($patient->chronicDiseases && $patient->chronicDiseases->count() > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $patient->chronicDiseases->count() }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#newChronicDiseaseModal">
                        <i class="fas fa-plus me-2"></i>{{ __('translation.add_chronic_disease') }}
                    </button>
                </div>
                <div class="card-body">
                    @if($patient->chronicDiseases && $patient->chronicDiseases->count() > 0)
                        <div class="row g-3">
                            @foreach($patient->chronicDiseases as $disease)
                                <div class="col-12">
                                    <div class="card border-start border-4 border-{{ $disease->severity === 'severe' ? 'danger' : ($disease->severity === 'moderate' ? 'warning' : 'success') }} h-100">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-semibold">{{ $disease->chronicDiseaseType->name }}</h6>
                                                    <div class="small text-muted mb-2">
                                                        <i class="fas fa-tag"></i> {{ $disease->chronicDiseaseType->category }}
                                                        @if($disease->chronicDiseaseType->icd11_code)
                                                            <span class="ms-2">| ICD-11: {{ $disease->chronicDiseaseType->icd11_code }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}-subtle text-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}">
                                                        {{ __('translation.' . $disease->status) }}
                                                    </span>
                                                    @if($disease->severity)
                                                        <span class="badge bg-{{ $disease->severity === 'severe' ? 'danger' : ($disease->severity === 'moderate' ? 'warning' : 'success') }} ms-1">
                                                            {{ __('translation.' . $disease->severity) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row g-2 small text-muted">
                                                <div class="col-md-4">
                                                    <i class="fas fa-calendar-check fa-fw"></i> {{ __('translation.diagnosed') }}: {{ $disease->diagnosis_date->format('M d, Y') }}
                                                </div>
                                                @if($disease->last_followup_date)
                                                    <div class="col-md-4">
                                                        <i class="fas fa-clock fa-fw"></i> {{ __('translation.last_followup') }}: {{ $disease->last_followup_date->format('M d, Y') }}
                                                    </div>
                                                @endif
                                                @if($disease->next_followup_date)
                                                    <div class="col-md-4 {{ $disease->next_followup_date->isPast() ? 'text-danger' : 'text-warning' }}">
                                                        <i class="fas fa-bell fa-fw"></i> {{ __('translation.next_followup') }}: {{ $disease->next_followup_date->format('M d, Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if($disease->treatment_plan)
                                                <div class="mt-2 p-2 rounded" style="background: rgba(0,0,0,0.03);">
                                                    <small class="text-muted d-block mb-1"><i class="fas fa-pills"></i> {{ __('translation.treatment_plan') }}:</small>
                                                    <small>{{ Str::limit($disease->treatment_plan, 150) }}</small>
                                                </div>
                                            @endif
                                            <div class="mt-2 d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-warning" onclick="viewDiseaseDetails({{ $disease->id }})">
                                                    <i class="fas fa-eye"></i> {{ __('translation.common.view') }}
                                                </button>
                                                <button class="btn btn-sm btn-outline-primary" onclick="addMonitoring({{ $disease->id }})">
                                                    <i class="fas fa-chart-line"></i> {{ __('translation.add_monitoring') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">🏥</div>
                            <h6 class="text-muted mb-2">{{ __('translation.no_chronic_diseases') }}</h6>
                            <p class="small text-muted mb-3">{{ __('translation.manage_chronic_conditions') }}</p>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#newChronicDiseaseModal">
                                <i class="fas fa-plus me-2"></i>{{ __('translation.add_first_disease') }}
                            </button>
                        </div>
                    @endif
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
                                        <div class="col-md-8">
                                            <label class="form-label">{{ __('translation.examination.diagnosis') }}</label>
                                            <textarea name="diagnosis" class="form-control" rows="2" placeholder="{{ __('translation.examination.diagnosis_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                {{ __('translation.examination.icd_code') }}
                                                <a href="https://icd.who.int/browse/2025-01/mms/en" target="_blank" class="text-info ms-1" title="{{ __('translation.examination.browse_icd_codes') }}">
                                                    <i class="fas fa-external-link-alt small"></i>
                                                </a>
                                            </label>
                                            <input type="text" name="icd_code" class="form-control" placeholder="{{ __('translation.examination.icd_code_placeholder') }}" maxlength="20">
                                            <small class="text-muted">{{ __('translation.examination.icd_code_help') }}</small>
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

{{-- New Lab Test Modal --}}
<div class="modal fade" id="newLabTestModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-flask me-2"></i>{{ __('translation.add_lab_test') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-4">
                    <i class="fas fa-info-circle"></i> {{ __('translation.select_test_and_enter_result') }}
                </p>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.test_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="labTestType" required>
                            <option value="">{{ __('translation.select_test_type') }}</option>
                            @foreach(\App\Models\LabTestType::where('is_active', true)->orderBy('order')->get() as $type)
                                <option value="{{ $type->id }}" 
                                    data-unit="{{ $type->unit }}" 
                                    data-min="{{ $type->normal_range_min }}" 
                                    data-max="{{ $type->normal_range_max }}">
                                    {{ $type->name }} 
                                    @if($type->category)
                                        ({{ $type->category }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.test_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="testDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.result_value') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="resultValue" step="0.01" required>
                            <span class="input-group-text" id="resultUnit">-</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-info small mb-0" id="normalRangeInfo" style="display: none;">
                            <i class="fas fa-chart-line"></i> <strong>{{ __('translation.normal_range') }}:</strong> <span id="normalRangeText"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.notes') }}</label>
                        <textarea class="form-control" id="labTestNotes" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveLabTest()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- New Vaccination Modal --}}
<div class="modal fade" id="newVaccinationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-syringe me-2"></i>{{ __('translation.add_vaccination') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.vaccination_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="vaccinationType" required>
                            <option value="">{{ __('translation.select_vaccination') }}</option>
                            @foreach(\App\Models\VaccinationType::where('is_active', true)->orderBy('order')->get() as $type)
                                <option value="{{ $type->id }}" 
                                    data-disease="{{ $type->disease_prevented }}"
                                    data-doses="{{ $type->doses_required }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.vaccination_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="vaccinationDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.dose_number') }}</label>
                        <input type="number" class="form-control" id="doseNumber" value="1" min="1">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.batch_number') }}</label>
                        <input type="text" class="form-control" id="batchNumber">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.manufacturer') }}</label>
                        <input type="text" class="form-control" id="manufacturer">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.injection_site') }}</label>
                        <select class="form-select" id="injectionSite">
                            <option value="">{{ __('translation.select_site') }}</option>
                            <option value="Left Arm">{{ __('translation.left_arm') }}</option>
                            <option value="Right Arm">{{ __('translation.right_arm') }}</option>
                            <option value="Left Thigh">{{ __('translation.left_thigh') }}</option>
                            <option value="Right Thigh">{{ __('translation.right_thigh') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.next_dose_date') }}</label>
                        <input type="date" class="form-control" id="nextDoseDate">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.reaction_notes') }}</label>
                        <textarea class="form-control" id="reactionNotes" rows="2" placeholder="{{ __('translation.any_adverse_reactions') }}"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-danger" onclick="saveVaccination()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- New Growth Measurement Modal --}}
<div class="modal fade" id="newGrowthMeasurementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>{{ __('translation.add_measurement') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.measurement_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="measurementDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.weight') }} (kg) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="growthWeight" step="0.1" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.height') }} (cm) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="growthHeight" step="0.1" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.head_circumference') }} (cm)</label>
                        <input type="number" class="form-control" id="headCircumference" step="0.1" min="0">
                        <small class="text-muted">{{ __('translation.for_infants_up_to_3_years') }}</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.notes') }}</label>
                        <textarea class="form-control" id="growthNotes" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-info" onclick="saveGrowthMeasurement()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- New Chronic Disease Modal --}}
<div class="modal fade" id="newChronicDiseaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-heartbeat me-2"></i>{{ __('translation.add_chronic_disease') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.disease_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="diseaseType" required>
                            <option value="">{{ __('translation.select_disease') }}</option>
                            @foreach(\App\Models\ChronicDiseaseType::where('is_active', true)->orderBy('category')->get()->groupBy('category') as $category => $diseases)
                                <optgroup label="{{ $category }}">
                                    @foreach($diseases as $disease)
                                        <option value="{{ $disease->id }}" data-icd="{{ $disease->icd11_code }}">
                                            {{ $disease->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.diagnosis_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="diagnosisDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.severity') }}</label>
                        <select class="form-select" id="diseaseSeverity">
                            <option value="mild">{{ __('translation.mild') }}</option>
                            <option value="moderate" selected>{{ __('translation.moderate') }}</option>
                            <option value="severe">{{ __('translation.severe') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.treatment_plan') }}</label>
                        <textarea class="form-control" id="diseaseTreatmentPlan" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.next_followup_date') }}</label>
                        <input type="date" class="form-control" id="nextFollowupDate">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.disease_status') }}</label>
                        <select class="form-select" id="diseaseStatus">
                            <option value="active" selected>{{ __('translation.active') }}</option>
                            <option value="in_remission">{{ __('translation.in_remission') }}</option>
                            <option value="resolved">{{ __('translation.resolved') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-warning" onclick="saveChronicDisease()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
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

    // Lab Test Type Selection Handler
    const labTestTypeSelect = document.getElementById('labTestType');
    if (labTestTypeSelect) {
        labTestTypeSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const unit = selected.dataset.unit || '-';
            const min = selected.dataset.min;
            const max = selected.dataset.max;
            
            document.getElementById('resultUnit').textContent = unit;
            
            if (min && max) {
                document.getElementById('normalRangeText').textContent = `${min} - ${max} ${unit}`;
                document.getElementById('normalRangeInfo').style.display = 'block';
            } else {
                document.getElementById('normalRangeInfo').style.display = 'none';
            }
        });
    }
});

// Medical Features Functions
async function saveLabTest() {
    const typeId = document.getElementById('labTestType').value;
    const testDate = document.getElementById('testDate').value;
    const resultValue = document.getElementById('resultValue').value;
    const notes = document.getElementById('labTestNotes').value;
    
    if (!typeId || !testDate || !resultValue) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    // Loading state
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    try {
        const response = await fetch('{{ route("patients.lab-tests.store", $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                lab_test_type_id: typeId,
                test_date: testDate,
                result_value: resultValue,
                notes: notes
            })
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newLabTestModal')).hide();
            SwalUtil.toast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            document.querySelectorAll('#newLabTestModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#newLabTestModal .invalid-feedback').forEach(el => el.remove());
            Object.keys(data.errors).forEach(field => {
                const fieldMap = {'lab_test_type_id': 'labTestType', 'test_date': 'testDate', 'result_value': 'resultValue', 'notes': 'labTestNotes'};
                const input = document.getElementById(fieldMap[field] || field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = data.errors[field][0];
                    input.after(feedback);
                }
            });
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    }
}

async function saveVaccination() {
    const typeId = document.getElementById('vaccinationType').value;
    const vaccinationDate = document.getElementById('vaccinationDate').value;
    
    if (!typeId || !vaccinationDate) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    // Loading state
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    try {
        const response = await fetch('{{ route("patients.vaccinations.store", $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                vaccination_type_id: typeId,
                vaccination_date: vaccinationDate,
                dose_number: document.getElementById('doseNumber').value,
                batch_number: document.getElementById('batchNumber').value,
                manufacturer: document.getElementById('manufacturer').value,
                site: document.getElementById('injectionSite').value,
                next_dose_due_date: document.getElementById('nextDoseDate').value,
                reaction_notes: document.getElementById('reactionNotes').value,
                status: 'completed'
            })
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newVaccinationModal')).hide();
            SwalUtil.toast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            document.querySelectorAll('#newVaccinationModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#newVaccinationModal .invalid-feedback').forEach(el => el.remove());
            Object.keys(data.errors).forEach(field => {
                const fieldMap = {'vaccination_type_id': 'vaccinationType', 'vaccination_date': 'vaccinationDate', 'dose_number': 'doseNumber', 'batch_number': 'batchNumber', 'site': 'injectionSite', 'next_dose_due_date': 'nextDoseDate', 'reaction_notes': 'reactionNotes'};
                const input = document.getElementById(fieldMap[field] || field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = data.errors[field][0];
                    input.after(feedback);
                }
            });
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

async function saveGrowthMeasurement() {
    const measurementDate = document.getElementById('measurementDate').value;
    const weight = document.getElementById('growthWeight').value;
    const height = document.getElementById('growthHeight').value;
    
    if (!measurementDate || !weight || !height) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    // Loading state
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    // Calculate age in months
    const birthDate = new Date('{{ $patient->birth_date }}');
    const measureDate = new Date(measurementDate);
    const ageMonths = Math.floor((measureDate - birthDate) / (1000 * 60 * 60 * 24 * 30.44));
    
    try {
        const response = await fetch('{{ route("patients.growth-charts.store", $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                measurement_date: measurementDate,
                age_months: ageMonths,
                weight_kg: weight,
                height_cm: height,
                head_circumference_cm: document.getElementById('headCircumference').value,
                notes: document.getElementById('growthNotes').value
            })
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newGrowthMeasurementModal')).hide();
            SwalUtil.toast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            document.querySelectorAll('#newGrowthMeasurementModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#newGrowthMeasurementModal .invalid-feedback').forEach(el => el.remove());
            Object.keys(data.errors).forEach(field => {
                const fieldMap = {'measurement_date': 'measurementDate', 'weight_kg': 'growthWeight', 'height_cm': 'growthHeight', 'head_circumference_cm': 'headCircumference', 'notes': 'growthNotes'};
                const input = document.getElementById(fieldMap[field] || field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = data.errors[field][0];
                    input.after(feedback);
                }
            });
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

async function saveChronicDisease() {
    const typeId = document.getElementById('diseaseType').value;
    const diagnosisDate = document.getElementById('diagnosisDate').value;
    
    if (!typeId || !diagnosisDate) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    // Loading state
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    try {
        const response = await fetch('{{ route("patients.chronic-diseases.store", $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                chronic_disease_type_id: typeId,
                diagnosis_date: diagnosisDate,
                severity: document.getElementById('diseaseSeverity').value,
                treatment_plan: document.getElementById('diseaseTreatmentPlan').value,
                next_followup_date: document.getElementById('nextFollowupDate').value,
                status: document.getElementById('diseaseStatus').value
            })
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newChronicDiseaseModal')).hide();
            SwalUtil.toast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            document.querySelectorAll('#newChronicDiseaseModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#newChronicDiseaseModal .invalid-feedback').forEach(el => el.remove());
            Object.keys(data.errors).forEach(field => {
                const fieldMap = {'chronic_disease_type_id': 'diseaseType', 'diagnosis_date': 'diagnosisDate', 'severity': 'diseaseSeverity', 'treatment_plan': 'diseaseTreatmentPlan', 'next_followup_date': 'nextFollowupDate', 'status': 'diseaseStatus'};
                const input = document.getElementById(fieldMap[field] || field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = data.errors[field][0];
                    input.after(feedback);
                }
            });
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

function viewLabTest(id) { console.log('View:', id); }
function viewDiseaseDetails(id) { console.log('View:', id); }
function addMonitoring(diseaseId) { console.log('Monitor:', diseaseId); }
</script>
@endpush
