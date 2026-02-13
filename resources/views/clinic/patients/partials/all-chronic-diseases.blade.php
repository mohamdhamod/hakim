@extends('layouts.clinic')

@section('content')
<div class="container-fluid py-4">
    {{-- Header with Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-heartbeat text-warning me-2"></i>
                {{ __('translation.all_chronic_diseases') }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.show', $patient->file_number) }}">{{ $patient->full_name }}</a></li>
                    <li class="breadcrumb-item active">{{ __('translation.chronic_diseases') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'chronic-diseases']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    {{-- Patient Info Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-lg bg-warning-subtle text-warning rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $patient->full_name }}</h6>
                    <small class="text-muted">
                        {{ $patient->file_number }} | 
                        {{ $patient->age ? $patient->age . ' ' . __('translation.patient.years') : '-' }} |
                        {{ $patient->gender_label }}
                    </small>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-warning text-dark fs-6">{{ $chronicDiseases->total() }} {{ __('translation.chronic_diseases') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Chronic Diseases Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($chronicDiseases->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.disease_type') }}</th>
                                <th>{{ __('translation.diagnosis_date') }}</th>
                                <th>{{ __('translation.severity') }}</th>
                                <th>{{ __('translation.disease_status') }}</th>
                                <th>{{ __('translation.treatment_plan') }}</th>
                                <th>{{ __('translation.next_followup') }}</th>
                                <th width="150">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chronicDiseases as $disease)
                                <tr>
                                    <td>
                                        <strong>{{ $disease->chronicDiseaseType->name }}</strong>
                                        <br><small class="text-muted">
                                            <i class="fas fa-tag"></i> {{ $disease->chronicDiseaseType->category }}
                                            @if($disease->chronicDiseaseType->icd11_code)
                                                | ICD-11: {{ $disease->chronicDiseaseType->icd11_code }}
                                            @endif
                                        </small>
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-calendar-check text-primary me-1"></i>
                                        {{ $disease->diagnosis_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $disease->severity === 'severe' ? 'danger' : ($disease->severity === 'moderate' ? 'warning' : 'success') }}">
                                            {{ __('translation.' . $disease->severity) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}-subtle text-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}">
                                            @if($disease->status === 'active')
                                                <i class="fas fa-exclamation-circle"></i>
                                            @elseif($disease->status === 'in_remission')
                                                <i class="fas fa-pause-circle"></i>
                                            @else
                                                <i class="fas fa-check-circle"></i>
                                            @endif
                                            {{ __('translation.' . $disease->status) }}
                                        </span>
                                    </td>
                                    <td class="small">
                                        {{ Str::limit($disease->treatment_plan, 50) ?: '-' }}
                                    </td>
                                    <td class="small">
                                        @if($disease->next_followup_date)
                                            <span class="{{ $disease->next_followup_date->isPast() ? 'text-danger' : 'text-warning' }}">
                                                <i class="fas fa-bell me-1"></i>
                                                {{ $disease->next_followup_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-warning me-1" onclick="viewDiseaseDetails({{ $disease->id }})" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" onclick="addMonitoring({{ $disease->id }})" title="{{ __('translation.add_monitoring') }}">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($chronicDiseases->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $chronicDiseases->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">🏥</div>
                    <h6 class="text-muted mb-2">{{ __('translation.no_chronic_diseases') }}</h6>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Include Chronic Disease modals --}}
@include('clinic.patients.partials.chronic-disease-modals')
@endsection

@push('scripts')
@include('clinic.patients.partials.medical-scripts')
@endpush
