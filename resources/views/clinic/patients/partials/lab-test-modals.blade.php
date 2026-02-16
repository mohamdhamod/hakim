{{-- New Lab Test Modal --}}
<div class="modal fade" id="newLabTestModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('patients.lab-tests.store', $patient) }}" method="POST" class="add-lab-test-form">
                @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-flask me-2"></i>{{ __('translation.add_lab_test') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Tabs Navigation --}}
            <ul class="nav nav-tabs px-3 pt-3 border-bottom-0" id="labTestTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="manual-labtest-tab" data-bs-toggle="tab" data-bs-target="#manualLabTestPane" type="button" role="tab" aria-controls="manualLabTestPane" aria-selected="true">
                        <i class="fas fa-edit me-1"></i>{{ __('translation.examination.manual_entry') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ai-labtest-tab" data-bs-toggle="tab" data-bs-target="#aiLabTestPane" type="button" role="tab" aria-controls="aiLabTestPane" aria-selected="false">
                        <i class="fas fa-robot me-1"></i>{{ __('translation.ai_lab_test') }}
                        <span class="badge bg-info ms-1">{{ __('translation.examination.coming_soon') }}</span>
                    </button>
                </li>
            </ul>

            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div class="tab-content" id="labTestTabContent">
                {{-- Tab 1: Manual Lab Test Form --}}
                <div class="tab-pane fade show active" id="manualLabTestPane" role="tabpanel" aria-labelledby="manual-labtest-tab">
                <p class="small text-muted mb-4">
                    <i class="fas fa-info-circle"></i> {{ __('translation.select_test_and_enter_result') }}
                </p>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.test_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="labTestType" name="lab_test_type_id" required>
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
                        <input type="date" class="form-control" id="testDate" name="test_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.result_value') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="resultValue" name="result_value" step="0.01" required>
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
                        <textarea class="form-control" id="labTestNotes" name="doctor_notes" rows="2"></textarea>
                    </div>
                </div>
                </div>{{-- end manualLabTestPane --}}

                {{-- Tab 2: AI (Coming Soon) --}}
                <div class="tab-pane fade" id="aiLabTestPane" role="tabpanel" aria-labelledby="ai-labtest-tab">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-robot text-info" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                        <h4 class="text-muted mb-3">{{ __('translation.ai_lab_test') }}</h4>
                        <p class="text-muted mb-4 px-5">{{ __('translation.ai_lab_test_description') }}</p>
                        <span class="badge bg-info fs-6 px-4 py-2">
                            <i class="fas fa-clock me-2"></i>{{ __('translation.examination.coming_soon') }}
                        </span>
                    </div>
                </div>{{-- end aiLabTestPane --}}

                </div>{{-- end tab-content --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="submit" id="labTestSaveBtn" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
            </form>
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
            <div class="modal-body" id="labTestDetailsContent" style="max-height: 50vh; overflow-y: auto;">
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
