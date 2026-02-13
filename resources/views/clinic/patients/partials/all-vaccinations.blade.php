@extends('layouts.clinic')

@section('content')
<div class="container-fluid py-4">
    {{-- Header with Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-syringe text-danger me-2"></i>
                {{ __('translation.all_vaccinations') }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.show', $patient->file_number) }}">{{ $patient->full_name }}</a></li>
                    <li class="breadcrumb-item active">{{ __('translation.vaccinations') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'vaccinations']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    {{-- Patient Info Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-lg bg-danger-subtle text-danger rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
                    <span class="badge bg-danger fs-6">{{ $vaccinations->total() }} {{ __('translation.vaccinations') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Vaccinations Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($vaccinations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                                        {{ $vaccination->vaccination_date->format('M d, Y') }}
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
                                                {{ $vaccination->next_dose_due_date->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $vaccination->status === 'completed' ? 'success' : ($vaccination->status === 'missed' ? 'danger' : 'warning') }}-subtle text-{{ $vaccination->status === 'completed' ? 'success' : ($vaccination->status === 'missed' ? 'danger' : 'warning') }}">
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
                                        <button class="btn btn-sm btn-outline-danger" onclick="viewVaccination({{ $vaccination->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($vaccinations->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $vaccinations->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">💉</div>
                    <h6 class="text-muted mb-2">{{ __('translation.no_vaccinations') }}</h6>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Include Vaccination modals --}}
@include('clinic.patients.partials.vaccination-modals')
@endsection

@push('scripts')
@include('clinic.patients.partials.medical-scripts')
@endpush
