{{-- New Lab Test Modal --}}
<div class="modal fade" id="newLabTestModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-flask me-2"></i>{{ __('translation.add_lab_test') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-4">
                    <i class="fas fa-info-circle"></i> {{ __('translation.select_test_and_enter_result') }}
                </p>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.test_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="labTestType" required>
                            <option value="">{{ __('translation.select_test_type') }}</option>
                            @foreach(\App\Models\LabTestType::with('translations')->where('is_active', true)->orderBy('order')->get() as $type)
                                <option value="{{ $type->id }}" 
                                    data-unit="{{ $type->unit }}" 
                                    data-min="{{ $type->normal_range_min }}" 
                                    data-max="{{ $type->normal_range_max }}">
                                    {{ $type->name }} 
                                    @if($type->category)
                                        ({{ $type->category }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.test_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="testDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.result_value') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="resultValue" step="0.01" required>
                            <span class="input-group-text" id="resultUnit">-</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="alert alert-info small mb-0" id="normalRangeInfo" style="display: none;">
                            <i class="fas fa-chart-line"></i> <strong>{{ __('translation.normal_range') }}:</strong> <span id="normalRangeText"></span>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.notes') }}</label>
                        <textarea class="form-control" id="labTestNotes" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveLabTest()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- View Lab Test Details Modal --}}
<div class="modal fade" id="viewLabTestModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-flask me-2"></i>{{ __('translation.lab_test_details') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="labTestDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('translation.common.loading') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
            </div>
        </div>
    </div>
</div>
