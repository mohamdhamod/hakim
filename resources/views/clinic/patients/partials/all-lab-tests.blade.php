@extends('layouts.clinic')

@section('content')
<div class="container-fluid py-4">
    {{-- Header with Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-flask text-primary me-2"></i>
                {{ __('translation.all_lab_tests') }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.show', $patient->file_number) }}">{{ $patient->full_name }}</a></li>
                    <li class="breadcrumb-item active">{{ __('translation.lab_tests') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'lab-tests']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    {{-- Patient Info Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-lg bg-primary-subtle text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
                    <span class="badge bg-primary fs-6">{{ $labTests->total() }} {{ __('translation.lab_tests') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Lab Tests Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($labTests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.test_name') }}</th>
                                <th>{{ __('translation.result') }}</th>
                                <th>{{ __('translation.normal_range') }}</th>
                                <th>{{ __('translation.test_date') }}</th>
                                <th>{{ __('translation.status') }}</th>
                                <th>{{ __('translation.interpretation') }}</th>
                                <th width="100">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($labTests as $labTest)
                                <tr>
                                    <td>
                                        <strong>{{ $labTest->labTestType->name }}</strong>
                                        @if($labTest->labTestType->category)
                                            <br><small class="text-muted">{{ $labTest->labTestType->category }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $labTest->isAbnormal() ? 'text-danger' : 'text-success' }}">
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
                                    <td class="small">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $labTest->test_date->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $labTest->status === 'completed' ? 'success' : 'warning' }}-subtle text-{{ $labTest->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ __('translation.' . $labTest->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($labTest->isAbnormal())
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

                {{-- Pagination --}}
                @if($labTests->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $labTests->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">🧪</div>
                    <h6 class="text-muted mb-2">{{ __('translation.no_lab_tests') }}</h6>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Include Lab Test modals and scripts if needed --}}
@include('clinic.patients.partials.lab-test-modals')
@endsection

@push('scripts')
@include('clinic.patients.partials.medical-scripts')
@endpush
