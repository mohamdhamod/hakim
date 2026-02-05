<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addImageModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.images.form.modal.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                                <form class="add-form" id="imageForm" method="POST" enctype="multipart/form-data">
                                        @csrf


                    <div class="row">
                        <div class="row col-md-12 mb-3">
                            <label for="name" id="name" class="col-sm-12 col-form-label">{{ __('translation.images.form.fields.image') }} *</label>
                            <div class="col-sm-12">
                                <input class="form-control @error('name') is-invalid @enderror"
                                       type="file"
                                       name="name"
                                >
                                <div class="form-text">{{ __('translation.images.form.hints.image') }}</div>
                            </div>
                        </div>
                        <div class="row col-md-12 mb-3 data-form">
                            <input type="hidden" id="editImageId" name="id">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.images.form.buttons.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="afm_btnSaveIt" form="imageForm">
                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.images.form.buttons.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
