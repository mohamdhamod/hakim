<!-- Add/Edit Vaccination Type Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.vaccination_types.form.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="add-form" action="{{ route('vaccination_types.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-6 mb-3">
                            <label for="key" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.key') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="key" 
                                   name="key" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.key') }}"
                                   pattern="[a-z_]+"
                                   required>
                            <div class="form-text">{{ __('translation.vaccination_types.form.hints.key') }}</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.name') }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Disease Prevented Field -->
                        <div class="col-md-6 mb-3">
                            <label for="disease_prevented" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.disease_prevented') }}
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="disease_prevented" 
                                   name="disease_prevented" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.disease_prevented') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Age Group Field -->
                        <div class="col-md-6 mb-3">
                            <label for="age_group" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.age_group') }}
                            </label>
                            <select class="form-select choices-select" id="age_group" name="age_group">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="newborn">{{ __('translation.vaccination_types.age_groups.newborn') }}</option>
                                <option value="infant">{{ __('translation.vaccination_types.age_groups.infant') }}</option>
                                <option value="toddler">{{ __('translation.vaccination_types.age_groups.toddler') }}</option>
                                <option value="child">{{ __('translation.vaccination_types.age_groups.child') }}</option>
                                <option value="adolescent">{{ __('translation.vaccination_types.age_groups.adolescent') }}</option>
                                <option value="adult">{{ __('translation.vaccination_types.age_groups.adult') }}</option>
                                <option value="elderly">{{ __('translation.vaccination_types.age_groups.elderly') }}</option>
                                <option value="all">{{ __('translation.vaccination_types.age_groups.all') }}</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Recommended Age Months Field -->
                        <div class="col-md-4 mb-3">
                            <label for="recommended_age_months" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.recommended_age_months') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="recommended_age_months" 
                                   name="recommended_age_months" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.recommended_age_months') }}"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Doses Required Field -->
                        <div class="col-md-4 mb-3">
                            <label for="doses_required" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.doses_required') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="doses_required" 
                                   name="doses_required" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.doses_required') }}"
                                   value="1"
                                   min="1">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Interval Days Field -->
                        <div class="col-md-4 mb-3">
                            <label for="interval_days" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.interval_days') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="interval_days" 
                                   name="interval_days" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.interval_days') }}"
                                   value="0"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Booster After Months Field -->
                        <div class="col-md-4 mb-3">
                            <label for="booster_after_months" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.booster_after_months') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="booster_after_months" 
                                   name="booster_after_months" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.booster_after_months') }}"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Order Field -->
                        <div class="col-md-4 mb-3">
                            <label for="order" class="form-label">
                                {{ __('translation.vaccination_types.form.fields.order') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="order" 
                                   name="order" 
                                   placeholder="{{ __('translation.vaccination_types.form.placeholders.order') }}"
                                   value="0"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Is Mandatory Field -->
                        <div class="col-md-4 mb-3 d-flex align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="is_mandatory" 
                                       name="is_mandatory" 
                                       value="1">
                                <label class="form-check-label" for="is_mandatory">
                                    {{ __('translation.vaccination_types.form.fields.is_mandatory') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            {{ __('translation.vaccination_types.form.fields.description') }}
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="2"
                                  placeholder="{{ __('translation.vaccination_types.form.placeholders.description') }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>{{ __('translation.vaccination_types.form.btn_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="afm_btnSaveIt">
                        <i class="bi bi-check-circle me-1"></i>{{ __('translation.vaccination_types.form.btn_save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
