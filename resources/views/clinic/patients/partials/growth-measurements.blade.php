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
                        <div class="card-footer bg-white border-0 text-center py-3">
                            <a href="{{ route('clinic.patients.all-growth-measurements', $patient->file_number) }}" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-list me-2"></i>
                                {{ __('translation.view_all') }} ({{ $patient->growthMeasurements->count() }})
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Charts Tab --}}
                @if($patient->growthMeasurements->count() >= 2)
                <div class="tab-pane fade" id="growthChartsPane" role="tabpanel">
                    <div class="p-3">
                        {{-- Chart Legend --}}
                        <div class="d-flex flex-wrap justify-content-center gap-3 mb-3 p-2 bg-light rounded">
                            <div class="d-flex align-items-center">
                                <span class="badge me-1" style="background: linear-gradient(180deg, #dc3545 0%, #dc3545 100%); width: 14px; height: 14px;"></span>
                                <small class="text-muted">{{ __('translation.growth.zone_extreme') }} (&lt;3% / &gt;97%)</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge me-1" style="background: linear-gradient(180deg, #ffc107 0%, #ffc107 100%); width: 14px; height: 14px;"></span>
                                <small class="text-muted">{{ __('translation.growth.zone_caution') }} (3-15% / 85-97%)</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge me-1" style="background: linear-gradient(180deg, #28a745 0%, #28a745 100%); width: 14px; height: 14px;"></span>
                                <small class="text-muted">{{ __('translation.growth.zone_normal') }} (15-85%)</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="rounded-circle me-1" style="background: #1a237e; width: 12px; height: 12px; display: inline-block;"></span>
                                <small class="text-muted">{{ __('translation.growth.patient_data') }}</small>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Weight Chart --}}
                            <div class="col-lg-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-white fw-bold">
                                                <i class="fas fa-weight me-2"></i>{{ __('translation.weight_for_age') }}
                                            </h6>
                                            <span class="badge bg-white text-dark">WHO</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 280px; position: relative;">
                                            <canvas id="inlineWeightChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Height Chart --}}
                            <div class="col-lg-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-white fw-bold">
                                                <i class="fas fa-ruler-vertical me-2"></i>{{ __('translation.height_for_age') }}
                                            </h6>
                                            <span class="badge bg-white text-dark">WHO</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 280px; position: relative;">
                                            <canvas id="inlineHeightChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- BMI Chart --}}
                            <div class="col-lg-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-white fw-bold">
                                                <i class="fas fa-calculator me-2"></i>{{ __('translation.bmi_for_age') }}
                                            </h6>
                                            <span class="badge bg-white text-dark">WHO</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 280px; position: relative;">
                                            <canvas id="inlineBmiChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Head Circumference Chart (under 6 years) --}}
                            @if($patient->age < 6)
                            <div class="col-lg-6 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header border-0 py-3" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-white fw-bold">
                                                <i class="fas fa-circle-notch me-2"></i>{{ __('translation.head_circumference_for_age') }}
                                            </h6>
                                            <span class="badge bg-white text-dark">WHO</span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <div style="height: 280px; position: relative;">
                                            <canvas id="inlineHeadChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Chart Information --}}
                        <div class="alert alert-light border mt-2" style="font-size: 0.8rem;">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                <div>
                                    <strong>{{ __('translation.growth.about_who_standards') }}</strong><br>
                                    {{ __('translation.growth.who_standards_description') }}
                                </div>
                            </div>
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
