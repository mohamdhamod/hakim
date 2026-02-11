{{-- Growth Measurements Section (For Children Only) --}}
@if($patient->date_of_birth && $patient->age < 18)
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #4facfe10 0%, #00f2fe10 100%);">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-chart-line text-info me-2"></i>
            {{ __('translation.growth_chart') }}
            @if($patient->growthMeasurements && $patient->growthMeasurements->count() > 0)
                <span class="badge bg-info ms-2">{{ $patient->growthMeasurements->count() }}</span>
            @endif
        </h5>
        <div class="d-flex gap-2 align-items-center">
            @if($patient->growthMeasurements && $patient->growthMeasurements->count() >= 2)
            <ul class="nav nav-pills nav-sm" id="growthTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-1 px-3 small" id="growth-table-tab" data-bs-toggle="pill" data-bs-target="#growthTablePane" type="button" role="tab">
                        <i class="fas fa-table me-1"></i>{{ __('translation.common.table') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-1 px-3 small" id="growth-charts-tab" data-bs-toggle="pill" data-bs-target="#growthChartsPane" type="button" role="tab">
                        <i class="fas fa-chart-area me-1"></i>{{ __('translation.view_charts') }}
                    </button>
                </li>
            </ul>
            @endif
            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#newGrowthMeasurementModal">
                <i class="fas fa-plus me-2"></i>{{ __('translation.add_measurement') }}
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($patient->growthMeasurements && $patient->growthMeasurements->count() > 0)
            <div class="tab-content">
                {{-- Table Tab --}}
                <div class="tab-pane fade show active" id="growthTablePane" role="tabpanel">
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
                                    <th width="120">{{ __('translation.common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->growthMeasurements->take(5) as $measurement)
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
                                            <button class="btn btn-sm btn-outline-info" onclick="viewGrowthMeasurement({{ $measurement->id }})" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($patient->growthMeasurements->count() > 5)
                        <div class="card-footer bg-white border-0 text-center py-2">
                            <small class="text-muted">
                                {{ __('translation.showing_latest_of_total', ['count' => 5, 'total' => $patient->growthMeasurements->count()]) }}
                            </small>
                        </div>
                    @endif
                </div>

                {{-- Charts Tab --}}
                @if($patient->growthMeasurements->count() >= 2)
                <div class="tab-pane fade" id="growthChartsPane" role="tabpanel">
                    <div class="p-3">
                        <div class="row">
                            {{-- Weight Chart --}}
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-header bg-primary bg-opacity-10 py-2">
                                        <h6 class="mb-0 small fw-bold text-primary">
                                            <i class="fas fa-weight me-1"></i>{{ __('translation.weight') }} (kg)
                                        </h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <canvas id="inlineWeightChart" height="220"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- Height Chart --}}
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-header bg-success bg-opacity-10 py-2">
                                        <h6 class="mb-0 small fw-bold text-success">
                                            <i class="fas fa-ruler-vertical me-1"></i>{{ __('translation.height') }} (cm)
                                        </h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <canvas id="inlineHeightChart" height="220"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- BMI Chart --}}
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-header bg-warning bg-opacity-10 py-2">
                                        <h6 class="mb-0 small fw-bold text-warning">
                                            <i class="fas fa-chart-area me-1"></i>{{ __('translation.bmi') }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <canvas id="inlineBmiChart" height="220"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- Head Circumference Chart (under 6 years) --}}
                            @if($patient->age < 6)
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-header bg-danger bg-opacity-10 py-2">
                                        <h6 class="mb-0 small fw-bold text-danger">
                                            <i class="fas fa-circle-notch me-1"></i>{{ __('translation.head_circumference') }} (cm)
                                        </h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <canvas id="inlineHeadChart" height="220"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="text-center mt-1">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ __('translation.who_percentile_lines_note') }}
                            </small>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @else
            <div class="text-center py-4">
                <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">📏</div>
                <h6 class="text-muted mb-2">{{ __('translation.no_measurements') }}</h6>
                <p class="small text-muted mb-3">{{ __('translation.track_child_growth') }}</p>
                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#newGrowthMeasurementModal">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.add_first_measurement') }}
                </button>
            </div>
        @endif
    </div>
</div>
@endif
