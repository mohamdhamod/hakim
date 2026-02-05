<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLinkModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.links.form.modal.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                                <form class="add-form" id="linkForm" method="POST" enctype="multipart/form-data">
                                        @csrf


                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-12 mb-3">
                            <label for="name" class="form-label">{{ __('translation.links.form.fields.name') }} <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   required
                                   placeholder="{{ __('translation.links.form.placeholders.name') }}">
                            <div class="invalid-feedback">
                                {{ __('translation.links.form.validation.name') }}
                            </div>
                            <input type="hidden" id="editLinkId" name="id">
                        </div>
                   
            
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.links.form.buttons.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="afm_btnSaveIt" form="linkForm">
                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.links.form.buttons.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
