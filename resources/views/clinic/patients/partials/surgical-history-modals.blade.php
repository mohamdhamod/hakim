{{-- Add Surgical History Modal --}}
<div class="modal fade" id="addSurgicalHistoryModal" tabindex="-1" aria-labelledby="addSurgicalHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('patients.surgeries.store', $patient) }}" method="POST" class="surgery-form">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="addSurgicalHistoryModalLabel">
                        <i class="fas fa-procedures me-2" style="color: #6f42c1;"></i>
                        {{ __('translation.surgical_history.add') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.procedure_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="procedure_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.procedure_date') }}</label>
                            <input type="date" name="procedure_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.hospital') }}</label>
                            <input type="text" name="hospital" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.surgeon') }}</label>
                            <input type="text" name="surgeon" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.surgical_history.indication') }}</label>
                            <textarea name="indication" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.surgical_history.complications') }}</label>
                            <textarea name="complications" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.surgical_history.notes') }}</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Surgical History Modal --}}
<div class="modal fade" id="editSurgicalHistoryModal" tabindex="-1" aria-labelledby="editSurgicalHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <form id="editSurgeryForm" method="POST" class="surgery-form">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="editSurgicalHistoryModalLabel">
                        <i class="fas fa-procedures me-2" style="color: #6f42c1;"></i>
                        {{ __('translation.surgical_history.edit') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.procedure_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="procedure_name" id="edit_surgery_procedure_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.procedure_date') }}</label>
                            <input type="date" name="procedure_date" id="edit_surgery_procedure_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.hospital') }}</label>
                            <input type="text" name="hospital" id="edit_surgery_hospital" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('translation.surgical_history.surgeon') }}</label>
                            <input type="text" name="surgeon" id="edit_surgery_surgeon" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.surgical_history.indication') }}</label>
                            <textarea name="indication" id="edit_surgery_indication" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.surgical_history.complications') }}</label>
                            <textarea name="complications" id="edit_surgery_complications" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('translation.surgical_history.notes') }}</label>
                            <textarea name="notes" id="edit_surgery_notes" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ __('translation.common.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View Surgical History Modal --}}
<div class="modal fade" id="viewSurgicalHistoryModal" tabindex="-1" aria-labelledby="viewSurgicalHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="viewSurgicalHistoryModalLabel">
                    <i class="fas fa-procedures me-2" style="color: #6f42c1;"></i>
                    {{ __('translation.surgical_history.details') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <strong class="small text-muted d-block">{{ __('translation.surgical_history.procedure_name') }}</strong>
                        <span id="view_surgery_procedure_name"></span>
                    </div>
                    <div class="col-md-6">
                        <strong class="small text-muted d-block">{{ __('translation.surgical_history.procedure_date') }}</strong>
                        <span id="view_surgery_procedure_date"></span>
                    </div>
                    <div class="col-md-6">
                        <strong class="small text-muted d-block">{{ __('translation.surgical_history.hospital') }}</strong>
                        <span id="view_surgery_hospital"></span>
                    </div>
                    <div class="col-md-6">
                        <strong class="small text-muted d-block">{{ __('translation.surgical_history.surgeon') }}</strong>
                        <span id="view_surgery_surgeon"></span>
                    </div>
                    <div class="col-12">
                        <strong class="small text-muted d-block">{{ __('translation.surgical_history.indication') }}</strong>
                        <span id="view_surgery_indication"></span>
                    </div>
                    <div class="col-12">
                        <strong class="small text-muted d-block">{{ __('translation.surgical_history.complications') }}</strong>
                        <span id="view_surgery_complications"></span>
                    </div>
                    <div class="col-12">
                        <strong class="small text-muted d-block">{{ __('translation.surgical_history.notes') }}</strong>
                        <span id="view_surgery_notes"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
            </div>
        </div>
    </div>
</div>
