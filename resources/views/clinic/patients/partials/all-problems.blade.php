@extends('layout.home.main')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.all_problems') }}</h1>
            <p class="text-muted mb-0">{{ $patient->full_name }} - {{ $patient->file_number }}</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2">
            @if(isset($clinic))
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            @endif
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'problem-list']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    <!-- Problem List Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($problems->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.problem_list.problem') }}</th>
                                <th>{{ __('translation.problem_list.icd_code') }}</th>
                                <th>{{ __('translation.problem_list.onset_date') }}</th>
                                <th>{{ __('translation.problem_list.resolved_date') }}</th>
                                <th>{{ __('translation.problem_list.status') }}</th>
                                <th>{{ __('translation.problem_list.severity') }}</th>
                                <th>{{ __('translation.problem_list.notes') }}</th>
                                <th width="120">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($problems as $problem)
                                <tr>
                                    <td class="fw-semibold">{{ $problem->title }}</td>
                                    <td class="small">
                                        @if($problem->icd_code)
                                            <span class="badge bg-info text-dark">{{ $problem->icd_code }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        @if($problem->onset_date)
                                            <i class="fas fa-calendar text-primary me-1"></i>
                                            {{ $problem->onset_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small">
                                        @if($problem->resolved_date)
                                            <i class="fas fa-calendar-check text-success me-1"></i>
                                            {{ $problem->resolved_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $problem->status_badge_class }}">
                                            {{ $problem->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($problem->severity)
                                            <span class="badge {{ $problem->severity_badge_class }}">{{ $problem->severity_label }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $problem->notes ? Str::limit($problem->notes, 30) : '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-info view-btn" data-type="problem" data-model='@json($problem)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($problem->clinic_id === $clinic->id)
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="problem" data-model='@json($problem)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="problem" data-model='@json($problem)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                    @foreach($problems as $problem)
                        <div class="card mb-3 border rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $problem->title }}</h6>
                                        @if($problem->icd_code)
                                            <small class="badge bg-info text-dark">{{ $problem->icd_code }}</small>
                                        @endif
                                    </div>
                                    <span class="badge {{ $problem->status_badge_class }}">
                                        {{ $problem->status_label }}
                                    </span>
                                </div>
                                <div class="row g-2 small mt-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.problem_list.onset_date') }}</span>
                                        @if($problem->onset_date)
                                            <i class="fas fa-calendar text-primary me-1"></i>{{ $problem->onset_date->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.problem_list.severity') }}</span>
                                        @if($problem->severity)
                                            <span class="badge {{ $problem->severity_badge_class }}">{{ $problem->severity_label }}</span>
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.problem_list.resolved_date') }}</span>
                                        @if($problem->resolved_date)
                                            <i class="fas fa-calendar-check text-success me-1"></i>{{ $problem->resolved_date->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.problem_list.notes') }}</span>
                                        {{ $problem->notes ? Str::limit($problem->notes, 30) : '-' }}
                                    </div>
                                </div>
                                <div class="row g-1 mt-3 pt-2 border-top">
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-info w-100 view-btn" data-type="problem" data-model='@json($problem)'>
                                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                        </button>
                                    </div>
                                    @if($problem->clinic_id === $clinic->id)
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="problem" data-model='@json($problem)'>
                                            <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="problem" data-model='@json($problem)'>
                                            <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($problems->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $problems->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">{{ __('translation.problem_list.no_records') }}</h5>
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

{{-- Include Problem List modals --}}
@include('clinic.patients.partials.problem-list-modals')
{{-- Confirm Delete Modal --}}
@include('modules.confirm')
@endsection

@push('scripts')
@include('modules.i18n')
@include('clinic.patients.partials.problem-list-scripts')
@endpush
