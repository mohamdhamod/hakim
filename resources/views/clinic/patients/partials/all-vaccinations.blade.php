@extends('layout.home.main')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.all_vaccinations') }}</h1>
            <p class="text-muted mb-0">{{ $patient->full_name }} - {{ $patient->file_number }}</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2">
            @if(isset($clinic))
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            @endif
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'vaccinations']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    <!-- Vaccinations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($vaccinations->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.vaccination_type') }}</th>
                                <th>{{ __('translation.disease_prevented') }}</th>
                                <th>{{ __('translation.vaccination_date') }}</th>
                                <th>{{ __('translation.dose_number') }}</th>
                                <th>{{ __('translation.batch_number') }}</th>
                                <th>{{ __('translation.next_dose_date') }}</th>
                                <th>{{ __('translation.status') }}</th>
                                <th width="100">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vaccinations as $vaccination)
                                @php
                                    $statusColors = ['completed' => 'success', 'scheduled' => 'warning', 'missed' => 'danger'];
                                    $statusColor = $statusColors[$vaccination->status] ?? 'secondary';
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $vaccination->vaccinationType->name }}</strong>
                                        @if($vaccination->manufacturer)
                                            <br><small class="text-muted">{{ $vaccination->manufacturer }}</small>
                                        @endif
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-shield-virus text-danger me-1"></i>
                                        {{ $vaccination->vaccinationType->disease_prevented }}
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $vaccination->vaccination_date->format('Y-m-d') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-subtle text-info">
                                            {{ __('translation.dose') }} {{ $vaccination->dose_number }}
                                        </span>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $vaccination->batch_number ?: '-' }}
                                    </td>
                                    <td class="small">
                                        @if($vaccination->next_dose_due_date)
                                            <span class="{{ $vaccination->next_dose_due_date->isPast() ? 'text-danger' : 'text-warning' }}">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $vaccination->next_dose_due_date->format('Y-m-d') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }}">
                                            @if($vaccination->status === 'completed')
                                                <i class="fas fa-check-circle"></i>
                                            @elseif($vaccination->status === 'missed')
                                                <i class="fas fa-times-circle"></i>
                                            @else
                                                <i class="fas fa-clock"></i>
                                            @endif
                                            {{ __('translation.' . $vaccination->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-info view-btn" data-type="vaccination" data-model='@json($vaccination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($vaccination->clinic_id === $clinic->id)
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="vaccination" data-model='@json($vaccination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="vaccination" data-model='@json($vaccination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                    @foreach($vaccinations as $vaccination)
                        @php
                            $statusColors = ['completed' => 'success', 'scheduled' => 'warning', 'missed' => 'danger'];
                            $statusColor = $statusColors[$vaccination->status] ?? 'secondary';
                        @endphp
                        <div class="card mb-3 border rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $vaccination->vaccinationType->name }}</h6>
                                        @if($vaccination->manufacturer)
                                            <small class="text-muted">{{ $vaccination->manufacturer }}</small>
                                        @endif
                                    </div>
                                    <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }}">
                                        @if($vaccination->status === 'completed')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($vaccination->status === 'missed')
                                            <i class="fas fa-times-circle"></i>
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                        {{ __('translation.' . $vaccination->status) }}
                                    </span>
                                </div>
                                <div class="row g-2 small mt-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.disease_prevented') }}</span>
                                        <i class="fas fa-shield-virus text-danger me-1"></i>{{ $vaccination->vaccinationType->disease_prevented }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.vaccination_date') }}</span>
                                        <i class="fas fa-calendar text-primary me-1"></i>{{ $vaccination->vaccination_date->format('Y-m-d') }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.dose_number') }}</span>
                                        <span class="badge bg-info-subtle text-info">{{ __('translation.dose') }} {{ $vaccination->dose_number }}</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.batch_number') }}</span>
                                        {{ $vaccination->batch_number ?: '-' }}
                                    </div>
                                    <div class="col-12">
                                        <span class="text-muted d-block">{{ __('translation.next_dose_date') }}</span>
                                        @if($vaccination->next_dose_due_date)
                                            <span class="{{ $vaccination->next_dose_due_date->isPast() ? 'text-danger' : 'text-warning' }}">
                                                <i class="fas fa-clock me-1"></i>{{ $vaccination->next_dose_due_date->format('Y-m-d') }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="row g-1 mt-3 pt-2 border-top">
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-info w-100 view-btn" data-type="vaccination" data-model='@json($vaccination)'>
                                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                        </button>
                                    </div>
                                    @if($vaccination->clinic_id === $clinic->id)
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="vaccination" data-model='@json($vaccination)'>
                                            <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="vaccination" data-model='@json($vaccination)'>
                                            <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($vaccinations->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $vaccinations->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-syringe text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">{{ __('translation.no_vaccinations') }}</h5>
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

{{-- Include Vaccination modals --}}
@include('clinic.patients.partials.vaccination-modals')
{{-- Confirm Delete Modal --}}
@include('modules.confirm')
@endsection

@push('scripts')
@include('modules.i18n')
@include('clinic.patients.partials.vaccination-medical-scripts')
@endpush
