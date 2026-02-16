{{-- New Chronic Disease Modal --}}
<div class="modal fade" id="newChronicDiseaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-heartbeat me-2"></i>{{ __('translation.add_chronic_disease') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form class="add-chronic-form" action="{{ route('patients.chronic-diseases.store', $patient) }}" method="POST">
                @csrf

            {{-- Tabs Navigation --}}
            <ul class="nav nav-tabs px-3 pt-3 border-bottom-0" id="chronicDiseaseTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="manual-chronic-tab" data-bs-toggle="tab" data-bs-target="#manualChronicPane" type="button" role="tab" aria-controls="manualChronicPane" aria-selected="true">
                        <i class="fas fa-edit me-1"></i>{{ __('translation.examination.manual_entry') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ai-chronic-tab" data-bs-toggle="tab" data-bs-target="#aiChronicPane" type="button" role="tab" aria-controls="aiChronicPane" aria-selected="false">
                        <i class="fas fa-robot me-1"></i>{{ __('translation.ai_chronic_disease') }}
                        <span class="badge bg-info ms-1">{{ __('translation.examination.coming_soon') }}</span>
                    </button>
                </li>
            </ul>

            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div class="tab-content" id="chronicDiseaseTabContent">
                {{-- Tab 1: Manual Chronic Disease Form --}}
                <div class="tab-pane fade show active" id="manualChronicPane" role="tabpanel" aria-labelledby="manual-chronic-tab">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.disease_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select choices-select" id="diseaseType" name="chronic_disease_type_id" required>
                            <option value="">{{ __('translation.select_disease') }}</option>
                            @foreach(\App\Models\ChronicDiseaseType::with('translations')->where('is_active', true)->orderBy('category')->get()->groupBy('category') as $category => $diseases)
                                <optgroup label="{{ $category }}">
                                    @foreach($diseases as $disease)
                                        <option value="{{ $disease->id }}" data-icd="{{ $disease->icd11_code }}">
                                            {{ $disease->name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.diagnosis_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="diagnosisDate" name="diagnosis_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.severity') }}</label>
                        <select class="form-select choices-select" id="diseaseSeverity" name="severity">
                            <option value="mild">{{ __('translation.mild') }}</option>
                            <option value="moderate" selected>{{ __('translation.moderate') }}</option>
                            <option value="severe">{{ __('translation.severe') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.treatment_plan') }}</label>
                        <textarea class="form-control" id="diseaseTreatmentPlan" name="treatment_plan" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.next_followup_date') }}</label>
                        <input type="date" class="form-control" id="nextFollowupDate" name="next_followup_date">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.disease_status') }}</label>
                        <select class="form-select choices-select" id="diseaseStatus" name="status">
                            <option value="active" selected>{{ __('translation.active') }}</option>
                            <option value="in_remission">{{ __('translation.in_remission') }}</option>
                            <option value="resolved">{{ __('translation.resolved') }}</option>
                        </select>
                    </div>
                </div>
                </div>{{-- end manualChronicPane --}}

                {{-- Tab 2: AI (Coming Soon) --}}
                <div class="tab-pane fade" id="aiChronicPane" role="tabpanel" aria-labelledby="ai-chronic-tab">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-robot text-info" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                        <h4 class="text-muted mb-3">{{ __('translation.ai_chronic_disease') }}</h4>
                        <p class="text-muted mb-4 px-5">{{ __('translation.ai_chronic_disease_description') }}</p>
                        <span class="badge bg-info fs-6 px-4 py-2">
                            <i class="fas fa-clock me-2"></i>{{ __('translation.examination.coming_soon') }}
                        </span>
                    </div>
                </div>{{-- end aiChronicPane --}}

                </div>{{-- end tab-content --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="submit" class="btn btn-warning" id="chronicDiseaseSaveBtn">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

{{-- View Chronic Disease Details Modal --}}
<div class="modal fade" id="viewChronicDiseaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-heartbeat me-2"></i>{{ __('translation.chronic_disease_details') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="chronicDiseaseDetailsContent" style="max-height: 50vh; overflow-y: auto;">
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
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

{{-- Add Monitoring Modal --}}
<div class="modal fade" id="addMonitoringModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>{{ __('translation.add_monitoring') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form class="add-monitoring-form" action="" method="POST">
                @csrf
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <input type="hidden" id="monitoringDiseaseId">
                <input type="hidden" name="parameter_name" id="monitoringParameterNameHidden">
                <div id="monitoringDiseaseName" class="alert alert-light mb-3 py-2 small">
                    <i class="fas fa-heartbeat text-warning me-1"></i>
                    <strong id="monitoringDiseaseLabel"></strong>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.monitoring_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="monitoringDate" name="monitoring_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.parameter_name') }} <span class="text-danger">*</span></label>
                        <select class="form-select choices-select" id="monitoringParameterName">
                            <option value="">{{ __('translation.select') }}...</option>
                            <option value="blood_pressure">{{ __('translation.blood_pressure') }}</option>
                            <option value="blood_sugar">{{ __('translation.blood_sugar') }}</option>
                            <option value="heart_rate">{{ __('translation.heart_rate') }}</option>
                            <option value="weight">{{ __('translation.weight') }}</option>
                            <option value="temperature">{{ __('translation.temperature') }}</option>
                            <option value="oxygen_saturation">{{ __('translation.oxygen_saturation') }}</option>
                            <option value="cholesterol">{{ __('translation.cholesterol') }}</option>
                            <option value="hba1c">HbA1c</option>
                            <option value="creatinine">{{ __('translation.creatinine') }}</option>
                            <option value="other">{{ __('translation.other') }}</option>
                        </select>
                    </div>
                    <div class="col-md-6" id="customParameterGroup" style="display:none;">
                        <label class="form-label">{{ __('translation.parameter_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="monitoringCustomParameter" placeholder="{{ __('translation.parameter_name') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.parameter_value') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="monitoringParameterValue" name="parameter_value" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.parameter_unit') }}</label>
                        <input type="text" class="form-control" id="monitoringParameterUnit" name="parameter_unit" placeholder="mmHg, mg/dL, ...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.status') }}</label>
                        <select class="form-select choices-select" id="monitoringStatus" name="status">
                            <option value="">{{ __('translation.select') }}...</option>
                            <option value="controlled">{{ __('translation.controlled') }}</option>
                            <option value="uncontrolled">{{ __('translation.uncontrolled') }}</option>
                            <option value="critical">{{ __('translation.critical') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.notes') }}</label>
                        <textarea class="form-control" id="monitoringNotes" name="notes" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
