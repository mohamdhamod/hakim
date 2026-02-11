{{-- New Growth Measurement Modal --}}
<div class="modal fade" id="newGrowthMeasurementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>{{ __('translation.add_measurement') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.measurement_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="measurementDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.weight') }} (kg) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="growthWeight" step="0.1" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.height') }} (cm) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="growthHeight" step="0.1" min="0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.head_circumference') }} (cm)</label>
                        <input type="number" class="form-control" id="headCircumference" step="0.1" min="0">
                        <small class="text-muted">{{ __('translation.for_infants_up_to_3_years') }}</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.notes') }}</label>
                        <textarea class="form-control" id="growthNotes" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-info" onclick="saveGrowthMeasurement()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- View Growth Measurement Modal --}}
<div class="modal fade" id="viewGrowthMeasurementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>{{ __('translation.growth_measurement_details') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="growthMeasurementDetailsContent">
                    <div class="text-center py-3">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">{{ __('translation.loading') }}</span>
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
