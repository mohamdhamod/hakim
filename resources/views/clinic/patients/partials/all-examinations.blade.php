@extends('layout.home.main')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.examination.all_examinations') }}</h1>
            <p class="text-muted mb-0">{{ $patient->full_name }} - {{ $patient->file_number }}</p>
        </div>
        <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'examinations']) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
        </a>
    </div>

    <!-- Examinations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($examinations->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.examination.number') }}</th>
                                <th>{{ __('translation.examination.date') }}</th>
                                <th>{{ __('translation.examination.chief_complaint') }}</th>
                                <th>{{ __('translation.examination.diagnosis') }}</th>
                                <th width="150">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($examinations as $examination)
                                <tr>
                                    <td class="small">
                                        <span class="badge bg-secondary">{{ $examination->examination_number }}</span>
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $examination->examination_date->format('Y-m-d') }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $examination->examination_date->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="small">
                                        {{ Str::limit($examination->chief_complaint, 50) ?: '-' }}
                                    </td>
                                    <td class="small">
                                        @if($examination->diagnosis)
                                            <span class="text-dark">{{ Str::limit($examination->diagnosis, 50) }}</span>
                                            @if($examination->icd_code)
                                                <br><small class="text-muted">ICD: {{ $examination->icd_code }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-info view-btn" data-type="examination" data-model='@json($examination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($examination->clinic_id === $clinic->id)
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="examination" data-model='@json($examination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="examination" data-model='@json($examination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                    @foreach($examinations as $examination)
                        <div class="card mb-3 border rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-secondary me-1">{{ $examination->examination_number }}</span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar text-primary me-1"></i>{{ $examination->examination_date->format('Y-m-d') }}
                                            <i class="fas fa-clock ms-2"></i> {{ $examination->examination_date->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="small mt-2">
                                    <div class="mb-2">
                                        <span class="text-muted d-block fw-bold">{{ __('translation.examination.chief_complaint') }}</span>
                                        {{ Str::limit($examination->chief_complaint, 80) ?: '-' }}
                                    </div>
                                    <div class="mb-1">
                                        <span class="text-muted d-block fw-bold">{{ __('translation.examination.diagnosis') }}</span>
                                        @if($examination->diagnosis)
                                            {{ Str::limit($examination->diagnosis, 80) }}
                                            @if($examination->icd_code)
                                                <br><small class="text-muted">ICD: {{ $examination->icd_code }}</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="row g-1 mt-3 pt-2 border-top">
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-info w-100 view-btn" data-type="examination" data-model='@json($examination)'>
                                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                        </button>
                                    </div>
                                    @if($examination->clinic_id === $clinic->id)
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="examination" data-model='@json($examination)'>
                                            <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="examination" data-model='@json($examination)'>
                                            <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <a href="{{ route('clinic.examinations.print', $examination->id) }}" class="btn btn-sm btn-secondary w-100" target="_blank">
                                            <i class="fas fa-print me-1"></i>{{ __('translation.common.print') }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($examinations->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $examinations->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">{{ __('translation.examination.no_examinations') }}</h5>
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

{{-- Examination Modals --}}
@include('clinic.patients.partials.examination-modals')

{{-- Confirm Delete Modal --}}
@include('modules.confirm')
@endsection

@push('scripts')
@include('modules.i18n')
@include('clinic.patients.partials.examination-medical-scripts')
@endpush
