<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRoleModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.roles.form.modal.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="add-form" id="roleForm" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="editRoleId" name="id">

                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-12">
                            <label for="name" class="form-label">{{ __('translation.roles.form.fields.name') }}</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   name="name"
                                   readonly
                                   placeholder="{{ __('translation.roles.form.placeholders.name') }}">
                            <div class="form-text">{{ __('translation.roles.form.hints.readonly_name') }}</div>
                        </div>

                        <!-- Roles -->
                        <div class="col-md-12">
                            <label for="roles" class="form-label">{{ __('translation.roles.roles') }} <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('roles') is-invalid @enderror"
                                    id="roles" name="roles[]" required multiple
                                    data-placeholder="{{ __('translation.messages.select_an_option') }}">
                                    @foreach(\App\Enums\RoleEnum::ALL as $role)
                                        <option value="{{$role}}">{{$role}}</option>
                                    @endforeach
                            </select>
                            @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>{{ __('translation.roles.form.buttons.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="afm_btnSaveIt" form="roleForm">
                    <i class="bi bi-check-circle me-1"></i>{{ __('translation.roles.form.buttons.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
