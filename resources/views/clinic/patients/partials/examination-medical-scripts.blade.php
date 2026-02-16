<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Examination View Handlers
    // ========================================
    document.querySelectorAll('.view-btn[data-type="examination"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.dataset.model);
            openExaminationView(data);
        });
    });

    // ========================================
    // Examination Delete Handlers
    // ========================================
    document.querySelectorAll('.delete-btn[data-type="examination"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            const deleteUrl = `{{ url("/" . app()->getLocale() . "/clinic/examinations") }}/${data.id}`;
            confirmDelete(data, deleteUrl, window.i18n, data.examination_number || '');
        });
    });

    // ========================================
    // Examination Edit Handlers
    // ========================================
    document.querySelectorAll('.edit-btn[data-type="examination"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            openExaminationEdit(data);
        });
    });

    // ========================================
    // Examination Form Submit (handleFormSubmit)
    // ========================================
    document.querySelectorAll('.add-examination-form').forEach(form => {
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
// Examination Quick View
// ========================================
window.openExaminationView = function(exam) {
    if (!exam) {
        SwalUtil.toast('{{ __("translation.examination.not_found") }}', 'error');
        return;
    }

    const formatDate = (dateStr) => {
        if (!dateStr) return '-';
        return new Date(dateStr).toLocaleDateString('{{ app()->getLocale() }}', {year: 'numeric', month: 'short', day: 'numeric'});
    };
    const formatDateTime = (dateStr) => {
        if (!dateStr) return '-';
        return new Date(dateStr).toLocaleDateString('{{ app()->getLocale() }}', {year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'});
    };

    // Build vital signs cards
    let vitalSignsHtml = '';
    const vitals = [
        { label: '{{ __("translation.examination.temperature") }}', value: exam.temperature, unit: '°C', icon: 'thermometer-half', color: 'danger' },
        { label: '{{ __("translation.examination.blood_pressure") }}', value: exam.blood_pressure_systolic && exam.blood_pressure_diastolic ? exam.blood_pressure_systolic + '/' + exam.blood_pressure_diastolic : null, unit: 'mmHg', icon: 'tint', color: 'primary' },
        { label: '{{ __("translation.examination.pulse_rate") }}', value: exam.pulse_rate, unit: 'bpm', icon: 'heartbeat', color: 'danger' },
        { label: '{{ __("translation.examination.respiratory_rate") }}', value: exam.respiratory_rate, unit: '/min', icon: 'lungs', color: 'info' },
        { label: '{{ __("translation.examination.oxygen_saturation") }}', value: exam.oxygen_saturation, unit: '%', icon: 'wind', color: 'success' },
        { label: '{{ __("translation.examination.weight") }}', value: exam.weight, unit: 'kg', icon: 'weight', color: 'secondary' },
        { label: '{{ __("translation.examination.height") }}', value: exam.height, unit: 'cm', icon: 'ruler-vertical', color: 'secondary' },
    ];
    
    const hasVitals = vitals.some(v => v.value);
    if (hasVitals) {
        vitalSignsHtml = `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-heartbeat text-danger me-2"></i>{{ __('translation.examination.vital_signs') }}</h6>
                <div class="row g-2">
                    ${vitals.filter(v => v.value).map(v => `
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-2 text-center">
                                <i class="fas fa-${v.icon} text-${v.color} mb-1"></i>
                                <div class="fw-bold">${v.value} <small class="text-muted">${v.unit}</small></div>
                                <small class="text-muted">${v.label}</small>
                            </div>
                        </div>
                    `).join('')}
                    ${exam.bmi ? `
                        <div class="col-md-3 col-6">
                            <div class="border rounded p-2 text-center">
                                <i class="fas fa-calculator text-info mb-1"></i>
                                <div class="fw-bold">${parseFloat(exam.bmi).toFixed(1)}</div>
                                <small class="text-muted">BMI</small>
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    const content = `
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.examination.number') }}</label>
                <p class="fw-bold">${exam.examination_number || '-'}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.examination.date') }}</label>
                <p class="fw-bold">${formatDateTime(exam.examination_date)}</p>
            </div>
        </div>

        ${vitalSignsHtml}

        ${exam.chief_complaint ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-comment-medical text-info me-2"></i>{{ __('translation.examination.chief_complaint') }}</h6>
                <p>${exam.chief_complaint}</p>
            </div>
        ` : ''}

        ${exam.present_illness_history ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2">{{ __('translation.examination.present_illness_history') }}</h6>
                <p>${exam.present_illness_history}</p>
            </div>
        ` : ''}

        ${exam.physical_examination ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-search text-primary me-2"></i>{{ __('translation.examination.physical_examination') }}</h6>
                <p>${exam.physical_examination}</p>
            </div>
        ` : ''}

        ${exam.diagnosis || exam.icd_code ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-clipboard-check text-success me-2"></i>{{ __('translation.examination.diagnosis') }}</h6>
                ${exam.diagnosis ? `<p>${exam.diagnosis}</p>` : ''}
                ${exam.icd_code ? `<small class="text-muted">ICD: <strong>${exam.icd_code}</strong></small>` : ''}
            </div>
        ` : ''}

        ${exam.treatment_plan ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2">{{ __('translation.examination.treatment_plan') }}</h6>
                <p>${exam.treatment_plan}</p>
            </div>
        ` : ''}

        ${exam.prescriptions ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-prescription me-2"></i>{{ __('translation.examination.prescriptions') }}</h6>
                <p style="white-space: pre-line;">${exam.prescriptions}</p>
            </div>
        ` : ''}

        ${exam.lab_tests_ordered || exam.imaging_ordered ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-flask text-warning me-2"></i>{{ __('translation.examination.lab_imaging') }}</h6>
                <div class="row">
                    ${exam.lab_tests_ordered ? `
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('translation.examination.lab_tests_ordered') }}</label>
                            <p>${exam.lab_tests_ordered}</p>
                        </div>
                    ` : ''}
                    ${exam.imaging_ordered ? `
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('translation.examination.imaging_ordered') }}</label>
                            <p>${exam.imaging_ordered}</p>
                        </div>
                    ` : ''}
                </div>
            </div>
        ` : ''}

        ${exam.follow_up_date || exam.follow_up_notes ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-calendar-check text-secondary me-2"></i>{{ __('translation.examination.follow_up') }}</h6>
                ${exam.follow_up_date ? `<p><strong>{{ __('translation.examination.follow_up_date') }}:</strong> ${formatDate(exam.follow_up_date)}</p>` : ''}
                ${exam.follow_up_notes ? `<p>${exam.follow_up_notes}</p>` : ''}
            </div>
        ` : ''}

        ${exam.doctor_notes ? `
            <div class="mb-3">
                <h6 class="text-muted border-bottom pb-2"><i class="fas fa-notes-medical text-muted me-2"></i>{{ __('translation.examination.doctor_notes') }}</h6>
                <p>${exam.doctor_notes}</p>
            </div>
        ` : ''}
    `;

    document.getElementById('examinationDetailsContent').innerHTML = content;
    
    document.getElementById('examinationPrintLink').href = '{{ url("/" . app()->getLocale() . "/clinic/examinations") }}/' + exam.id + '/print';
    
    showModal('viewExaminationModal');
};

// ========================================
// Examination Create
// ========================================
window.openExaminationCreate = function() {
    const modal = document.getElementById('newExaminationModal');
    const form = modal.querySelector('.add-examination-form');
    const modalTitle = modal.querySelector('.modal-title');
    const saveButton = modal.querySelector('#examinationSaveBtn');

    if (!form) return;

    // Reset form
    form.reset();
    clearFormErrors(form);

    // Remove method override (use POST for create)
    const methodInput = form.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();

    // Set form action for create
    form.action = '{{ route("clinic.examinations.store") }}';

    // Update modal title and button
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-stethoscope me-2"></i>{{ __('translation.examination.new_for_patient') }}: {{ $patient->full_name }}';
    }
    if (saveButton) {
        saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __('translation.examination.save') }}';
    }

    // Reset date to now
    const dateInput = form.querySelector('input[name="examination_date"]');
    if (dateInput) {
        const now = new Date();
        dateInput.value = now.getFullYear() + '-' + 
            String(now.getMonth() + 1).padStart(2, '0') + '-' + 
            String(now.getDate()).padStart(2, '0') + 'T' + 
            String(now.getHours()).padStart(2, '0') + ':' + 
            String(now.getMinutes()).padStart(2, '0');
    }

    showModal('newExaminationModal');
};

