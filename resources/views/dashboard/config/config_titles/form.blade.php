<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addConfigTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addConfigTitleModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.config_titles.form.modal.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="add-form" id="configTitleForm" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="editConfigTitleId" name="id">
                    
                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-6 mb-3">
                            <label for="key" class="form-label">{{ __('translation.config_titles.form.fields.key') }}</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="key" 
                                   name="key" 
                                   readonly
                    placeholder="{{ __('translation.config_titles.form.placeholders.key') }}">
                <div class="form-text">{{ __('translation.config_titles.form.hints.readonly_autogen') }}</div>
                        </div>
                        
                        <!-- Page Field -->
                        <div class="col-md-6 mb-3">
                            <label for="page" class="form-label">{{ __('translation.config_titles.form.fields.page') }}</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="page" 
                                   name="page" 
                                   readonly
                    placeholder="{{ __('translation.config_titles.form.placeholders.page') }}">
                <div class="form-text">{{ __('translation.config_titles.form.hints.readonly_autogen') }}</div>
                        </div>
                        
                        <!-- Title Field -->
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">{{ __('translation.config_titles.form.fields.title') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   required
                                   placeholder="{{ __('translation.config_titles.form.placeholders.title') }}">
                            <div class="invalid-feedback">
                                {{ __('translation.config_titles.form.validation.title') }}
                            </div>
                        </div>
                        
                        <!-- Description Field -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">{{ __('translation.config_titles.form.fields.description') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      required
                                      placeholder="{{ __('translation.config_titles.form.placeholders.description') }}"></textarea>
                            <div class="invalid-feedback">
                                {{ __('translation.config_titles.form.validation.description') }}
                            </div>
                            <div class="form-text">{{ __('translation.config_titles.form.hints.description_help') }}</div>
                        </div>
                        
                        <!-- Status Information (Read-only fields for edit mode) -->
                        <div class="col-md-6 mb-3 edit-only" style="display: none;">
                            <label for="created_at" class="form-label">{{ __('translation.config_titles.form.fields.created_at') }}</label>
                            <input type="text" 
                                   class="form-control-plaintext" 
                                   id="created_at" 
                                   readonly>
                        </div>
                        
                        <div class="col-md-6 mb-3 edit-only" style="display: none;">
                            <label for="updated_at" class="form-label">{{ __('translation.config_titles.form.fields.updated_at') }}</label>
                            <input type="text" 
                                   class="form-control-plaintext" 
                                   id="updated_at" 
                                   readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.config_titles.form.buttons.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="afm_btnSaveIt" form="configTitleForm">
                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.config_titles.form.buttons.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
