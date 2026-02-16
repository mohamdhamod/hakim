@extends('layout.home.main')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.all_chronic_diseases') }}</h1>
            <p class="text-muted mb-0">{{ $patient->full_name }} - {{ $patient->file_number }}</p>
        </div>
        <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'chronic-diseases']) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
        </a>
    </div>

    <!-- Chronic Diseases Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($chronicDiseases->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.disease_type') }}</th>
                                <th>{{ __('translation.diagnosis_date') }}</th>
                                <th>{{ __('translation.severity') }}</th>
                                <th>{{ __('translation.disease_status') }}</th>
                                <th>{{ __('translation.treatment_plan') }}</th>
                                <th>{{ __('translation.next_followup') }}</th>
                                <th width="120">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($chronicDiseases as $disease)
                                @php
                                    $statusColors = ['active' => 'danger', 'in_remission' => 'warning', 'resolved' => 'success'];
                                    $statusColor = $statusColors[$disease->status] ?? 'secondary';
                                    $severityColors = ['severe' => 'danger', 'moderate' => 'warning', 'mild' => 'success'];
                                    $severityColor = $severityColors[$disease->severity] ?? 'secondary';
                                @endphp
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
                                        {{ $disease->diagnosis_date->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $severityColor }}">
                                            {{ __('translation.' . $disease->severity) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }}">
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
                                                {{ $disease->next_followup_date->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-info view-btn" data-type="chronicDisease" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($disease->clinic_id === $clinic->id)
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="chronicDisease" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success monitoring-btn" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.add_monitoring') }}">
                                                <i class="fas fa-chart-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="chronicDisease" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="d-md-none p-3">
                    @foreach($chronicDiseases as $disease)
                        @php
                            $statusColors = ['active' => 'danger', 'in_remission' => 'warning', 'resolved' => 'success'];
                            $statusColor = $statusColors[$disease->status] ?? 'secondary';
                            $severityColors = ['severe' => 'danger', 'moderate' => 'warning', 'mild' => 'success'];
                            $severityColor = $severityColors[$disease->severity] ?? 'secondary';
                        @endphp
                        <div class="card mb-3 border rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $disease->chronicDiseaseType->name }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> {{ $disease->chronicDiseaseType->category }}
                                            @if($disease->chronicDiseaseType->icd11_code)
                                                | ICD-11: {{ $disease->chronicDiseaseType->icd11_code }}
                                            @endif
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }}">
                                        @if($disease->status === 'active')
                                            <i class="fas fa-exclamation-circle"></i>
                                        @elseif($disease->status === 'in_remission')
                                            <i class="fas fa-pause-circle"></i>
                                        @else
                                            <i class="fas fa-check-circle"></i>
                                        @endif
                                        {{ __('translation.' . $disease->status) }}
                                    </span>
                                </div>
                                <div class="row g-2 small mt-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.diagnosis_date') }}</span>
                                        <i class="fas fa-calendar-check text-primary me-1"></i>{{ $disease->diagnosis_date->format('Y-m-d') }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.severity') }}</span>
                                        <span class="badge bg-{{ $severityColor }}">{{ __('translation.' . $disease->severity) }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.treatment_plan') }}</span>
                                        {{ Str::limit($disease->treatment_plan, 40) ?: '-' }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.next_followup') }}</span>
                                        @if($disease->next_followup_date)
                                            <span class="{{ $disease->next_followup_date->isPast() ? 'text-danger' : 'text-warning' }}">
                                                <i class="fas fa-bell me-1"></i>{{ $disease->next_followup_date->format('Y-m-d') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="row g-1 mt-3 pt-2 border-top">
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-info w-100 view-btn" data-type="chronicDisease" data-model='@json($disease)'>
                                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                        </button>
                                    </div>
                                    @if($disease->clinic_id === $clinic->id)
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="chronicDisease" data-model='@json($disease)'>
                                            <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="chronicDisease" data-model='@json($disease)'>
                                            <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-sm btn-success w-100 monitoring-btn" data-model='@json($disease)'>
                                            <i class="fas fa-chart-line me-1"></i>{{ __('translation.add_monitoring') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($chronicDiseases->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $chronicDiseases->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-heartbeat text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">{{ __('translation.no_chronic_diseases') }}</h5>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.bg-warning-subtle { background-color: rgba(255, 193, 7, 0.15) !important; }
.bg-success-subtle { background-color: rgba(25, 135, 84, 0.15) !important; }
.bg-info-subtle { background-color: rgba(13, 202, 240, 0.15) !important; }
.bg-danger-subtle { background-color: rgba(220, 53, 69, 0.15) !important; }
.bg-primary-subtle { background-color: rgba(13, 110, 253, 0.15) !important; }
</style>

{{-- Include Chronic Disease modals --}}
@include('clinic.patients.partials.chronic-disease-modals')
{{-- Confirm Delete Modal --}}
@include('modules.confirm')
@endsection

@push('scripts')
@include('modules.i18n')
@include('clinic.patients.partials.chronic-disease-medical-scripts')
@endpush
