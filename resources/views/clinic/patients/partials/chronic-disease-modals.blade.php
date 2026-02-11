{{-- New Chronic Disease Modal --}}
<div class="modal fade" id="newChronicDiseaseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-heartbeat me-2"></i>{{ __('translation.add_chronic_disease') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.disease_type') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="diseaseType" required>
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
                        <input type="date" class="form-control" id="diagnosisDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.severity') }}</label>
                        <select class="form-select" id="diseaseSeverity">
                            <option value="mild">{{ __('translation.mild') }}</option>
                            <option value="moderate" selected>{{ __('translation.moderate') }}</option>
                            <option value="severe">{{ __('translation.severe') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.treatment_plan') }}</label>
                        <textarea class="form-control" id="diseaseTreatmentPlan" rows="3"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.next_followup_date') }}</label>
                        <input type="date" class="form-control" id="nextFollowupDate">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.disease_status') }}</label>
                        <select class="form-select" id="diseaseStatus">
                            <option value="active" selected>{{ __('translation.active') }}</option>
                            <option value="in_remission">{{ __('translation.in_remission') }}</option>
                            <option value="resolved">{{ __('translation.resolved') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-warning" onclick="saveChronicDisease()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
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
            <div class="modal-body" id="chronicDiseaseDetailsContent">
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
            <div class="modal-body">
                <input type="hidden" id="monitoringDiseaseId">
                <div id="monitoringDiseaseName" class="alert alert-light mb-3 py-2 small">
                    <i class="fas fa-heartbeat text-warning me-1"></i>
                    <strong id="monitoringDiseaseLabel"></strong>
                </div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.monitoring_date') }} <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="monitoringDate" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.parameter_name') }} <span class="text-danger">*</span></label>
                        <select class="form-select" id="monitoringParameterName">
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
                        <input type="text" class="form-control" id="monitoringParameterValue" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.parameter_unit') }}</label>
                        <input type="text" class="form-control" id="monitoringParameterUnit" placeholder="mmHg, mg/dL, ...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.status') }}</label>
                        <select class="form-select" id="monitoringStatus">
                            <option value="">{{ __('translation.select') }}...</option>
                            <option value="controlled">{{ __('translation.controlled') }}</option>
                            <option value="uncontrolled">{{ __('translation.uncontrolled') }}</option>
                            <option value="critical">{{ __('translation.critical') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.notes') }}</label>
                        <textarea class="form-control" id="monitoringNotes" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveMonitoring()">
                    <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
