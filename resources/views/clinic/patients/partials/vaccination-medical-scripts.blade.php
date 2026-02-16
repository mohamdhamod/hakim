<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Initialize Choices.js for Vaccination Type Select
    // ========================================
    window.vaccinationChoicesInstance = null;
    const vaccinationModalElement = document.getElementById('newVaccinationModal');
    if (vaccinationModalElement) {
        vaccinationModalElement.addEventListener('shown.bs.modal', async function() {
            const vaccinationTypeSelect = document.getElementById('vaccinationType');
            if (vaccinationTypeSelect && !window.vaccinationChoicesInstance && window.loadChoices) {
                try {
                    const Choices = await window.loadChoices();
                    window.vaccinationChoicesInstance = new Choices(vaccinationTypeSelect, {
                        searchEnabled: true,
                        itemSelectText: '',
                        shouldSort: false,
                        allowHTML: true,
                        searchPlaceholderValue: window.i18n?.common?.search || '{{ __('translation.common.search') }}',
                        noResultsText: window.i18n?.common?.no_results_found || '{{ __('translation.common.no_results_found') }}',
                        noChoicesText: window.i18n?.common?.no_choices || '{{ __('translation.common.no_choices') }}',
                    });
                } catch (error) {
                    console.error('Failed to initialize Choices.js for vaccination:', error);
                }
            }
        });

        // Reset Choices.js on modal hide
        vaccinationModalElement.addEventListener('hidden.bs.modal', function() {
            if (window.vaccinationChoicesInstance) {
                window.vaccinationChoicesInstance.destroy();
                window.vaccinationChoicesInstance = null;
            }
        });
    }

    // ========================================
    // Vaccination View Handlers
    // ========================================
    document.querySelectorAll('.view-btn[data-type="vaccination"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.dataset.model);
            openVaccinationView(data);
        });
    });

    // ========================================
    // Vaccination Edit Handlers
    // ========================================
    document.querySelectorAll('.edit-btn[data-type="vaccination"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            openVaccinationEdit(data);
        });
    });

    // ========================================
    // Vaccination Delete Handlers
    // ========================================
    document.querySelectorAll('.delete-btn[data-type="vaccination"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            const deleteUrl = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/vaccinations") }}/${data.id}`;
            confirmDelete(data, deleteUrl, window.i18n, data.vaccination_type?.name || '');
        });
    });

    // ========================================
    // Vaccination Form Submit (handleFormSubmit)
    // ========================================
    document.querySelectorAll('.add-vaccination-form').forEach(form => {
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
// Vaccination Quick View
// ========================================
window.openVaccinationView = function(vaccination) {
    if (!vaccination) {
        SwalUtil.toast('{{ __("translation.vaccination") }} {{ __("translation.common.not_found") }}', 'error');
        return;
    }

    const statusColors = {
        'completed': 'success',
        'missed': 'danger',
        'scheduled': 'warning',
        'cancelled': 'secondary'
    };

    const statusIcons = {
        'completed': 'check-circle',
        'missed': 'times-circle',
        'scheduled': 'clock',
        'cancelled': 'ban'
    };

    const statusTexts = {
        'completed': '{{ __("translation.completed") }}',
        'missed': '{{ __("translation.missed") }}',
        'scheduled': '{{ __("translation.scheduled") }}',
        'cancelled': '{{ __("translation.cancelled") }}'
    };

    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.vaccination_type') }}</label>
                <p class="fw-bold">${vaccination.vaccination_type.name}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.vaccination_date') }}</label>
                <p class="fw-bold">${new Date(vaccination.vaccination_date).toLocaleDateString('{{ app()->getLocale() }}', {year: 'numeric', month: 'short', day: 'numeric'})}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.disease_prevented') }}</label>
                <p><i class="fas fa-shield-virus text-danger me-1"></i> ${vaccination.vaccination_type.disease_prevented}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.dose_number') }}</label>
                <p><span class="badge bg-info">${vaccination.dose_number}</span></p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.status') }}</label>
                <p>
                    <span class="badge bg-${statusColors[vaccination.status] || 'secondary'}">
                        <i class="fas fa-${statusIcons[vaccination.status] || 'question'}"></i>
                        ${statusTexts[vaccination.status] || vaccination.status}
                    </span>
                </p>
            </div>
            ${vaccination.next_dose_due_date ? `
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.next_dose_date') }}</label>
                    <p class="text-warning"><i class="fas fa-clock me-1"></i> ${new Date(vaccination.next_dose_due_date).toLocaleDateString('{{ app()->getLocale() }}', {year: 'numeric', month: 'short', day: 'numeric'})}</p>
                </div>
            ` : ''}
            ${vaccination.batch_number ? `
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.batch_number') }}</label>
                    <p>${vaccination.batch_number}</p>
                </div>
            ` : ''}
            ${vaccination.manufacturer ? `
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.manufacturer') }}</label>
                    <p>${vaccination.manufacturer}</p>
                </div>
            ` : ''}
            ${vaccination.site ? `
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.injection_site') }}</label>
                    <p>${vaccination.site}</p>
                </div>
            ` : ''}
            ${vaccination.reaction_notes ? `
                <div class="col-12">
                    <label class="text-muted small">{{ __('translation.reaction_notes') }}</label>
                    <p class="text-muted">${vaccination.reaction_notes}</p>
                </div>
            ` : ''}
        </div>
    `;

    document.getElementById('vaccinationDetailsContent').innerHTML = content;
    showModal('viewVaccinationModal');
};

// ========================================
// Vaccination Edit
// ========================================
window.openVaccinationEdit = function(data) {
    const modal = document.getElementById('newVaccinationModal');
    const form = modal.querySelector('.add-vaccination-form');
    const modalTitle = modal.querySelector('.modal-title');
    const saveButton = modal.querySelector('#vaccinationSaveBtn');

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
    form.action = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/vaccinations") }}/${data.id}`;

    // Update modal title and button
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>{{ __("translation.edit_vaccination") }}';
    }
    if (saveButton) {
        saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.general.update") }}';
    }

    // Clear previous errors
    clearFormErrors(modal);

    // Normalize dates (ISO → YYYY-MM-DD for date inputs)
    const normalized = { ...data };
    ['vaccination_date', 'next_dose_due_date'].forEach(key => {
        if (normalized[key]) normalized[key] = String(normalized[key]).split('T')[0];
    });

    // Fill form with data
    fillForm(modal, normalized);

    // Show modal
    showModal(modal);

    // Sync Choices.js UI after modal is visible
    modal.addEventListener('shown.bs.modal', async () => {
        for (const fieldId of ['vaccinationType', 'injectionSite']) {
            const el = document.getElementById(fieldId);
            if (el) await window.setChoicesSelectValue(fieldId, el.value);
        }
    }, { once: true });
};

// ========================================
// Vaccination Create
// ========================================
window.openVaccinationCreate = function() {
    const modal = document.getElementById('newVaccinationModal');
    const form = modal.querySelector('.add-vaccination-form');
    const modalTitle = modal.querySelector('.modal-title');
    const saveButton = modal.querySelector('#vaccinationSaveBtn');

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
    form.action = '{{ route("patients.vaccinations.store", $patient) }}';

    // Update modal title and button
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-syringe me-2"></i>{{ __("translation.add_vaccination") }}';
    }
    if (saveButton) {
        saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.common.save") }}';
    }

    // Clear previous errors
    clearFormErrors(modal);

    // Reset date to today and dose to 1
    const vacDateInput = form.querySelector('input[name="vaccination_date"]');
    if (vacDateInput) vacDateInput.value = '{{ date("Y-m-d") }}';
    const doseInput = form.querySelector('input[name="dose_number"]');
    if (doseInput) doseInput.value = '1';

    // Show modal
    showModal(modal);

    // Reset Choices.js selects after modal is visible
    modal.addEventListener('shown.bs.modal', async () => {
        await window.setChoicesSelectValue('vaccinationType', '');
        await window.setChoicesSelectValue('injectionSite', '');
    }, { once: true });
};
</script>
