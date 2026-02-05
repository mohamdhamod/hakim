<div id="add-modal"   class="modal fade"  tabindex="-1">
    <form class="modal-dialog modal-lg modal-dialog-scrollable add-form" id="frm_add" method="POST" >
        {{ csrf_field() }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row" >
                    <div class="form-floating col-md-6 mb-3 data">
                        <input type="text" name="name"
                               class="form-control @error('value') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="{{ __('translation.team_members.fields.name') }}">
                        <label for="name">{{ __('translation.team_members.fields.name') }}</label>
                    </div>
                    <div class="form-floating col-md-6 mb-3 data">
                        <input type="hidden" name="id" >
                        <input type="text" name="title" required
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="">
                           <label for="title">{{ __('translation.team_members.fields.title') }} {{'*'}}</label>
                    </div>

                    <!-- LinkedIn -->
                    <div class="form-floating col-md-12 mb-3 data">
                        <input type="url" name="linkedin"
                               class="form-control @error('linkedin') is-invalid @enderror"
                               value="{{ old('linkedin') }}" placeholder="{{ __('translation.team_members.fields.linkedin') }}">
                        <label for="linkedin">{{ __('translation.team_members.fields.linkedin') }}</label>
                    </div>


                    <div class="row col-md-12 mb-3">
                        <label for="image" id="image" class="col-sm-12 col-form-label">{{ __('translation.team_members.fields.image') }} *</label>
                        <div class="col-sm-12">
                            <input class="form-control @error('image') is-invalid @enderror" type="file"  name="image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translation.view.modal.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ __('translation.general.save') }}</button>
            </div>
        </div>
    </form>
</div>
