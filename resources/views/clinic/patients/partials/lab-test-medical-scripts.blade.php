<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Initialize Choices.js for Lab Test Type Select
    // ========================================
    window.labTestChoicesInstance = null;
    const labTestModalElement = document.getElementById('newLabTestModal');
    if (labTestModalElement) {
        labTestModalElement.addEventListener('shown.bs.modal', async function() {
            const labTestTypeSelect = document.getElementById('labTestType');
            if (labTestTypeSelect && !window.labTestChoicesInstance && window.loadChoices) {
                try {
                    const Choices = await window.loadChoices();
                    window.labTestChoicesInstance = new Choices(labTestTypeSelect, {
                        searchEnabled: true,
                        itemSelectText: '',
                        shouldSort: false,
                        allowHTML: true,
                        searchPlaceholderValue: window.i18n?.common?.search || '{{ __('translation.common.search') }}',
                        noResultsText: window.i18n?.common?.no_results_found || '{{ __('translation.common.no_results_found') }}',
                        noChoicesText: window.i18n?.common?.no_choices || '{{ __('translation.common.no_choices') }}',
                    });

                    // Update unit and normal range when test type changes
                    labTestTypeSelect.addEventListener('change', function(e) {
                        const selectedOption = e.target.options[e.target.selectedIndex];
                        if (selectedOption.value) {
                            const unit = selectedOption.getAttribute('data-unit');
                            const min = selectedOption.getAttribute('data-min');
                            const max = selectedOption.getAttribute('data-max');
                            
                            document.getElementById('resultUnit').textContent = unit || '-';
                            
                            if (min && max) {
                                document.getElementById('normalRangeText').textContent = `${min} - ${max} ${unit}`;
                                document.getElementById('normalRangeInfo').style.display = 'block';
                            } else {
                                document.getElementById('normalRangeInfo').style.display = 'none';
                            }
                        }
                    });
                } catch (error) {
                    console.error('Failed to initialize Choices.js:', error);
                }
            }
        });

        // Reset Choices.js on modal hide
        labTestModalElement.addEventListener('hidden.bs.modal', function() {
            if (window.labTestChoicesInstance) {
                window.labTestChoicesInstance.destroy();
                window.labTestChoicesInstance = null;
            }
        });
    }

    // ========================================
    // Lab Test View Handlers
    // ========================================
    document.querySelectorAll('.view-btn[data-type="labTest"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.dataset.model);
            openLabTestView(data);
        });
    });

    // ========================================
    // Lab Test Edit Handlers
    // ========================================
    document.querySelectorAll('.edit-btn[data-type="labTest"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            openLabTestEdit(data);
        });
    });

    // ========================================
    // Lab Test Delete Handlers
    // ========================================
    document.querySelectorAll('.delete-btn[data-type="labTest"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            const deleteUrl = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/lab-tests") }}/${data.id}`;
            confirmDelete(data, deleteUrl, window.i18n, data.lab_test_type?.name || '');
        });
    });

    // ========================================
    // Lab Test Form Submit (handleFormSubmit)
    // ========================================
    document.querySelectorAll('.add-lab-test-form').forEach(form => {
        if (!form.__handleSubmitBound) {
            form.__handleSubmitBound = true;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                window.handleFormSubmit(e, this);
            });
        }
    });
});

