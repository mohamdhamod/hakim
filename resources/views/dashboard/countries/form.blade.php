<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addCountriesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCountriesModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.countries.form.modal.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="add-form" id="countriesForm" method="POST">
                    {{ csrf_field() }}


                    <div class="row form-data">
                        <!-- Name Field -->
                        <div class="col-md-12 mb-2">
                            <input type="hidden" id="editId" name="id">
                            <label for="name" class="form-label">{{ __('translation.countries.form.fields.name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   required
                                   placeholder="{{ __('translation.countries.form.placeholders.name') }}">
                            <div class="invalid-feedback">
                                {{ __('translation.countries.form.validation.name') }}
                            </div>
                        </div>

                        <!-- Code Field -->
                        <div class="col-md-12 mb-2">
                            <label for="code" class="form-label">{{ __('translation.countries.form.fields.code') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="code"
                                   name="code"
                                   placeholder="{{ __('translation.countries.form.placeholders.code') }}">
                            <div class="form-text">{{ __('translation.countries.form.hints.code') }}</div>
                        </div>

                        <!-- Phone Extension Field -->
                        <div class="col-md-12 mb-2">
                            <label for="phone_extension" class="form-label">{{ __('translation.countries.form.fields.phone_extension') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="phone_extension"
                                   name="phone_extension"
                                   placeholder="{{ __('translation.countries.form.placeholders.phone_extension') }}">
                            <div class="form-text">{{ __('translation.countries.form.hints.phone_extension') }}</div>
                        </div>


                    </div>
                    <div class="row">
                        <!-- Flag Field -->
                        <div class="row col-md-12 mb-3">
                            <label for="flag" id="flag" class="col-sm-12 col-form-label">{{ __('translation.countries.form.fields.flag') }} </label>
                            <div class="col-sm-12">
                                <input class="form-control @error('flag') is-invalid @enderror"
                                       type="file"
                                       name="flag"
                                >
                                <div class="form-text">{{ __('translation.countries.form.hints.flag') }}</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.countries.form.buttons.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="afm_btnSaveIt" form="countriesForm">
                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.countries.form.buttons.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