// ========================================
// Examination Edit
// ========================================
window.openExaminationEdit = function(data) {
    const modal = document.getElementById('newExaminationModal');
    const form = modal.querySelector('.add-examination-form');
    const modalTitle = modal.querySelector('.modal-title');
    const saveButton = modal.querySelector('#examinationSaveBtn');

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
    form.action = `{{ url("/" . app()->getLocale() . "/clinic/examinations") }}/${data.id}`;

    // Update modal title and button
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>{{ __("translation.examination.details") }} - ' + (data.examination_number || '');
    }
    if (saveButton) {
        saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.general.update") }}';
    }

    // Clear previous errors
    clearFormErrors(modal);

    // Normalize data for form filling
    const normalized = { ...data };

    // Convert examination_date to datetime-local format
    if (normalized.examination_date) {
        const d = new Date(normalized.examination_date);
        if (!isNaN(d)) {
            normalized.examination_date = d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0') + 'T' +
                String(d.getHours()).padStart(2, '0') + ':' +
                String(d.getMinutes()).padStart(2, '0');
        }
    }

    // Convert follow_up_date to date format
    if (normalized.follow_up_date) {
        normalized.follow_up_date = String(normalized.follow_up_date).split('T')[0];
    }

    // Fill form with data
    fillForm(modal, normalized);

    // Set examination number (readonly field without name attribute)
    const examNumberInput = form.querySelector('input[readonly].bg-light');
    if (examNumberInput) {
        examNumberInput.value = data.examination_number || '';
    }

    // Show modal
    showModal(modal);
};
</script>