// ========================================
// Lab Test Quick View
// ========================================
window.openLabTestView = function(labTest) {
    if (!labTest) {
        SwalUtil.toast('{{ __("translation.lab_test") }} {{ __("translation.common.not_found") }}', 'error');
        return;
    }

    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.test_type') }}</label>
                <p class="fw-bold">${labTest.lab_test_type.name}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.test_date') }}</label>
                <p class="fw-bold">${new Date(labTest.test_date).toLocaleDateString('{{ app()->getLocale() }}', {year: 'numeric', month: 'short', day: 'numeric'})}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.result_value') }}</label>
                <p class="fw-bold ${labTest.is_abnormal ? 'text-danger' : 'text-success'}">
                    ${labTest.result_value} ${labTest.lab_test_type.unit || ''}
                </p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.normal_range') }}</label>
                <p>${labTest.lab_test_type.normal_range_text || (labTest.lab_test_type.normal_range_min && labTest.lab_test_type.normal_range_max ? `${labTest.lab_test_type.normal_range_min} - ${labTest.lab_test_type.normal_range_max} ${labTest.lab_test_type.unit || ''}` : '-')}</p>
            </div>
            ${labTest.lab_test_type.category ? `
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.category') }}</label>
                    <p>${labTest.lab_test_type.category}</p>
                </div>
            ` : ''}
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.status') }}</label>
                <p>
                    ${labTest.is_abnormal ? 
                        '<span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> {{ __("translation.abnormal") }}</span>' : 
                        '<span class="badge bg-success"><i class="fas fa-check-circle"></i> {{ __("translation.normal") }}</span>'}
                </p>
            </div>
            ${labTest.doctor_notes ? `
                <div class="col-12">
                    <label class="text-muted small">{{ __('translation.notes') }}</label>
                    <p class="text-muted">${labTest.doctor_notes}</p>
                </div>
            ` : ''}
            ${labTest.lab_name ? `
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.lab_name') }}</label>
                    <p>${labTest.lab_name}</p>
                </div>
            ` : ''}
            ${labTest.lab_reference_number ? `
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.lab_reference_number') }}</label>
                    <p>${labTest.lab_reference_number}</p>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('labTestDetailsContent').innerHTML = content;
    showModal('viewLabTestModal');
};

// ========================================
// Lab Test Edit
// ========================================
window.openLabTestEdit = function(data) {
    const modal = document.getElementById('newLabTestModal');
    const form = modal.querySelector('.add-lab-test-form');
    const modalTitle = modal.querySelector('.modal-title');
    const saveButton = modal.querySelector('#labTestSaveBtn');

    if (!modal || !form) {
        console.error('Modal or form not found');
        return;
    }

    // Reset form
    form.reset();

    // Add method override for PUT request
    let methodInput = form.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        form.appendChild(methodInput);
    }
    methodInput.value = 'PUT';

    // Set form action
    form.action = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/lab-tests") }}/${data.id}`;

    // Update modal title and button
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>{{ __("translation.edit_lab_test") }}';
    }
    if (saveButton) {
        saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.general.update") }}';
    }

    // Clear previous errors
    clearFormErrors(modal);

    // Normalize dates (ISO → YYYY-MM-DD for date inputs)
    const normalized = { ...data };
    ['test_date'].forEach(key => {
        if (normalized[key]) normalized[key] = String(normalized[key]).split('T')[0];
    });

    // Map notes field
    if (normalized.doctor_notes) {
        normalized.notes = normalized.doctor_notes;
    }

    // Fill form with data
    fillForm(modal, normalized);

    // Show modal
    showModal(modal);

    // Sync Choices.js UI after modal is visible
    modal.addEventListener('shown.bs.modal', async () => {
        const el = document.getElementById('labTestType');
        if (el) await window.setChoicesSelectValue('labTestType', el.value);

        // Update unit and normal range display
        const selectedOption = el?.options[el.selectedIndex];
        if (selectedOption?.value) {
            const unit = selectedOption.getAttribute('data-unit');
            const min = selectedOption.getAttribute('data-min');
            const max = selectedOption.getAttribute('data-max');
            
            document.getElementById('resultUnit').textContent = unit || '-';
            
            if (min && max) {
                document.getElementById('normalRangeText').textContent = `${min} - ${max} ${unit}`;
                document.getElementById('normalRangeInfo').style.display = 'block';
            } else {
                document.getElementById('normalRangeInfo').style.display = 'none';
            }
        }
    }, { once: true });
};

// ========================================
// Lab Test Create
// ========================================
window.openLabTestCreate = function() {
    const modal = document.getElementById('newLabTestModal');
    const form = modal.querySelector('.add-lab-test-form');
    const modalTitle = modal.querySelector('.modal-title');
    const saveButton = modal.querySelector('#labTestSaveBtn');

    if (!modal || !form) {
        console.error('Modal or form not found');
        return;
    }

    // Reset form
    form.reset();

    // Remove method override (use POST for create)
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();

    // Set form action for create
    form.action = '{{ route("patients.lab-tests.store", $patient) }}';

    // Update modal title and button
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-flask me-2"></i>{{ __("translation.add_lab_test") }}';
    }
    if (saveButton) {
        saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.common.save") }}';
    }

    // Clear previous errors
    clearFormErrors(modal);

    // Reset unit and normal range display
    document.getElementById('resultUnit').textContent = '-';
    document.getElementById('normalRangeInfo').style.display = 'none';

    // Reset date to today
    const testDateInput = form.querySelector('input[name="test_date"]');
    if (testDateInput) {
        testDateInput.value = '{{ date("Y-m-d") }}';
    }

    // Show modal
    showModal(modal);

    // Reset Choices.js select after modal is visible
    modal.addEventListener('shown.bs.modal', async () => {
        await window.setChoicesSelectValue('labTestType', '');
    }, { once: true });
};
</script>
