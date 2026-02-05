<!-- Add/Edit Specialty Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>{{ __('translation.specialties.form.title_add') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="add-form" action="{{ route('specialties.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <!-- Key Field -->
                        <div class="col-md-6 mb-3">
                            <label for="key" class="form-label">
                                {{ __('translation.specialties.form.fields.key') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="key" 
                                   name="key" 
                                   placeholder="{{ __('translation.specialties.form.placeholders.key') }}"
                                   pattern="[a-z_]+"
                                   required>
                            <div class="form-text">{{ __('translation.specialties.form.hints.key') }}</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                {{ __('translation.specialties.form.fields.name') }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   placeholder="{{ __('translation.specialties.form.placeholders.name') }}"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Icon Field -->
                        <div class="col-md-4 mb-3">
                            <label for="icon" class="form-label">
                                {{ __('translation.specialties.form.fields.icon') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i id="icon-preview" class="fas fa-stethoscope"></i></span>
                                <input type="text" 
                                       class="form-control" 
                                       id="icon" 
                                       name="icon" 
                                       placeholder="{{ __('translation.specialties.form.placeholders.icon') }}"
                                       value="fa-stethoscope">
                            </div>
                            <div class="form-text">{{ __('translation.specialties.form.hints.icon') }}</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Color Field -->
                        <div class="col-md-4 mb-3">
                            <label for="color" class="form-label">
                                {{ __('translation.specialties.form.fields.color') }}
                            </label>
                            <div class="input-group">
                                <input type="color" 
                                       class="form-control form-control-color" 
                                       id="color" 
                                       name="color" 
                                       value="#4A90D9"
                                       title="{{ __('translation.specialties.form.placeholders.color') }}">
                                <input type="text" 
                                       class="form-control" 
                                       id="color_text" 
                                       placeholder="#4A90D9"
                                       value="#4A90D9"
                                       pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Sort Order Field -->
                        <div class="col-md-4 mb-3">
                            <label for="sort_order" class="form-label">
                                {{ __('translation.specialties.form.fields.sort_order') }}
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="sort_order" 
                                   name="sort_order" 
                                   placeholder="{{ __('translation.specialties.form.placeholders.sort_order') }}"
                                   value="0"
                                   min="0">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            {{ __('translation.specialties.form.fields.description') }}
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="{{ __('translation.specialties.form.placeholders.description') }}"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <!-- Topics Info (shown only in edit mode) -->
                    <div class="mb-3 edit-only" id="topics-info" style="display: none;">
                        <label class="form-label">
                            <i class="bi bi-tags me-1"></i>
                            {{ __('translation.specialties.topics') }}
                        </label>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ __('translation.specialties.topics_info') ?? 'Topics can be managed after saving the specialty. Use the "Topics" button in the table to add, edit, or delete topics.' }}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>{{ __('translation.specialties.form.btn_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="afm_btnSaveIt">
                        <i class="bi bi-check-circle me-1"></i>{{ __('translation.specialties.form.btn_save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sync color picker with text input
    const colorPicker = document.getElementById('color');
    const colorText = document.getElementById('color_text');
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');

    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value.toUpperCase();
        });

        colorText.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
    }

    // Update icon preview
    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function() {
            iconPreview.className = 'fas ' + this.value;
        });
    }
    
    // Override fillForm after DOMContentLoaded
    const originalFillForm = window.fillForm;
    window.fillForm = function(modal, data) {
        // Call original fillForm first
        if (typeof originalFillForm === 'function') {
            originalFillForm(modal, data);
        }
        
        // Sync color text field
        const colorPickerEl = modal.querySelector('#color');
        const colorTextEl = modal.querySelector('#color_text');
        if (colorPickerEl && colorTextEl && data.color) {
            colorTextEl.value = data.color;
        }

        // Update icon preview
        const iconPreviewEl = modal.querySelector('#icon-preview');
        const iconInputEl = modal.querySelector('#icon');
        if (iconPreviewEl && iconInputEl && data.icon) {
            iconPreviewEl.className = 'fas ' + data.icon;
        }
    };
});
</script>
