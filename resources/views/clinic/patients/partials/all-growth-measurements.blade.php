@extends('layout.home.main')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.all_growth_measurements') }}</h1>
            <p class="text-muted mb-0">{{ $patient->full_name }} - {{ $patient->file_number }}</p>
        </div>
        <div class="d-flex flex-column flex-sm-row gap-2">
            @if(isset($clinic))
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            @endif
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'growth']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    <!-- Growth Measurements Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($measurements->count() > 0)
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.measurement_date') }}</th>
                                <th>{{ __('translation.age') }}</th>
                                <th>{{ __('translation.weight') }}</th>
                                <th>{{ __('translation.height') }}</th>
                                <th>{{ __('translation.bmi') }}</th>
                                <th>{{ __('translation.head_circumference') }}</th>
                                <th>{{ __('translation.interpretation') }}</th>
                                <th width="100">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($measurements as $measurement)
                                <tr>
                                    <td class="small">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $measurement->measurement_date->format('Y-m-d') }}
                                    </td>
                                    <td class="small">
                                        @if($measurement->age_months)
                                            @if($measurement->age_months < 12)
                                                {{ $measurement->age_months }} {{ __('translation.months') }}
                                            @else
                                                {{ floor($measurement->age_months / 12) }} {{ __('translation.years') }}
                                                @if($measurement->age_months % 12 > 0)
                                                    {{ $measurement->age_months % 12 }} {{ __('translation.months') }}
                                                @endif
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ $measurement->weight_kg }}</strong> <small>kg</small>
                                        @if($measurement->weight_percentile)
                                            <br><small class="text-muted">{{ round($measurement->weight_percentile) }}%</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-info">{{ $measurement->height_cm }}</strong> <small>cm</small>
                                        @if($measurement->height_percentile)
                                            <br><small class="text-muted">{{ round($measurement->height_percentile) }}%</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong style="color: #6610f2;">{{ round($measurement->bmi, 1) }}</strong>
                                        @if($measurement->bmi_percentile)
                                            <br><small class="text-muted">{{ round($measurement->bmi_percentile) }}%</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($measurement->head_circumference_cm)
                                            <strong class="text-danger">{{ $measurement->head_circumference_cm }}</strong> <small>cm</small>
                                            @if($measurement->head_circumference_percentile)
                                                <br><small class="text-muted">{{ round($measurement->head_circumference_percentile) }}%</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($measurement->interpretation)
                                            @php
                                                $interpColors = ['normal' => 'success', 'underweight' => 'warning', 'overweight' => 'danger', 'obese' => 'danger'];
                                                $interpColor = $interpColors[$measurement->interpretation] ?? 'info';
                                            @endphp
                                            <span class="badge bg-{{ $interpColor }}-subtle text-{{ $interpColor }}">
                                                {{ __('translation.' . $measurement->interpretation) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-nowrap">
                                            <button type="button" class="btn btn-sm btn-info view-btn" data-type="growth" data-model='@json($measurement)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($measurement->clinic_id === $clinic->id)
                                            <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="growth" data-model='@json($measurement)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="growth" data-model='@json($measurement)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                    @foreach($measurements as $measurement)
                        <div class="card mb-3 border rounded-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-calendar text-primary me-1"></i>{{ $measurement->measurement_date->format('Y-m-d') }}
                                        </h6>
                                        <small class="text-muted">
                                            @if($measurement->age_months)
                                                @if($measurement->age_months < 12)
                                                    {{ $measurement->age_months }} {{ __('translation.months') }}
                                                @else
                                                    {{ floor($measurement->age_months / 12) }} {{ __('translation.years') }}
                                                    @if($measurement->age_months % 12 > 0)
                                                        {{ $measurement->age_months % 12 }} {{ __('translation.months') }}
                                                    @endif
                                                @endif
                                            @endif
                                        </small>
                                    </div>
                                    @if($measurement->interpretation)
                                        @php
                                            $interpColors = ['normal' => 'success', 'underweight' => 'warning', 'overweight' => 'danger', 'obese' => 'danger'];
                                            $interpColor = $interpColors[$measurement->interpretation] ?? 'info';
                                        @endphp
                                        <span class="badge bg-{{ $interpColor }}-subtle text-{{ $interpColor }}">
                                            {{ __('translation.' . $measurement->interpretation) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="row g-2 small mt-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.weight') }}</span>
                                        <strong class="text-primary">{{ $measurement->weight_kg }}</strong> <small>kg</small>
                                        @if($measurement->weight_percentile)
                                            <small class="text-muted ms-1">({{ round($measurement->weight_percentile) }}%)</small>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.height') }}</span>
                                        <strong class="text-info">{{ $measurement->height_cm }}</strong> <small>cm</small>
                                        @if($measurement->height_percentile)
                                            <small class="text-muted ms-1">({{ round($measurement->height_percentile) }}%)</small>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.bmi') }}</span>
                                        <strong style="color: #6610f2;">{{ round($measurement->bmi, 1) }}</strong>
                                        @if($measurement->bmi_percentile)
                                            <small class="text-muted ms-1">({{ round($measurement->bmi_percentile) }}%)</small>
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">{{ __('translation.head_circumference') }}</span>
                                        @if($measurement->head_circumference_cm)
                                            <strong class="text-danger">{{ $measurement->head_circumference_cm }}</strong> <small>cm</small>
                                            @if($measurement->head_circumference_percentile)
                                                <small class="text-muted ms-1">({{ round($measurement->head_circumference_percentile) }}%)</small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="row g-1 mt-3 pt-2 border-top">
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-info w-100 view-btn" data-type="growth" data-model='@json($measurement)'>
                                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                        </button>
                                    </div>
                                    @if($measurement->clinic_id === $clinic->id)
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="growth" data-model='@json($measurement)'>
                                            <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="growth" data-model='@json($measurement)'>
                                            <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($measurements->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $measurements->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-line text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">{{ __('translation.no_measurements') }}</h5>
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

{{-- Include Growth Measurement modals --}}
@include('clinic.patients.partials.growth-measurement-modals')
{{-- Confirm Delete Modal --}}
@include('modules.confirm')
@endsection

@push('scripts')
@include('modules.i18n')
@include('clinic.patients.partials.growth-measurement-medical-scripts')
@endpush
