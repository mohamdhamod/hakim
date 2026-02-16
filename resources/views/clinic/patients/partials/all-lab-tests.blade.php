@extends('layout.home.main')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.all_lab_tests') }}</h1>
            <p class="text-muted mb-0">{{ $patient->full_name }} - {{ $patient->file_number }}</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2">
            @if(isset($clinic))
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            @endif
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'lab-tests']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    <!-- Lab Tests Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($labTests->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
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
                                        {{ $labTest->test_date->format('Y-m-d') }}
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
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-info view-btn" data-type="labTest" data-model='@json($labTest)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($labTest->clinic_id === $clinic->id)
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="labTest" data-model='@json($labTest)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="labTest" data-model='@json($labTest)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                    @foreach($labTests as $labTest)
                        <div class="card mb-3 border rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $labTest->labTestType->name }}</h6>
                                        @if($labTest->labTestType->category)
                                            <small class="text-muted">{{ $labTest->labTestType->category }}</small>
                                        @endif
                                    </div>
                                    @if($labTest->isAbnormal())
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="fas fa-exclamation-circle"></i> {{ __('translation.abnormal') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-check-circle"></i> {{ __('translation.normal') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="row g-2 small mt-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.result') }}</span>
                                        <span class="fw-bold {{ $labTest->isAbnormal() ? 'text-danger' : 'text-success' }}">
                                            {{ $labTest->result_value }}
                                            @if($labTest->labTestType->unit) {{ $labTest->labTestType->unit }} @endif
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.normal_range') }}</span>
                                        {{ $labTest->labTestType->normal_range_text ?? ($labTest->labTestType->normal_range_min && $labTest->labTestType->normal_range_max ? $labTest->labTestType->normal_range_min . ' - ' . $labTest->labTestType->normal_range_max : '-') }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.test_date') }}</span>
                                        <i class="fas fa-calendar text-primary me-1"></i>{{ $labTest->test_date->format('Y-m-d') }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.status') }}</span>
                                        <span class="badge bg-{{ $labTest->status === 'completed' ? 'success' : 'warning' }}-subtle text-{{ $labTest->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ __('translation.' . $labTest->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row g-1 mt-3 pt-2 border-top">
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-info w-100 view-btn" data-type="labTest" data-model='@json($labTest)'>
                                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                        </button>
                                    </div>
                                    @if($labTest->clinic_id === $clinic->id)
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="labTest" data-model='@json($labTest)'>
                                            <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="labTest" data-model='@json($labTest)'>
                                            <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($labTests->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $labTests->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-flask text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">{{ __('translation.no_lab_tests') }}</h5>
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

{{-- Include Lab Test modals and scripts if needed --}}
@include('clinic.patients.partials.lab-test-modals')
{{-- Confirm Delete Modal --}}
@include('modules.confirm')
@endsection

@push('scripts')
@include('modules.i18n')
@include('clinic.patients.partials.lab-test-medical-scripts')
@endpush
