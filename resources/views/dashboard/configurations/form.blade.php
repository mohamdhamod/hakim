<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addConfigurationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addConfigurationsModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.configurations.form.modal.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="add-form" id="configurationsForm" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="editId" name="id">

                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-12">
                            <label for="key" class="form-label">{{ __('translation.configurations.form.fields.key') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('key') is-invalid @enderror"
                                    id="key" name="key" required
                                    data-placeholder="{{ __('translation.messages.select_an_option') }}">
                                @foreach(\App\Enums\ConfigurationsTypeEnum::ALL as $key)
                                    <option value="{{$key}}"> {{ $key }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">{{ __('translation.configurations.form.hints.required') }}</div>
                            @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-12">
                            <label for="name" class="form-label">{{ __('translation.configurations.form.fields.name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   required
                                   placeholder="{{ __('translation.configurations.form.placeholders.name') }}">
                            <div class="invalid-feedback">
                                {{ __('translation.configurations.form.validation.name') }}
                            </div>
                        </div>
                        <!-- Score Field -->
                        <div class="col-md-12">
                            <label for="score" class="form-label">{{ __('translation.configurations.form.fields.score') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="score"
                                   name="score"
                                   placeholder="{{ __('translation.configurations.form.placeholders.score') }}">
                            <div class="form-text">{{ __('translation.configurations.form.hints.score') }}</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.configurations.form.buttons.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="afm_btnSaveIt" form="configurationsForm">
                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.configurations.form.buttons.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
