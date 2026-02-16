<script>
    // ===========================
    // Chronic Diseases Scripts
    // (Following specialties pattern)
    // ===========================

    // Store the currently viewed disease (for monitoring from view modal)
    let currentViewedDisease = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Edit button handler
        document.querySelectorAll('.edit-btn[data-type="chronicDisease"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const json = this.getAttribute('data-model');
                const data = JSON.parse(json);
                window.openChronicEdit(data);
            });
        });

        // View button handler
        document.querySelectorAll('.view-btn[data-type="chronicDisease"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const json = this.getAttribute('data-model');
                const data = JSON.parse(json);
                window.openChronicView(data);
            });
        });

        // Monitoring button handler
        document.querySelectorAll('.monitoring-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const json = this.getAttribute('data-model');
                const data = JSON.parse(json);
                window.openMonitoring(data);
            });
        });

        // Delete button handler
        document.querySelectorAll('.delete-btn[data-type="chronicDisease"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const json = this.getAttribute('data-model');
                const data = JSON.parse(json);
                const deleteUrl = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/chronic-diseases") }}/${data.id}`;
                confirmDelete(data, deleteUrl, window.i18n, data.chronic_disease_type?.name || '');
            });
        });

        // Toggle custom parameter name field
        const paramSelect = document.getElementById('monitoringParameterName');
        if (paramSelect) {
            paramSelect.addEventListener('change', function() {
                const customGroup = document.getElementById('customParameterGroup');
                if (this.value === 'other') {
                    customGroup.style.display = '';
                } else {
                    customGroup.style.display = 'none';
                    document.getElementById('monitoringCustomParameter').value = '';
                }
            });
        }

        // Handle chronic disease form submission
        const addChronicForm = document.querySelector('.add-chronic-form');
        if (addChronicForm && !addChronicForm.__handleSubmitBound) {
            addChronicForm.__handleSubmitBound = true;
            addChronicForm.addEventListener('submit', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                window.handleFormSubmit(e, this);
            });
        }

        // Handle monitoring form submission
        const addMonitoringForm = document.querySelector('.add-monitoring-form');
        if (addMonitoringForm && !addMonitoringForm.__handleSubmitBound) {
            addMonitoringForm.__handleSubmitBound = true;
            addMonitoringForm.addEventListener('submit', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                // Resolve "other" parameter name before submitting
                const paramNameSelect = document.getElementById('monitoringParameterName');
                const hiddenParam = document.getElementById('monitoringParameterNameHidden');
                if (paramNameSelect && hiddenParam) {
                    hiddenParam.value = paramNameSelect.value === 'other'
                        ? document.getElementById('monitoringCustomParameter').value
                        : paramNameSelect.value;
                }

                window.handleFormSubmit(e, this);
            });
        }
    });

    // ===========================
    // Open Edit (like specialties openEdit)
    // ===========================

    window.openChronicEdit = function(data) {
        const modal = document.getElementById('newChronicDiseaseModal');
        const form = modal.querySelector('.add-chronic-form');
        const modalTitle = modal.querySelector('.modal-title');
        const saveButton = modal.querySelector('#chronicDiseaseSaveBtn');

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
        form.action = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/chronic-diseases") }}/${data.id}`;

        // Update modal title and button
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>{{ __("translation.edit_chronic_disease") }}';
        }
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.general.update") }}';
        }

        // Clear previous errors
        clearFormErrors(modal);

        // Normalize dates (ISO → YYYY-MM-DD for date inputs)
        const normalized = { ...data };
        ['diagnosis_date', 'next_followup_date'].forEach(key => {
            if (normalized[key]) normalized[key] = String(normalized[key]).split('T')[0];
        });

        // Fill form with data (matches fields by name attribute — like specialties)
        fillForm(modal, normalized);

        // Show modal
        showModal(modal);

        // Sync Choices.js UI after modal is visible
        modal.addEventListener('shown.bs.modal', async () => {
            for (const fieldId of ['diseaseType', 'diseaseSeverity', 'diseaseStatus']) {
                const el = document.getElementById(fieldId);
                if (el) await window.setChoicesSelectValue(fieldId, el.value);
            }
        }, { once: true });
    };

    // ===========================
    // Open View (like specialties openView)
    // ===========================

    window.openChronicView = function(data) {
        currentViewedDisease = data;

        const modal = document.getElementById('viewChronicDiseaseModal');
        const dataContainer = modal.querySelector('#chronicDiseaseDetailsContent');

        if (!modal || !dataContainer) {
            console.error('View modal or data container not found');
            return;
        }

        // Translation maps
        const statusTexts = {
            'active': '{{ __("translation.active") }}',
            'in_remission': '{{ __("translation.in_remission") }}',
            'resolved': '{{ __("translation.resolved") }}'
        };

        const severityTexts = {
            'mild': '{{ __("translation.mild") }}',
            'moderate': '{{ __("translation.moderate") }}',
            'severe': '{{ __("translation.severe") }}'
        };

        // Format dates
        const diagnosisDate = new Date(data.diagnosis_date).toLocaleDateString('{{ app()->getLocale() }}', {
            year: 'numeric', month: 'long', day: 'numeric'
        });

        const lastFollowup = data.last_followup_date ? new Date(data.last_followup_date).toLocaleDateString('{{ app()->getLocale() }}', {
            year: 'numeric', month: 'long', day: 'numeric'
        }) : '-';

        const nextFollowup = data.next_followup_date ? new Date(data.next_followup_date).toLocaleDateString('{{ app()->getLocale() }}', {
            year: 'numeric', month: 'long', day: 'numeric'
        }) : '-';

        // Badge colors
        const severityClass = data.severity === 'severe' ? 'danger' : (data.severity === 'moderate' ? 'warning' : 'success');
        const statusClass = data.status === 'active' ? 'danger' : (data.status === 'in_remission' ? 'warning' : 'success');

        // Build HTML (like specialties field display)
        const content = `
            <div class="row g-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="fw-bold">${data.chronic_disease_type.name}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-tag"></i> ${data.chronic_disease_type.category}
                                ${data.chronic_disease_type.icd11_code ? `<span class="ms-2">| ICD-11: ${data.chronic_disease_type.icd11_code}</span>` : ''}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-${statusClass} mb-1">${statusTexts[data.status] || data.status}</span><br>
                            <span class="badge bg-${severityClass}">${severityTexts[data.severity] || data.severity}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.diagnosis_date') }}</label>
                    <p><i class="fas fa-calendar-check text-primary"></i> ${diagnosisDate}</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.last_followup') }}</label>
                    <p><i class="fas fa-clock text-info"></i> ${lastFollowup}</p>
                </div>
                <div class="col-md-6">
                    <label class="text-muted small">{{ __('translation.next_followup') }}</label>
                    <p><i class="fas fa-bell text-warning"></i> ${nextFollowup}</p>
                </div>
                ${data.treatment_plan ? `
                    <div class="col-12">
                        <label class="text-muted small">{{ __('translation.treatment_plan') }}</label>
                        <p class="p-2 rounded" style="background: rgba(0,0,0,0.03)">${data.treatment_plan}</p>
                    </div>
                ` : ''}
                ${data.notes ? `
                    <div class="col-12">
                        <label class="text-muted small">{{ __('translation.notes') }}</label>
                        <p class="text-muted">${data.notes}</p>
                    </div>
                ` : ''}

                {{-- Monitoring History Section --}}
                <div class="col-12 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-chart-line text-primary me-1"></i>{{ __('translation.monitoring_records') }}
                        </h6>
                        <button class="btn btn-sm btn-outline-primary" onclick="bootstrap.Modal.getInstance(document.getElementById('viewChronicDiseaseModal')).hide(); setTimeout(() => window.openMonitoring(currentViewedDisease), 300);">
                            <i class="fas fa-plus me-1"></i>{{ __('translation.add_monitoring') }}
                        </button>
                    </div>
                    ${data.monitoring_records && data.monitoring_records.length > 0 ? `
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="small">{{ __('translation.date') }}</th>
                                        <th class="small">{{ __('translation.parameter_name') }}</th>
                                        <th class="small">{{ __('translation.parameter_value') }}</th>
                                        <th class="small">{{ __('translation.status') }}</th>
                                        <th class="small">{{ __('translation.notes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${data.monitoring_records.map(record => {
                                        const monitoringStatusClass = record.status === 'controlled' ? 'success' : (record.status === 'critical' ? 'danger' : 'warning');
                                        const monitoringStatusText = {
                                            'controlled': '{{ __('translation.controlled') }}',
                                            'uncontrolled': '{{ __('translation.uncontrolled') }}',
                                            'critical': '{{ __('translation.critical') }}'
                                        };
                                        const paramLabels = {
                                            'blood_pressure': '{{ __('translation.blood_pressure') }}',
                                            'blood_sugar': '{{ __('translation.blood_sugar') }}',
                                            'heart_rate': '{{ __('translation.heart_rate') }}',
                                            'weight': '{{ __('translation.weight') }}',
                                            'temperature': '{{ __('translation.temperature') }}',
                                            'oxygen_saturation': '{{ __('translation.oxygen_saturation') }}',
                                            'cholesterol': '{{ __('translation.cholesterol') }}',
                                            'hba1c': 'HbA1c',
                                            'creatinine': '{{ __('translation.creatinine') }}'
                                        };
                                        const recordDate = new Date(record.monitoring_date).toLocaleDateString('{{ app()->getLocale() }}', { year: 'numeric', month: 'short', day: 'numeric' });
                                        return `<tr>
                                            <td class="small">${recordDate}</td>
                                            <td class="small">${paramLabels[record.parameter_name] || record.parameter_name}</td>
                                            <td class="small fw-bold">${record.parameter_value}${record.parameter_unit ? ' <span class="text-muted fw-normal">' + record.parameter_unit + '</span>' : ''}</td>
                                            <td class="small">${record.status ? '<span class="badge bg-' + monitoringStatusClass + '">' + (monitoringStatusText[record.status] || record.status) + '</span>' : '-'}</td>
                                            <td class="small text-muted">${record.notes || '-'}</td>
                                        </tr>`;
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>
                    ` : `
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-chart-line fa-2x mb-2 opacity-50"></i>
                            <p class="small mb-0">{{ __('translation.no_monitoring_records') }}</p>
                        </div>
                    `}
                </div>
            </div>
        `;

        dataContainer.innerHTML = content;

        // Clear previous errors
        clearFormErrors(modal);

        // Show modal
        showModal(modal);
    };

    // ===========================
    // Open Create (like specialties openCreate)
    // ===========================

    window.openChronicCreate = function() {
        const modal = document.getElementById('newChronicDiseaseModal');
        const form = modal.querySelector('.add-chronic-form');
        const modalTitle = modal.querySelector('.modal-title');
        const saveButton = modal.querySelector('#chronicDiseaseSaveBtn');

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
        form.action = '{{ route("patients.chronic-diseases.store", $patient) }}';

        // Update modal title and button
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="fas fa-heartbeat me-2"></i>{{ __("translation.add_chronic_disease") }}';
        }
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.common.save") }}';
        }

        // Clear previous errors
        clearFormErrors(modal);

        // Show modal
        showModal(modal);

        // Reset Choices.js selects after modal is visible
        modal.addEventListener('shown.bs.modal', async () => {
            await window.setChoicesSelectValue('diseaseType', '');
            await window.setChoicesSelectValue('diseaseSeverity', 'moderate');
            await window.setChoicesSelectValue('diseaseStatus', 'active');
        }, { once: true });
    };

    // ===========================
    // Open Monitoring (full model instead of ID)
    // ===========================

    window.openMonitoring = function(disease) {
        if (!disease) {
            SwalUtil.toast('{{ __("translation.chronic_diseases") }} {{ __("translation.common.not_found") }}', 'error');
            return;
        }

        const modal = document.getElementById('addMonitoringModal');
        const form = modal.querySelector('.add-monitoring-form');

        // Reset form
        if (form) form.reset();

        // Set form action dynamically with disease ID
        if (form) {
            form.action = `{{ url(app()->getLocale() . '/clinic/patients/' . $patient->file_number . '/chronic-diseases') }}/${disease.id}/monitoring`;
        }

        // Set disease info in modal
        document.getElementById('monitoringDiseaseId').value = disease.id;
        document.getElementById('monitoringDiseaseLabel').textContent = disease.chronic_disease_type
            ? disease.chronic_disease_type.name
            : (disease.name || '');

        // Reset custom parameter visibility
        document.getElementById('customParameterGroup').style.display = 'none';

        showModal(modal);
    };
</script>
