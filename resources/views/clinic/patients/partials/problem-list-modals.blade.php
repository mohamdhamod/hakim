{{-- Add Problem Modal --}}
<div class="modal fade" id="addProblemModal" tabindex="-1" aria-labelledby="addProblemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('patients.problems.store', $patient) }}" method="POST" class="problem-form">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="addProblemModalLabel">
                        <i class="fas fa-list-check text-danger me-2"></i>
                        {{ __('translation.problem_list.add') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('translation.problem_list.problem') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.icd_code') }}</label>
                            <input type="text" name="icd_code" class="form-control" placeholder="e.g. E11.9">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.onset_date') }}</label>
                            <input type="date" name="onset_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="active">{{ __('translation.problem_list.status_active') }}</option>
                                <option value="resolved">{{ __('translation.problem_list.status_resolved') }}</option>
                                <option value="inactive">{{ __('translation.problem_list.status_inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.severity') }}</label>
                            <select name="severity" class="form-select">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="mild">{{ __('translation.problem_list.severity_mild') }}</option>
                                <option value="moderate">{{ __('translation.problem_list.severity_moderate') }}</option>
                                <option value="severe">{{ __('translation.problem_list.severity_severe') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.problem_list.notes') }}</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Problem Modal --}}
<div class="modal fade" id="editProblemModal" tabindex="-1" aria-labelledby="editProblemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="editProblemForm" method="POST" class="problem-form">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="editProblemModalLabel">
                        <i class="fas fa-list-check text-danger me-2"></i>
                        {{ __('translation.problem_list.edit') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">{{ __('translation.problem_list.problem') }} <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_problem_title" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.icd_code') }}</label>
                            <input type="text" name="icd_code" id="edit_problem_icd_code" class="form-control" placeholder="e.g. E11.9">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.onset_date') }}</label>
                            <input type="date" name="onset_date" id="edit_problem_onset_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.resolved_date') }}</label>
                            <input type="date" name="resolved_date" id="edit_problem_resolved_date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.status') }}</label>
                            <select name="status" id="edit_problem_status" class="form-select">
                                <option value="active">{{ __('translation.problem_list.status_active') }}</option>
                                <option value="resolved">{{ __('translation.problem_list.status_resolved') }}</option>
                                <option value="inactive">{{ __('translation.problem_list.status_inactive') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('translation.problem_list.severity') }}</label>
                            <select name="severity" id="edit_problem_severity" class="form-select">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="mild">{{ __('translation.problem_list.severity_mild') }}</option>
                                <option value="moderate">{{ __('translation.problem_list.severity_moderate') }}</option>
                                <option value="severe">{{ __('translation.problem_list.severity_severe') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.problem_list.notes') }}</label>
                            <textarea name="notes" id="edit_problem_notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Problem Modal --}}
<div class="modal fade" id="viewProblemModal" tabindex="-1" aria-labelledby="viewProblemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="viewProblemModalLabel">
                    <i class="fas fa-list-check text-danger me-2"></i>
                    {{ __('translation.problem_list.details') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div class="row g-3">
                    <div class="col-md-8">
                        <strong class="small text-muted d-block">{{ __('translation.problem_list.problem') }}</strong>
                        <span id="view_problem_title" class="fw-semibold"></span>
                    </div>
                    <div class="col-md-4">
                        <strong class="small text-muted d-block">{{ __('translation.problem_list.icd_code') }}</strong>
                        <span id="view_problem_icd_code"></span>
                    </div>
                    <div class="col-md-4">
                        <strong class="small text-muted d-block">{{ __('translation.problem_list.onset_date') }}</strong>
                        <span id="view_problem_onset_date"></span>
                    </div>
                    <div class="col-md-4">
                        <strong class="small text-muted d-block">{{ __('translation.problem_list.resolved_date') }}</strong>
                        <span id="view_problem_resolved_date"></span>
                    </div>
                    <div class="col-md-4">
                        <strong class="small text-muted d-block">{{ __('translation.problem_list.status') }}</strong>
                        <span id="view_problem_status"></span>
                    </div>
                    <div class="col-md-4">
                        <strong class="small text-muted d-block">{{ __('translation.problem_list.severity') }}</strong>
                        <span id="view_problem_severity"></span>
                    </div>
                    <div class="col-12">
                        <strong class="small text-muted d-block">{{ __('translation.problem_list.notes') }}</strong>
                        <span id="view_problem_notes"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
            </div>
        </div>
    </div>
</div>
