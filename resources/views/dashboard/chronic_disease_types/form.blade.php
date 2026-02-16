<!-- Add/Edit Chronic Disease Type Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.chronic_disease_types.form.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="add-form" action="{{ route('chronic_disease_types.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-6 mb-3">
                            <label for="key" class="form-label">
                                {{ __('translation.chronic_disease_types.form.fields.key') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="key" 
                                   name="key" 
                                   placeholder="{{ __('translation.chronic_disease_types.form.placeholders.key') }}"
                                   pattern="[a-z_]+"
                                   required>
                            <div class="form-text">{{ __('translation.chronic_disease_types.form.hints.key') }}</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                {{ __('translation.chronic_disease_types.form.fields.name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="{{ __('translation.chronic_disease_types.form.placeholders.name') }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Category Field -->
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">
                                {{ __('translation.chronic_disease_types.form.fields.category') }}
                            </label>
                            <select class="form-select choices-select" id="category" name="category">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="cardiovascular">{{ __('translation.chronic_disease_types.categories.cardiovascular') }}</option>
                                <option value="endocrine">{{ __('translation.chronic_disease_types.categories.endocrine') }}</option>
                                <option value="respiratory">{{ __('translation.chronic_disease_types.categories.respiratory') }}</option>
                                <option value="neurological">{{ __('translation.chronic_disease_types.categories.neurological') }}</option>
                                <option value="musculoskeletal">{{ __('translation.chronic_disease_types.categories.musculoskeletal') }}</option>
                                <option value="autoimmune">{{ __('translation.chronic_disease_types.categories.autoimmune') }}</option>
                                <option value="gastrointestinal">{{ __('translation.chronic_disease_types.categories.gastrointestinal') }}</option>
                                <option value="renal">{{ __('translation.chronic_disease_types.categories.renal') }}</option>
                                <option value="other">{{ __('translation.chronic_disease_types.categories.other') }}</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- ICD-11 Code Field -->
                        <div class="col-md-4 mb-3">
                            <label for="icd11_code" class="form-label">
                                {{ __('translation.chronic_disease_types.form.fields.icd11_code') }}
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="icd11_code" 
                                   name="icd11_code" 
                                   placeholder="{{ __('translation.chronic_disease_types.form.placeholders.icd11_code') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Follow-up Interval Days Field -->
                        <div class="col-md-4 mb-3">
                            <label for="followup_interval_days" class="form-label">
                                {{ __('translation.chronic_disease_types.form.fields.followup_days') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="followup_interval_days" 
                                   name="followup_interval_days" 
                                   placeholder="{{ __('translation.chronic_disease_types.form.placeholders.followup_days') }}"
                                   value="30"
                                   min="1">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            {{ __('translation.chronic_disease_types.form.fields.description') }}
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="2"
                                  placeholder="{{ __('translation.chronic_disease_types.form.placeholders.description') }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Management Guidelines Field -->
                    <div class="mb-3">
                        <label for="management_guidelines" class="form-label">
                            {{ __('translation.chronic_disease_types.form.fields.management_guidelines') }}
                        </label>
                        <textarea class="form-control" 
                                  id="management_guidelines" 
                                  name="management_guidelines" 
                                  rows="3"
                                  placeholder="{{ __('translation.chronic_disease_types.form.placeholders.management_guidelines') }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>{{ __('translation.chronic_disease_types.form.btn_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="afm_btnSaveIt">
                        <i class="bi bi-check-circle me-1"></i>{{ __('translation.chronic_disease_types.form.btn_save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
