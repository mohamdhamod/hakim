<!-- Add/Edit Lab Test Type Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.lab_test_types.form.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="add-form" action="{{ route('lab_test_types.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-6 mb-3">
                            <label for="key" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.key') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="key" 
                                   name="key" 
                                   placeholder="{{ __('translation.lab_test_types.form.placeholders.key') }}"
                                   pattern="[a-z_]+"
                                   required>
                            <div class="form-text">{{ __('translation.lab_test_types.form.hints.key') }}</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="{{ __('translation.lab_test_types.form.placeholders.name') }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Category Field -->
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.category') }}
                            </label>
                            <select class="form-select choices-select" id="category" name="category">
                                <option value="">{{ __('translation.common.select') }}</option>
                                <option value="hematology">{{ __('translation.lab_test_types.categories.hematology') }}</option>
                                <option value="biochemistry">{{ __('translation.lab_test_types.categories.biochemistry') }}</option>
                                <option value="immunology">{{ __('translation.lab_test_types.categories.immunology') }}</option>
                                <option value="microbiology">{{ __('translation.lab_test_types.categories.microbiology') }}</option>
                                <option value="urinalysis">{{ __('translation.lab_test_types.categories.urinalysis') }}</option>
                                <option value="hormones">{{ __('translation.lab_test_types.categories.hormones') }}</option>
                                <option value="coagulation">{{ __('translation.lab_test_types.categories.coagulation') }}</option>
                                <option value="other">{{ __('translation.lab_test_types.categories.other') }}</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Unit Field -->
                        <div class="col-md-4 mb-3">
                            <label for="unit" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.unit') }}
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="unit" 
                                   name="unit" 
                                   placeholder="{{ __('translation.lab_test_types.form.placeholders.unit') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Order Field -->
                        <div class="col-md-4 mb-3">
                            <label for="order" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.order') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="order" 
                                   name="order" 
                                   placeholder="{{ __('translation.lab_test_types.form.placeholders.order') }}"
                                   value="0"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Normal Range Min Field -->
                        <div class="col-md-4 mb-3">
                            <label for="normal_range_min" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.normal_range_min') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="normal_range_min" 
                                   name="normal_range_min" 
                                   step="0.01"
                                   placeholder="{{ __('translation.lab_test_types.form.placeholders.normal_range_min') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Normal Range Max Field -->
                        <div class="col-md-4 mb-3">
                            <label for="normal_range_max" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.normal_range_max') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="normal_range_max" 
                                   name="normal_range_max" 
                                   step="0.01"
                                   placeholder="{{ __('translation.lab_test_types.form.placeholders.normal_range_max') }}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Normal Range Text Field -->
                        <div class="col-md-4 mb-3">
                            <label for="normal_range_text" class="form-label">
                                {{ __('translation.lab_test_types.form.fields.normal_range_text') }}
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="normal_range_text" 
                                   name="normal_range_text" 
                                   placeholder="{{ __('translation.lab_test_types.form.placeholders.normal_range_text') }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            {{ __('translation.lab_test_types.form.fields.description') }}
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="2"
                                  placeholder="{{ __('translation.lab_test_types.form.placeholders.description') }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>{{ __('translation.lab_test_types.form.btn_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="afm_btnSaveIt">
                        <i class="bi bi-check-circle me-1"></i>{{ __('translation.lab_test_types.form.btn_save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
