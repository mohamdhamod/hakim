@extends('layouts.clinic')

@section('content')
<div class="container-fluid py-4">
    {{-- Header with Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-chart-line text-info me-2"></i>
                {{ __('translation.all_growth_measurements') }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.show', $patient->file_number) }}">{{ $patient->full_name }}</a></li>
                    <li class="breadcrumb-item active">{{ __('translation.growth_chart') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.print.comprehensive', $patient->file_number) }}" class="btn btn-outline-success" target="_blank">
                <i class="fas fa-print me-2"></i>{{ __('translation.print_comprehensive_report') }}
            </a>
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'growth']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    {{-- Patient Info Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-lg bg-info-subtle text-info rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
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
                    <span class="badge bg-info fs-6">{{ $measurements->total() }} {{ __('translation.measurements') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Growth Measurements Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($measurements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                                        {{ $measurement->measurement_date->format('M d, Y') }}
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
                                            <span class="badge bg-{{ $measurement->interpretation === 'normal' ? 'success' : ($measurement->interpretation === 'overweight' || $measurement->interpretation === 'obese' ? 'danger' : 'warning') }}-subtle text-{{ $measurement->interpretation === 'normal' ? 'success' : ($measurement->interpretation === 'overweight' || $measurement->interpretation === 'obese' ? 'danger' : 'warning') }}">
                                                {{ __('translation.' . $measurement->interpretation) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewGrowthMeasurement({{ $measurement->id }})" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($measurements->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $measurements->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">📏</div>
                    <h6 class="text-muted mb-2">{{ __('translation.no_measurements') }}</h6>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Include Growth Measurement modals --}}
@include('clinic.patients.partials.growth-measurement-modals')
@endsection

@push('scripts')
@include('clinic.patients.partials.medical-scripts')
@endpush
