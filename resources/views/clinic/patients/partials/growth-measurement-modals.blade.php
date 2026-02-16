{{-- New Growth Measurement Modal --}}
<div class="modal fade" id="newGrowthMeasurementModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>{{ __('translation.add_measurement') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form class="add-growth-form" action="{{ route('patients.growth-charts.store', $patient) }}" method="POST">
                @csrf

            {{-- Tabs Navigation --}}
            <ul class="nav nav-tabs px-3 pt-3 border-bottom-0" id="growthTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="manual-growth-tab" data-bs-toggle="tab" data-bs-target="#manualGrowthPane" type="button" role="tab" aria-controls="manualGrowthPane" aria-selected="true">
                        <i class="fas fa-edit me-1"></i>{{ __('translation.examination.manual_entry') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ai-growth-tab" data-bs-toggle="tab" data-bs-target="#aiGrowthPane" type="button" role="tab" aria-controls="aiGrowthPane" aria-selected="false">
                        <i class="fas fa-robot me-1"></i>{{ __('translation.ai_growth') }}
                        <span class="badge bg-info ms-1">{{ __('translation.examination.coming_soon') }}</span>
                    </button>
                </li>
            </ul>

            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div class="tab-content" id="growthTabContent">
                {{-- Tab 1: Manual Growth Form --}}
                <div class="tab-pane fade show active" id="manualGrowthPane" role="tabpanel" aria-labelledby="manual-growth-tab">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.measurement_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="measurementDate" name="measurement_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.weight') }} (kg) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="growthWeight" name="weight_kg" step="0.1" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.height') }} (cm) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="growthHeight" name="height_cm" step="0.1" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.head_circumference') }} (cm)</label>
                        <input type="number" class="form-control" id="headCircumference" name="head_circumference_cm" step="0.1" min="0">
                        <small class="text-muted">{{ __('translation.for_infants_up_to_3_years') }}</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.notes') }}</label>
                        <textarea class="form-control" id="growthNotes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                </div>{{-- end manualGrowthPane --}}

                {{-- Tab 2: AI (Coming Soon) --}}
                <div class="tab-pane fade" id="aiGrowthPane" role="tabpanel" aria-labelledby="ai-growth-tab">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-robot text-info" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                        <h4 class="text-muted mb-3">{{ __('translation.ai_growth') }}</h4>
                        <p class="text-muted mb-4 px-5">{{ __('translation.ai_growth_description') }}</p>
                        <span class="badge bg-info fs-6 px-4 py-2">
                            <i class="fas fa-clock me-2"></i>{{ __('translation.examination.coming_soon') }}
                        </span>
                    </div>
                </div>{{-- end aiGrowthPane --}}

                </div>{{-- end tab-content --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="submit" class="btn btn-info" id="growthMeasurementSaveBtn">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- View Growth Measurement Modal --}}
<div class="modal fade" id="viewGrowthMeasurementModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>{{ __('translation.growth_measurement_details') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div id="growthMeasurementDetailsContent">
                    <div class="text-center py-3">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">{{ __('translation.common.loading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
            </div>
        </div>
    </div>
</div>
