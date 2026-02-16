{{-- New Examination Modal --}}
<div class="modal fade" id="newExaminationModal" tabindex="-1" aria-labelledby="newExaminationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('clinic.examinations.store') }}" method="POST" id="newExaminationForm" class="add-examination-form">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                
                <div class="modal-header border-0 bg-success text-white">
                    <h5 class="modal-title" id="newExaminationModalLabel">
                        <i class="fas fa-stethoscope me-2"></i>
                        {{ __('translation.examination.new_for_patient') }}: {{ $patient->full_name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Tabs Navigation --}}
                <ul class="nav nav-tabs px-3 pt-3 border-bottom-0" id="examinationTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="manual-exam-tab" data-bs-toggle="tab" data-bs-target="#manualExamPane" type="button" role="tab" aria-controls="manualExamPane" aria-selected="true">
                            <i class="fas fa-edit me-1"></i>{{ __('translation.examination.manual_entry') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="ai-exam-tab" data-bs-toggle="tab" data-bs-target="#aiExamPane" type="button" role="tab" aria-controls="aiExamPane" aria-selected="false">
                            <i class="fas fa-robot me-1"></i>{{ __('translation.examination.ai_examination') }}
                            <span class="badge bg-info ms-1">{{ __('translation.examination.coming_soon') }}</span>
                        </button>
                    </li>
                </ul>

                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="tab-content" id="examinationTabContent">
                    {{-- Tab 1: Manual Examination Form --}}
                    <div class="tab-pane fade show active" id="manualExamPane" role="tabpanel" aria-labelledby="manual-exam-tab">
                    <div class="row g-4">
                        {{-- Basic Info --}}
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.number') }}</label>
                                    <input type="text" class="form-control bg-light" value="{{ $examinationNumber ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('translation.examination.date') }} <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="examination_date" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Chief Complaint --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-comment-medical text-info me-2"></i>{{ __('translation.examination.chief_complaint') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.chief_complaint') }}</label>
                                            <textarea name="chief_complaint" class="form-control" rows="3" placeholder="{{ __('translation.examination.chief_complaint_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.present_illness_history') }}</label>
                                            <textarea name="present_illness_history" class="form-control" rows="3" placeholder="{{ __('translation.examination.present_illness_history_placeholder') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Vital Signs --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-heartbeat text-danger me-2"></i>{{ __('translation.examination.vital_signs') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.temperature') }} (°C)</label>
                                            <input type="number" name="temperature" class="form-control form-control-sm" step="0.1" min="30" max="45" placeholder="37.0">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.blood_pressure') }}</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="blood_pressure_systolic" class="form-control" placeholder="120" min="60" max="250">
                                                <span class="input-group-text">/</span>
                                                <input type="number" name="blood_pressure_diastolic" class="form-control" placeholder="80" min="40" max="150">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <label class="form-label small">{{ __('translation.examination.pulse_rate') }}</label>
                                            <input type="number" name="pulse_rate" class="form-control form-control-sm" min="30" max="200" placeholder="72">
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <label class="form-label small">{{ __('translation.examination.respiratory_rate') }}</label>
                                            <input type="number" name="respiratory_rate" class="form-control form-control-sm" min="8" max="60" placeholder="16">
                                        </div>
                                        <div class="col-md-2 col-6">
                                            <label class="form-label small">{{ __('translation.examination.oxygen_saturation') }} (%)</label>
                                            <input type="number" name="oxygen_saturation" class="form-control form-control-sm" min="50" max="100" placeholder="98">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.weight') }} (kg)</label>
                                            <input type="number" name="weight" class="form-control form-control-sm" step="0.1" min="0.5" max="500" placeholder="70">
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label small">{{ __('translation.examination.height') }} (cm)</label>
                                            <input type="number" name="height" class="form-control form-control-sm" step="0.1" min="20" max="300" placeholder="170">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Physical Examination --}}
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-search text-primary me-2"></i>{{ __('translation.examination.physical_examination') }}</label>
                            <textarea name="physical_examination" class="form-control" rows="3" placeholder="{{ __('translation.examination.physical_examination_placeholder') }}"></textarea>
                        </div>

                        {{-- Diagnosis & Treatment --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-clipboard-check text-success me-2"></i>{{ __('translation.examination.diagnosis_treatment') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label class="form-label">{{ __('translation.examination.diagnosis') }}</label>
                                            <textarea name="diagnosis" class="form-control" rows="2" placeholder="{{ __('translation.examination.diagnosis_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">
                                                {{ __('translation.examination.icd_code') }}
                                                <a href="https://icd.who.int/browse/2025-01/mms/en" target="_blank" class="text-info ms-1" title="{{ __('translation.examination.browse_icd_codes') }}">
                                                    <i class="fas fa-external-link-alt small"></i>
                                                </a>
                                            </label>
                                            <input type="text" name="icd_code" class="form-control" placeholder="{{ __('translation.examination.icd_code_placeholder') }}" maxlength="20">
                                            <small class="text-muted">{{ __('translation.examination.icd_code_help') }}</small>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ __('translation.examination.treatment_plan') }}</label>
                                            <textarea name="treatment_plan" class="form-control" rows="2" placeholder="{{ __('translation.examination.treatment_plan_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">{{ __('translation.examination.prescriptions') }}</label>
                                            <textarea name="prescriptions" class="form-control" rows="3" placeholder="{{ __('translation.examination.prescriptions_placeholder') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Lab & Imaging --}}
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0"><i class="fas fa-flask text-warning me-2"></i>{{ __('translation.examination.lab_imaging') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.lab_tests_ordered') }}</label>
                                            <textarea name="lab_tests_ordered" class="form-control" rows="2" placeholder="{{ __('translation.examination.lab_tests_ordered_placeholder') }}"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ __('translation.examination.imaging_ordered') }}</label>
                                            <textarea name="imaging_ordered" class="form-control" rows="2" placeholder="{{ __('translation.examination.imaging_ordered_placeholder') }}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Follow Up --}}
                        <div class="col-12">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-calendar-check text-secondary me-2"></i>{{ __('translation.examination.follow_up_date') }}</label>
                                    <input type="date" name="follow_up_date" class="form-control">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">{{ __('translation.examination.follow_up_notes') }}</label>
                                    <input type="text" name="follow_up_notes" class="form-control" placeholder="{{ __('translation.examination.follow_up_notes_placeholder') }}">
                                </div>
                            </div>
                        </div>

                        {{-- Doctor Notes --}}
                        <div class="col-12">
                            <label class="form-label"><i class="fas fa-notes-medical text-muted me-2"></i>{{ __('translation.examination.doctor_notes') }}</label>
                            <textarea name="doctor_notes" class="form-control" rows="2" placeholder="{{ __('translation.examination.doctor_notes_placeholder') }}"></textarea>
                        </div>
                    </div>
                    </div>{{-- end manualExamPane --}}

                    {{-- Tab 2: AI Examination (Coming Soon) --}}
                    <div class="tab-pane fade" id="aiExamPane" role="tabpanel" aria-labelledby="ai-exam-tab">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-robot text-info" style="font-size: 5rem; opacity: 0.3;"></i>
                            </div>
                            <h4 class="text-muted mb-3">{{ __('translation.examination.ai_examination') }}</h4>
                            <p class="text-muted mb-4 px-5">
                                {{ __('translation.examination.ai_examination_description') }}
                            </p>
                            <span class="badge bg-info fs-6 px-4 py-2">
                                <i class="fas fa-clock me-2"></i>{{ __('translation.examination.coming_soon') }}
                            </span>
                        </div>
                    </div>{{-- end aiExamPane --}}

                    </div>{{-- end tab-content --}}
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" id="examinationSaveBtn" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>{{ __('translation.examination.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Examination Details Modal --}}
<div class="modal fade" id="viewExaminationModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-stethoscope me-2"></i>{{ __('translation.examination.details') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="examinationDetailsContent" style="max-height: 50vh; overflow-y: auto;">
                <div class="text-center py-4">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">{{ __('translation.common.loading') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="examinationPrintLink" class="btn btn-outline-secondary" target="_blank">
                    <i class="fas fa-print me-1"></i>{{ __('translation.common.print') }}
                </a>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
            </div>
        </div>
    </div>
</div>
