@extends('layout.home.main')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.all_surgical_history') }}</h1>
            <p class="text-muted mb-0">{{ $patient->full_name }} - {{ $patient->file_number }}</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2">
            @if(isset($clinic))
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            @endif
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'surgical-history']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    <!-- Surgical History Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($surgeries->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.surgical_history.procedure_name') }}</th>
                                <th>{{ __('translation.surgical_history.procedure_date') }}</th>
                                <th>{{ __('translation.surgical_history.hospital') }}</th>
                                <th>{{ __('translation.surgical_history.surgeon') }}</th>
                                <th>{{ __('translation.surgical_history.indication') }}</th>
                                <th>{{ __('translation.surgical_history.complications') }}</th>
                                <th>{{ __('translation.surgical_history.notes') }}</th>
                                <th width="120">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surgeries as $surgery)
                                <tr>
                                    <td class="fw-semibold">{{ $surgery->procedure_name }}</td>
                                    <td class="small">
                                        @if($surgery->procedure_date)
                                            <i class="fas fa-calendar text-primary me-1"></i>
                                            {{ $surgery->procedure_date->format('Y-m-d') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $surgery->hospital ?: '-' }}</td>
                                    <td class="small">{{ $surgery->surgeon ?: '-' }}</td>
                                    <td class="small">{{ $surgery->indication ? Str::limit($surgery->indication, 40) : '-' }}</td>
                                    <td class="small">
                                        @if($surgery->complications)
                                            <span class="badge bg-warning text-dark">{{ Str::limit($surgery->complications, 30) }}</span>
                                        @else
                                            <span class="text-muted">{{ __('translation.surgical_history.none') }}</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $surgery->notes ? Str::limit($surgery->notes, 30) : '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-info view-btn" data-type="surgery" data-model='@json($surgery)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($surgery->clinic_id === $clinic->id)
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="surgery" data-model='@json($surgery)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="surgery" data-model='@json($surgery)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                    @foreach($surgeries as $surgery)
                        <div class="card mb-3 border rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $surgery->procedure_name }}</h6>
                                        @if($surgery->hospital)
                                            <small class="text-muted"><i class="fas fa-hospital me-1"></i>{{ $surgery->hospital }}</small>
                                        @endif
                                    </div>
                                    @if($surgery->complications)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle"></i> {{ __('translation.surgical_history.complications') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="row g-2 small mt-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.surgical_history.procedure_date') }}</span>
                                        @if($surgery->procedure_date)
                                            <i class="fas fa-calendar text-primary me-1"></i>{{ $surgery->procedure_date->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.surgical_history.surgeon') }}</span>
                                        {{ $surgery->surgeon ?: '-' }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.surgical_history.indication') }}</span>
                                        {{ $surgery->indication ? Str::limit($surgery->indication, 30) : '-' }}
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.surgical_history.complications') }}</span>
                                        {{ $surgery->complications ? Str::limit($surgery->complications, 30) : __('translation.surgical_history.none') }}
                                    </div>
                                </div>
                                <div class="row g-1 mt-3 pt-2 border-top">
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-info w-100 view-btn" data-type="surgery" data-model='@json($surgery)'>
                                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                        </button>
                                    </div>
                                    @if($surgery->clinic_id === $clinic->id)
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="surgery" data-model='@json($surgery)'>
                                            <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="surgery" data-model='@json($surgery)'>
                                            <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($surgeries->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $surgeries->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-procedures text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">{{ __('translation.surgical_history.no_records') }}</h5>
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

{{-- Include Surgical History modals --}}
@include('clinic.patients.partials.surgical-history-modals')
{{-- Confirm Delete Modal --}}
@include('modules.confirm')
@endsection

@push('scripts')
@include('modules.i18n')
@include('clinic.patients.partials.surgical-history-scripts')
@endpush
