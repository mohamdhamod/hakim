<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Choices.js for Lab Test Type Select
    let labTestChoicesInstance = null;
    const labTestModalElement = document.getElementById('newLabTestModal');
    if (labTestModalElement) {
        labTestModalElement.addEventListener('shown.bs.modal', async function() {
            const labTestTypeSelect = document.getElementById('labTestType');
            if (labTestTypeSelect && !labTestChoicesInstance && window.loadChoices) {
                try {
                    const Choices = await window.loadChoices();
                    labTestChoicesInstance = new Choices(labTestTypeSelect, {
                        searchEnabled: true,
                        itemSelectText: '',
                        shouldSort: false,
                        allowHTML: true,
                        searchPlaceholderValue: '{{ __('translation.common.search') }}',
                        noResultsText: '{{ __('translation.common.no_results_found') }}',
                        noChoicesText: '{{ __('translation.common.no_choices') }}',
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

        // Reset modal on hide
        labTestModalElement.addEventListener('hidden.bs.modal', function() {
            if (labTestChoicesInstance) {
                labTestChoicesInstance.destroy();
                labTestChoicesInstance = null;
            }
            document.getElementById('labTestType').value = '';
            document.getElementById('testDate').value = '{{ date('Y-m-d') }}';
            document.getElementById('resultValue').value = '';
            document.getElementById('labTestNotes').value = '';
            document.getElementById('normalRangeInfo').style.display = 'none';
        });
    }

    // Initialize Choices.js for Vaccination Type Select
    let vaccinationChoicesInstance = null;
    const vaccinationModalElement = document.getElementById('newVaccinationModal');
    if (vaccinationModalElement) {
        vaccinationModalElement.addEventListener('shown.bs.modal', async function() {
            const vaccinationTypeSelect = document.getElementById('vaccinationType');
            if (vaccinationTypeSelect && !vaccinationChoicesInstance && window.loadChoices) {
                try {
                    const Choices = await window.loadChoices();
                    vaccinationChoicesInstance = new Choices(vaccinationTypeSelect, {
                        searchEnabled: true,
                        itemSelectText: '',
                        shouldSort: false,
                        allowHTML: true,
                        searchPlaceholderValue: '{{ __('translation.common.search') }}',
                        noResultsText: '{{ __('translation.common.no_results_found') }}',
                        noChoicesText: '{{ __('translation.common.no_choices') }}',
                    });
                } catch (error) {
                    console.error('Failed to initialize Choices.js for vaccination:', error);
                }
            }
        });

        // Reset modal on hide
        vaccinationModalElement.addEventListener('hidden.bs.modal', function() {
            if (vaccinationChoicesInstance) {
                vaccinationChoicesInstance.destroy();
                vaccinationChoicesInstance = null;
            }
            document.getElementById('vaccinationType').value = '';
            document.getElementById('vaccinationDate').value = '{{ date('Y-m-d') }}';
            document.getElementById('doseNumber').value = '1';
            document.getElementById('batchNumber').value = '';
            document.getElementById('manufacturer').value = '';
            document.getElementById('injectionSite').value = '';
            document.getElementById('nextDoseDate').value = '';
            document.getElementById('reactionNotes').value = '';
        });
    }
});

// Medical Features Functions
async function saveLabTest() {
    const typeId = document.getElementById('labTestType').value;
    const testDate = document.getElementById('testDate').value;
    const resultValue = document.getElementById('resultValue').value;
    const notes = document.getElementById('labTestNotes').value;
    
    if (!typeId || !testDate || !resultValue) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    // Loading state
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    try {
        const response = await fetch('{{ route("patients.lab-tests.store", $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                lab_test_type_id: typeId,
                test_date: testDate,
                result_value: resultValue,
                notes: notes
            })
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newLabTestModal')).hide();
            SwalUtil.toast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            document.querySelectorAll('#newLabTestModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#newLabTestModal .invalid-feedback').forEach(el => el.remove());
            Object.keys(data.errors).forEach(field => {
                const fieldMap = {'lab_test_type_id': 'labTestType', 'test_date': 'testDate', 'result_value': 'resultValue', 'notes': 'labTestNotes'};
                const input = document.getElementById(fieldMap[field] || field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = data.errors[field][0];
                    input.after(feedback);
                }
            });
        } else {
            SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
        }
    } catch (error) {
        console.error('Error saving lab test:', error);
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

async function saveVaccination() {
    const typeId = document.getElementById('vaccinationType').value;
    const vaccinationDate = document.getElementById('vaccinationDate').value;
    
    if (!typeId || !vaccinationDate) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    // Loading state
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    try {
        const response = await fetch('{{ route("patients.vaccinations.store", $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                vaccination_type_id: typeId,
                vaccination_date: vaccinationDate,
                dose_number: document.getElementById('doseNumber').value,
                batch_number: document.getElementById('batchNumber').value,
                manufacturer: document.getElementById('manufacturer').value,
                site: document.getElementById('injectionSite').value,
                next_dose_due_date: document.getElementById('nextDoseDate').value,
                reaction_notes: document.getElementById('reactionNotes').value,
                status: 'completed'
            })
        });
        
        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newVaccinationModal')).hide();
            SwalUtil.toast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            document.querySelectorAll('#newVaccinationModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#newVaccinationModal .invalid-feedback').forEach(el => el.remove());
            Object.keys(data.errors).forEach(field => {
                const fieldMap = {'vaccination_type_id': 'vaccinationType', 'vaccination_date': 'vaccinationDate', 'dose_number': 'doseNumber', 'batch_number': 'batchNumber', 'site': 'injectionSite', 'next_dose_due_date': 'nextDoseDate', 'reaction_notes': 'reactionNotes'};
                const input = document.getElementById(fieldMap[field] || field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = data.errors[field][0];
                    input.after(feedback);
                }
            });
        } else {
            SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
        }
    } catch (error) {
        console.error('Error saving vaccination:', error);
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

function viewLabTest(id) {
    const labTests = @json($patient->labTestResults);
    const labTest = labTests.find(test => test.id === id);
    
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
    const modal = new bootstrap.Modal(document.getElementById('viewLabTestModal'));
    modal.show();
}

function viewVaccination(id) {
    const vaccinations = @json($patient->vaccinationRecords);
    const vaccination = vaccinations.find(vac => vac.id === id);
    
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
    const modal = new bootstrap.Modal(document.getElementById('viewVaccinationModal'));
    modal.show();
}

// ===========================
// Chronic Diseases Functions
// ===========================

// Initialize Choices.js for Chronic Disease Type
let diseaseTypeChoicesInstance = null;
const diseaseModalElement = document.getElementById('newChronicDiseaseModal');
if (diseaseModalElement) {
    diseaseModalElement.addEventListener('shown.bs.modal', async function() {
        const diseaseTypeSelect = document.getElementById('diseaseType');
        if (diseaseTypeSelect && !diseaseTypeChoicesInstance && window.loadChoices) {
            try {
                const Choices = await window.loadChoices();
                diseaseTypeChoicesInstance = new Choices(diseaseTypeSelect, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    allowHTML: true,
                    searchPlaceholderValue: '{{ __('translation.common.search') }}',
                    noResultsText: '{{ __('translation.common.no_results_found') }}',
                    noChoicesText: '{{ __('translation.common.no_choices') }}',
                });
            } catch (error) {
                console.error('Failed to initialize Choices.js:', error);
            }
        }
    });

    // Reset modal on hide
    diseaseModalElement.addEventListener('hidden.bs.modal', function() {
        if (diseaseTypeChoicesInstance) {
            diseaseTypeChoicesInstance.destroy();
            diseaseTypeChoicesInstance = null;
        }
        // Reset form
        document.getElementById('diseaseType').value = '';
        document.getElementById('diagnosisDate').value = '{{ date('Y-m-d') }}';
        document.getElementById('diseaseSeverity').value = 'moderate';
        document.getElementById('diseaseTreatmentPlan').value = '';
        document.getElementById('nextFollowupDate').value = '';
        document.getElementById('diseaseStatus').value = 'active';
    });
}

async function saveChronicDisease() {
    const diseaseType = document.getElementById('diseaseType').value;
    const diagnosisDate = document.getElementById('diagnosisDate').value;
    const severity = document.getElementById('diseaseSeverity').value;
    const treatmentPlan = document.getElementById('diseaseTreatmentPlan').value;
    const nextFollowupDate = document.getElementById('nextFollowupDate').value;
    const status = document.getElementById('diseaseStatus').value;

    // Validation
    if (!diseaseType || !diagnosisDate) {
        SwalUtil.toast('{{ __('translation.please_fill_required_fields') }}', 'error');
        return;
    }

    // Loading state
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';

    try {
        const response = await fetch('{{ route('patients.chronic-diseases.store', $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                chronic_disease_type_id: diseaseType,
                diagnosis_date: diagnosisDate,
                severity: severity,
                treatment_plan: treatmentPlan,
                next_followup_date: nextFollowupDate || null,
                status: status
            })
        });

        const data = await response.json();
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('newChronicDiseaseModal')).hide();
            SwalUtil.toast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            document.querySelectorAll('#newChronicDiseaseModal .is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('#newChronicDiseaseModal .invalid-feedback').forEach(el => el.remove());
            Object.keys(data.errors).forEach(field => {
                const fieldMap = {
                    'chronic_disease_type_id': 'diseaseType',
                    'diagnosis_date': 'diagnosisDate',
                    'severity': 'diseaseSeverity',
                    'treatment_plan': 'diseaseTreatmentPlan',
                    'next_followup_date': 'nextFollowupDate',
                    'status': 'diseaseStatus'
                };
                const input = document.getElementById(fieldMap[field] || field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = data.errors[field][0];
                    input.after(feedback);
                }
            });
        } else {
            SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
        }
    } catch (error) {
        console.error('Error saving chronic disease:', error);
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

function viewDiseaseDetails(diseaseId) {
    const diseases = @json($patient->chronicDiseases);
    const disease = diseases.find(d => d.id === diseaseId);
    
    if (!disease) {
        SwalUtil.toast('{{ __("translation.chronic_diseases") }} {{ __("translation.common.not_found") }}', 'error');
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
    const diagnosisDate = new Date(disease.diagnosis_date).toLocaleDateString('{{ app()->getLocale() }}', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    const lastFollowup = disease.last_followup_date ? new Date(disease.last_followup_date).toLocaleDateString('{{ app()->getLocale() }}', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) : '-';
    
    const nextFollowup = disease.next_followup_date ? new Date(disease.next_followup_date).toLocaleDateString('{{ app()->getLocale() }}', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }) : '-';

    // Badge colors
    const severityClass = disease.severity === 'severe' ? 'danger' : (disease.severity === 'moderate' ? 'warning' : 'success');
    const statusClass = disease.status === 'active' ? 'danger' : (disease.status === 'in_remission' ? 'warning' : 'success');

    const content = `
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="fw-bold">${disease.chronic_disease_type.name}</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-tag"></i> ${disease.chronic_disease_type.category}
                            ${disease.chronic_disease_type.icd11_code ? `<span class="ms-2">| ICD-11: ${disease.chronic_disease_type.icd11_code}</span>` : ''}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-${statusClass} mb-1">${statusTexts[disease.status] || disease.status}</span><br>
                        <span class="badge bg-${severityClass}">${severityTexts[disease.severity] || disease.severity}</span>
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
            ${disease.treatment_plan ? `
                <div class="col-12">
                    <label class="text-muted small">{{ __('translation.treatment_plan') }}</label>
                    <p class="p-2 rounded" style="background: rgba(0,0,0,0.03)">${disease.treatment_plan}</p>
                </div>
            ` : ''}
            ${disease.notes ? `
                <div class="col-12">
                    <label class="text-muted small">{{ __('translation.notes') }}</label>
                    <p class="text-muted">${disease.notes}</p>
                </div>
            ` : ''}

            {{-- Monitoring History Section --}}
            <div class="col-12 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-chart-line text-primary me-1"></i>{{ __('translation.monitoring_records') }}
                    </h6>
                    <button class="btn btn-sm btn-outline-primary" onclick="bootstrap.Modal.getInstance(document.getElementById('viewChronicDiseaseModal')).hide(); setTimeout(() => addMonitoring(${disease.id}), 300);">
                        <i class="fas fa-plus me-1"></i>{{ __('translation.add_monitoring') }}
                    </button>
                </div>
                ${disease.monitoring_records && disease.monitoring_records.length > 0 ? `
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
                                ${disease.monitoring_records.map(record => {
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

    document.getElementById('chronicDiseaseDetailsContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('viewChronicDiseaseModal'));
    modal.show();
}

function addMonitoring(diseaseId) {
    const diseases = @json($patient->chronicDiseases);
    const disease = diseases.find(d => d.id === diseaseId);
    
    if (!disease) {
        SwalUtil.toast('{{ __("translation.chronic_diseases") }} {{ __("translation.common.not_found") }}', 'error');
        return;
    }
    
    // Set disease info in modal
    document.getElementById('monitoringDiseaseId').value = diseaseId;
    document.getElementById('monitoringDiseaseLabel').textContent = disease.chronic_disease_type.name;
    
    // Reset form
    document.getElementById('monitoringDate').value = '{{ date("Y-m-d") }}';
    document.getElementById('monitoringParameterName').value = '';
    document.getElementById('monitoringCustomParameter').value = '';
    document.getElementById('customParameterGroup').style.display = 'none';
    document.getElementById('monitoringParameterValue').value = '';
    document.getElementById('monitoringParameterUnit').value = '';
    document.getElementById('monitoringStatus').value = '';
    document.getElementById('monitoringNotes').value = '';
    
    const modal = new bootstrap.Modal(document.getElementById('addMonitoringModal'));
    modal.show();
}

// Toggle custom parameter name field
document.addEventListener('DOMContentLoaded', function() {
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
});

async function saveMonitoring() {
    const diseaseId = document.getElementById('monitoringDiseaseId').value;
    const monitoringDate = document.getElementById('monitoringDate').value;
    const paramSelect = document.getElementById('monitoringParameterName');
    let parameterName = paramSelect.value;
    const parameterValue = document.getElementById('monitoringParameterValue').value;
    const parameterUnit = document.getElementById('monitoringParameterUnit').value;
    const status = document.getElementById('monitoringStatus').value;
    const notes = document.getElementById('monitoringNotes').value;
    
    // If "other" selected, use custom parameter name
    if (parameterName === 'other') {
        parameterName = document.getElementById('monitoringCustomParameter').value;
    }
    
    // Validate required fields
    if (!monitoringDate || !parameterName || !parameterValue) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    const saveBtn = document.querySelector('#addMonitoringModal .btn-primary');
    const originalHTML = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    try {
        const url = `{{ url(app()->getLocale() . '/clinic/patients/' . $patient->file_number . '/chronic-diseases') }}/${diseaseId}/monitoring`;
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                monitoring_date: monitoringDate,
                parameter_name: parameterName,
                parameter_value: parameterValue,
                parameter_unit: parameterUnit || null,
                status: status || null,
                notes: notes || null
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('addMonitoringModal'));
            modal.hide();
            
            SwalUtil.toast(data.message || '{{ __("translation.monitoring_record_added_successfully") }}', 'success');
            
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
        }
    } catch (error) {
        console.error('Error saving monitoring:', error);
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

// ==================== Growth Measurements Functions ====================

async function saveGrowthMeasurement() {
    const saveBtn = event.target;
    const originalHTML = saveBtn.innerHTML;
    
    // Get form values
    const measurementDate = document.getElementById('measurementDate').value;
    const weight = document.getElementById('growthWeight').value;
    const height = document.getElementById('growthHeight').value;
    const headCircumference = document.getElementById('headCircumference').value;
    const notes = document.getElementById('growthNotes').value;
    
    // Validate required fields
    if (!measurementDate || !weight || !height) {
        SwalUtil.toast('{{ __("translation.please_fill_required_fields") }}', 'error');
        return;
    }
    
    // Disable button and show loading
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.saving") }}';
    
    try {
        const response = await fetch('{{ route("patients.growth-charts.store", $patient) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                measurement_date: measurementDate,
                weight_kg: weight,
                height_cm: height,
                head_circumference_cm: headCircumference || null,
                notes: notes
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('newGrowthMeasurementModal'));
            modal.hide();
            
            // Clear form
            document.getElementById('measurementDate').value = '{{ date("Y-m-d") }}';
            document.getElementById('growthWeight').value = '';
            document.getElementById('growthHeight').value = '';
            document.getElementById('headCircumference').value = '';
            document.getElementById('growthNotes').value = '';
            
            SwalUtil.toast(data.message || '{{ __("translation.measurement_added_successfully") }}', 'success');
            
            // Reload page to update chart and table
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
        }
    } catch (error) {
        console.error('Error saving growth measurement:', error);
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalHTML;
    }
}

function viewGrowthMeasurement(id) {
    const measurements = @json($patient->growthMeasurements);
    const measurement = measurements.find(m => m.id === id);
    
    if (!measurement) {
        SwalUtil.toast('{{ __("translation.measurement") }} {{ __("translation.common.not_found") }}', 'error');
        return;
    }
    
    // Format date
    const measurementDate = new Date(measurement.measurement_date).toLocaleDateString('{{ app()->getLocale() }}', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    
    // Calculate age display
    let ageDisplay = '-';
    if (measurement.age_months) {
        if (measurement.age_months < 12) {
            ageDisplay = `${measurement.age_months} {{ __('translation.months') }}`;
        } else {
            const years = Math.floor(measurement.age_months / 12);
            const months = measurement.age_months % 12;
            ageDisplay = `${years} {{ __('translation.years') }}`;
            if (months > 0) {
                ageDisplay += ` ${months} {{ __('translation.months') }}`;
            }
        }
    }
    
    // Interpretation badge
    let interpretationBadge = '';
    if (measurement.interpretation) {
        let badgeClass = 'success';
        let badgeText = measurement.interpretation;
        
        if (measurement.interpretation === 'attention_needed') {
            badgeClass = 'danger';
            badgeText = '{{ __("translation.attention_needed") }}';
        } else if (measurement.interpretation === 'monitor') {
            badgeClass = 'warning';
            badgeText = '{{ __("translation.monitor") }}';
        } else if (measurement.interpretation === 'normal') {
            badgeClass = 'success';
            badgeText = '{{ __("translation.normal") }}';
        }
        
        interpretationBadge = `
            <div class="col-12">
                <div class="alert alert-${badgeClass} mb-0 py-2">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>{{ __('translation.interpretation') }}:</strong> ${badgeText}
                </div>
            </div>
        `;
    }
    
    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.measurement_date') }}</label>
                <p class="fw-bold"><i class="fas fa-calendar text-primary me-2"></i>${measurementDate}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">{{ __('translation.age') }}</label>
                <p class="fw-bold"><i class="fas fa-birthday-cake text-info me-2"></i>${ageDisplay}</p>
            </div>
            <div class="col-12"><hr class="my-2"></div>
            <div class="col-md-6">
                <div class="text-center p-3 rounded" style="background: rgba(13, 110, 253, 0.1);">
                    <div class="small text-muted mb-1">{{ __('translation.weight') }}</div>
                    <h4 class="mb-0 text-primary">${measurement.weight_kg} <small>kg</small></h4>
                    ${measurement.weight_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(measurement.weight_percentile)}%</small>` : ''}
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center p-3 rounded" style="background: rgba(13, 202, 240, 0.1);">
                    <div class="small text-muted mb-1">{{ __('translation.height') }}</div>
                    <h4 class="mb-0 text-info">${measurement.height_cm} <small>cm</small></h4>
                    ${measurement.height_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(measurement.height_percentile)}%</small>` : ''}
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center p-3 rounded" style="background: rgba(102, 16, 242, 0.1);">
                    <div class="small text-muted mb-1">{{ __('translation.bmi') }}</div>
                    <h4 class="mb-0" style="color: #6610f2;">${measurement.bmi ? Math.round(measurement.bmi * 10) / 10 : '-'}</h4>
                    ${measurement.bmi_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(measurement.bmi_percentile)}%</small>` : ''}
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-center p-3 rounded" style="background: rgba(220, 53, 69, 0.1);">
                    <div class="small text-muted mb-1">{{ __('translation.head_circumference') }}</div>
                    <h4 class="mb-0 text-danger">${measurement.head_circumference_cm || '-'} ${measurement.head_circumference_cm ? '<small>cm</small>' : ''}</h4>
                    ${measurement.head_circumference_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(measurement.head_circumference_percentile)}%</small>` : ''}
                </div>
            </div>
            ${interpretationBadge}
            ${measurement.notes ? `
                <div class="col-12">
                    <label class="text-muted small">{{ __('translation.notes') }}</label>
                    <p class="text-muted mb-0">${measurement.notes}</p>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('growthMeasurementDetailsContent').innerHTML = content;
    const modal = new bootstrap.Modal(document.getElementById('viewGrowthMeasurementModal'));
    modal.show();
}

// ==================== Inline Growth Charts ====================

@if($patient->date_of_birth && $patient->age < 18 && $patient->growthMeasurements && $patient->growthMeasurements->count() >= 2)
(function() {
    let chartsInitialized = false;
    const growthChartsTab = document.getElementById('growth-charts-tab');
    
    if (!growthChartsTab) return;
    
    growthChartsTab.addEventListener('shown.bs.tab', function() {
        if (chartsInitialized) return;
        chartsInitialized = true;
        initGrowthCharts();
    });

    function initGrowthCharts() {
        // Load Chart.js dynamically if not already loaded
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
            script.onload = () => buildAllCharts();
            document.head.appendChild(script);
        } else {
            buildAllCharts();
        }
    }

    function buildAllCharts() {
        const measurements = @json($patient->growthMeasurements->sortBy('age_months')->values());
        const gender = '{{ $patient->gender }}';
        const isMale = gender === 'male';

        // WHO approximate reference data (LMS simplified for key age points 0-216 months)
        // These generate the 3rd, 15th, 50th, 85th, 97th percentile curves
        const whoData = getWhoReferenceData(isMale);

        // Build charts
        buildChart('inlineWeightChart', measurements, 'weight_kg', '{{ __("translation.weight") }}', 'kg',
            'rgb(54, 162, 235)', 'rgba(54, 162, 235, 0.1)', whoData.weight);

        buildChart('inlineHeightChart', measurements, 'height_cm', '{{ __("translation.height") }}', 'cm',
            'rgb(75, 192, 192)', 'rgba(75, 192, 192, 0.1)', whoData.height);

        buildChart('inlineBmiChart', measurements, 'bmi', '{{ __("translation.bmi") }}', '',
            'rgb(255, 159, 64)', 'rgba(255, 159, 64, 0.1)', whoData.bmi);

        @if($patient->age < 6)
        buildChart('inlineHeadChart', measurements, 'head_circumference_cm', '{{ __("translation.head_circumference") }}', 'cm',
            'rgb(153, 102, 255)', 'rgba(153, 102, 255, 0.1)', whoData.head);
        @endif
    }

    function buildChart(canvasId, measurements, field, label, unit, borderColor, bgColor, whoRef) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        // Filter measurements that have this field
        const validData = measurements.filter(m => m[field] !== null && m[field] !== undefined);
        if (validData.length === 0) return;

        // Get age range for full WHO curve display
        const patientAges = validData.map(m => m.age_months || 0);
        const minPatientAge = Math.min(...patientAges);
        const maxPatientAge = Math.max(...patientAges);
        
        // Extend range for context (show more of the curve)
        const chartMinAge = Math.max(0, minPatientAge - 3);
        const chartMaxAge = Math.min(whoRef && whoRef.length > 0 ? whoRef[whoRef.length - 1].age : 216, maxPatientAge + 6);

        // Generate WHO percentile data points for the full age range
        const whoAgePoints = [];
        for (let age = chartMinAge; age <= chartMaxAge; age += (chartMaxAge - chartMinAge > 60 ? 3 : 1)) {
            whoAgePoints.push(age);
        }
        // Ensure patient ages are included
        patientAges.forEach(age => {
            if (!whoAgePoints.includes(age)) whoAgePoints.push(age);
        });
        whoAgePoints.sort((a, b) => a - b);

        // Format age labels
        const formatAge = (months) => {
            if (months < 24) return months + '{{ __("translation.months_abbr") }}';
            return Math.floor(months / 12) + '{{ __("translation.years_abbr") }}';
        };

        const labels = whoAgePoints.map(age => formatAge(age));

        // Build WHO percentile datasets with colored fill bands
        const datasets = [];
        
        if (whoRef && whoRef.length > 0) {
            // Get percentile values at each age point
            const p3 = whoAgePoints.map(a => interpolateWho(whoRef, a, 'p3'));
            const p15 = whoAgePoints.map(a => interpolateWho(whoRef, a, 'p15'));
            const p50 = whoAgePoints.map(a => interpolateWho(whoRef, a, 'p50'));
            const p85 = whoAgePoints.map(a => interpolateWho(whoRef, a, 'p85'));
            const p97 = whoAgePoints.map(a => interpolateWho(whoRef, a, 'p97'));

            // Zone: Below 3rd percentile (danger - fill from bottom to p3)
            datasets.push({
                label: '< 3%',
                data: p3,
                borderColor: 'rgba(220, 53, 69, 0.8)',
                backgroundColor: 'rgba(220, 53, 69, 0.15)',
                borderWidth: 1.5,
                pointRadius: 0,
                fill: 'origin',
                tension: 0.4,
                order: 10
            });

            // Zone: 3-15% (caution - yellow band)
            datasets.push({
                label: '3-15%',
                data: p15,
                borderColor: 'rgba(255, 193, 7, 0.8)',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                borderWidth: 1.5,
                pointRadius: 0,
                fill: '-1',
                tension: 0.4,
                order: 9
            });

            // Zone: 15-50% (normal lower - light green)
            datasets.push({
                label: '15-50%',
                data: p50,
                borderColor: 'rgba(40, 167, 69, 0.9)',
                backgroundColor: 'rgba(40, 167, 69, 0.15)',
                borderWidth: 2,
                pointRadius: 0,
                fill: '-1',
                tension: 0.4,
                order: 8
            });

            // Zone: 50-85% (normal upper - light green)
            datasets.push({
                label: '50-85%',
                data: p85,
                borderColor: 'rgba(255, 193, 7, 0.8)',
                backgroundColor: 'rgba(40, 167, 69, 0.15)',
                borderWidth: 1.5,
                pointRadius: 0,
                fill: '-1',
                tension: 0.4,
                order: 7
            });

            // Zone: 85-97% (caution - yellow band)
            datasets.push({
                label: '85-97%',
                data: p97,
                borderColor: 'rgba(220, 53, 69, 0.8)',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                borderWidth: 1.5,
                pointRadius: 0,
                fill: '-1',
                tension: 0.4,
                order: 6
            });
        }

        // Prepare patient data - map to WHO age points
        const patientDataPoints = whoAgePoints.map(age => {
            const measurement = validData.find(m => m.age_months === age);
            return measurement ? parseFloat(measurement[field]) : null;
        });

        // Patient data line (on top of everything)
        datasets.push({
            label: label + (unit ? ` (${unit})` : ''),
            data: patientDataPoints,
            borderColor: '#1a237e',
            backgroundColor: '#1a237e',
            borderWidth: 3,
            pointRadius: 7,
            pointHoverRadius: 10,
            pointBackgroundColor: '#1a237e',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointStyle: 'circle',
            fill: false,
            tension: 0.3,
            spanGaps: true,
            order: 0
        });

        new Chart(canvas, {
            type: 'line',
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.98)',
                        titleColor: '#1a237e',
                        titleFont: { size: 13, weight: 'bold' },
                        bodyColor: '#333',
                        bodyFont: { size: 12 },
                        borderColor: '#e0e0e0',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        boxPadding: 4,
                        filter: function(item) {
                            // Only show patient data and key percentiles
                            return item.datasetIndex === datasets.length - 1 || 
                                   item.dataset.label === '50%' ||
                                   item.dataset.label === '< 3%' ||
                                   item.dataset.label === '85-97%';
                        },
                        callbacks: {
                            title: function(items) {
                                const age = whoAgePoints[items[0].dataIndex];
                                return '{{ __("translation.age") }}: ' + formatAge(age);
                            },
                            label: function(ctx) {
                                const val = ctx.parsed.y;
                                if (val === null || val === undefined) return null;
                                
                                // For patient data, show interpretation
                                if (ctx.datasetIndex === datasets.length - 1) {
                                    const age = whoAgePoints[ctx.dataIndex];
                                    let interpretation = '';
                                    if (whoRef) {
                                        const p3 = interpolateWho(whoRef, age, 'p3');
                                        const p15 = interpolateWho(whoRef, age, 'p15');
                                        const p85 = interpolateWho(whoRef, age, 'p85');
                                        const p97 = interpolateWho(whoRef, age, 'p97');
                                        
                                        if (val < p3) interpretation = ' ⚠️ {{ __("translation.growth.below_3rd") }}';
                                        else if (val < p15) interpretation = ' ⚡ {{ __("translation.growth.below_15th") }}';
                                        else if (val > p97) interpretation = ' ⚠️ {{ __("translation.growth.above_97th") }}';
                                        else if (val > p85) interpretation = ' ⚡ {{ __("translation.growth.above_85th") }}';
                                        else interpretation = ' ✓ {{ __("translation.growth.normal_range") }}';
                                    }
                                    return `📊 ${val.toFixed(1)} ${unit}${interpretation}`;
                                }
                                return null;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { 
                            color: 'rgba(0,0,0,0.06)',
                            drawBorder: false
                        },
                        ticks: { 
                            font: { size: 11 },
                            color: '#666',
                            padding: 8
                        },
                        title: {
                            display: true,
                            text: unit ? `${label} (${unit})` : label,
                            font: { size: 11, weight: 'bold' },
                            color: '#666'
                        }
                    },
                    x: {
                        grid: { 
                            display: false 
                        },
                        ticks: { 
                            font: { size: 10 }, 
                            maxRotation: 45,
                            color: '#666'
                        },
                        title: {
                            display: true,
                            text: '{{ __("translation.age") }}',
                            font: { size: 11, weight: 'bold' },
                            color: '#666'
                        }
                    }
                }
            }
        });
    }

    /**
     * Interpolate WHO reference value at a given age
     */
    function interpolateWho(whoRef, ageMonths, percentile) {
        if (!whoRef || whoRef.length === 0) return null;
        
        // Find surrounding reference points
        let lower = whoRef[0];
        let upper = whoRef[whoRef.length - 1];
        
        for (let i = 0; i < whoRef.length - 1; i++) {
            if (whoRef[i].age <= ageMonths && whoRef[i+1].age >= ageMonths) {
                lower = whoRef[i];
                upper = whoRef[i+1];
                break;
            }
        }
        
        if (ageMonths <= lower.age) return lower[percentile];
        if (ageMonths >= upper.age) return upper[percentile];
        
        // Linear interpolation
        const ratio = (ageMonths - lower.age) / (upper.age - lower.age);
        return lower[percentile] + ratio * (upper[percentile] - lower[percentile]);
    }

    /**
     * WHO approximate reference data for growth charts
     * Based on WHO Child Growth Standards (simplified key points)
     */
    function getWhoReferenceData(isMale) {
        return {
            weight: isMale ? [
                // Boys weight-for-age (kg): age, p3, p15, p50, p85, p97
                {age:0, p3:2.5, p15:2.9, p50:3.3, p85:3.9, p97:4.4},
                {age:3, p3:4.7, p15:5.4, p50:6.4, p85:7.2, p97:7.8},
                {age:6, p3:6.2, p15:7.1, p50:7.9, p85:8.8, p97:9.5},
                {age:9, p3:7.2, p15:8.1, p50:8.9, p85:9.9, p97:10.5},
                {age:12, p3:7.8, p15:8.8, p50:9.6, p85:10.8, p97:11.5},
                {age:18, p3:8.8, p15:9.9, p50:10.9, p85:12.1, p97:13.0},
                {age:24, p3:9.7, p15:10.8, p50:12.2, p85:13.6, p97:14.7},
                {age:36, p3:11.3, p15:12.5, p50:14.3, p85:16.2, p97:17.7},
                {age:48, p3:12.7, p15:14.2, p50:16.3, p85:18.8, p97:20.7},
                {age:60, p3:14.1, p15:15.9, p50:18.3, p85:21.2, p97:23.6},
                {age:72, p3:15.5, p15:17.5, p50:20.5, p85:24.0, p97:27.1},
                {age:96, p3:18.5, p15:21.0, p50:25.0, p85:30.0, p97:34.5},
                {age:120, p3:22.0, p15:25.0, p50:31.0, p85:38.0, p97:44.0},
            ] : [
                // Girls weight-for-age (kg)
                {age:0, p3:2.4, p15:2.8, p50:3.2, p85:3.7, p97:4.2},
                {age:3, p3:4.4, p15:5.0, p50:5.8, p85:6.6, p97:7.2},
                {age:6, p3:5.8, p15:6.5, p50:7.3, p85:8.2, p97:8.8},
                {age:9, p3:6.7, p15:7.5, p50:8.2, p85:9.3, p97:10.0},
                {age:12, p3:7.1, p15:8.1, p50:8.9, p85:10.1, p97:10.9},
                {age:18, p3:8.1, p15:9.2, p50:10.2, p85:11.6, p97:12.6},
                {age:24, p3:9.0, p15:10.2, p50:11.5, p85:13.1, p97:14.3},
                {age:36, p3:10.6, p15:12.0, p50:13.9, p85:16.0, p97:17.8},
                {age:48, p3:12.1, p15:13.8, p50:16.1, p85:18.8, p97:21.1},
                {age:60, p3:13.5, p15:15.5, p50:18.2, p85:21.5, p97:24.5},
                {age:72, p3:15.0, p15:17.2, p50:20.2, p85:24.2, p97:27.8},
                {age:96, p3:18.0, p15:20.5, p50:25.0, p85:30.5, p97:36.0},
                {age:120, p3:21.5, p15:24.5, p50:31.0, p85:39.0, p97:46.0},
            ],
            height: isMale ? [
                // Boys height/length-for-age (cm)
                {age:0, p3:46.3, p15:48.0, p50:49.9, p85:51.8, p97:53.4},
                {age:3, p3:57.6, p15:59.5, p50:61.4, p85:63.4, p97:65.0},
                {age:6, p3:63.6, p15:65.4, p50:67.6, p85:69.8, p97:71.6},
                {age:9, p3:68.0, p15:69.7, p50:72.0, p85:74.2, p97:76.2},
                {age:12, p3:71.0, p15:73.0, p50:75.7, p85:78.1, p97:80.2},
                {age:18, p3:76.9, p15:79.2, p50:82.3, p85:85.0, p97:87.3},
                {age:24, p3:81.7, p15:84.1, p50:87.8, p85:91.0, p97:93.4},
                {age:36, p3:89.0, p15:91.9, p50:96.1, p85:99.8, p97:102.7},
                {age:48, p3:95.4, p15:98.9, p50:103.3, p85:107.3, p97:110.7},
                {age:60, p3:101.2, p15:105.0, p50:110.0, p85:114.5, p97:118.0},
                {age:72, p3:106.5, p15:110.5, p50:116.0, p85:121.0, p97:125.0},
                {age:96, p3:116.0, p15:120.5, p50:127.0, p85:133.0, p97:137.5},
                {age:120, p3:124.5, p15:129.5, p50:137.0, p85:144.0, p97:149.5},
                {age:144, p3:133.5, p15:139.0, p50:149.0, p85:158.0, p97:163.5},
                {age:168, p3:148.0, p15:155.0, p50:163.0, p85:172.0, p97:177.0},
                {age:192, p3:159.0, p15:164.0, p50:172.0, p85:179.0, p97:184.0},
                {age:216, p3:162.0, p15:167.0, p50:175.0, p85:182.0, p97:186.0},
            ] : [
                // Girls height/length-for-age (cm)
                {age:0, p3:45.6, p15:47.2, p50:49.1, p85:51.0, p97:52.7},
                {age:3, p3:56.2, p15:58.0, p50:59.8, p85:61.8, p97:63.5},
                {age:6, p3:61.8, p15:63.5, p50:65.7, p85:68.0, p97:69.8},
                {age:9, p3:66.0, p15:67.7, p50:70.1, p85:72.6, p97:74.5},
                {age:12, p3:69.2, p15:71.4, p50:74.0, p85:76.6, p97:78.8},
                {age:18, p3:75.0, p15:77.5, p50:80.7, p85:83.5, p97:86.0},
                {age:24, p3:80.0, p15:82.5, p50:86.4, p85:89.6, p97:92.2},
                {age:36, p3:87.5, p15:90.6, p50:95.1, p85:99.0, p97:102.0},
                {age:48, p3:94.0, p15:97.5, p50:102.7, p85:107.2, p97:110.4},
                {age:60, p3:100.0, p15:104.0, p50:109.4, p85:114.5, p97:118.0},
                {age:72, p3:105.5, p15:109.5, p50:115.5, p85:121.0, p97:125.0},
                {age:96, p3:115.0, p15:119.5, p50:127.0, p85:133.5, p97:138.0},
                {age:120, p3:123.5, p15:128.5, p50:137.0, p85:145.0, p97:150.0},
                {age:144, p3:135.0, p15:141.0, p50:151.0, p85:160.0, p97:165.5},
                {age:168, p3:147.0, p15:152.0, p50:159.0, p85:165.0, p97:169.5},
                {age:192, p3:150.0, p15:155.0, p50:162.0, p85:168.0, p97:172.0},
                {age:216, p3:150.5, p15:155.5, p50:162.5, p85:168.5, p97:172.5},
            ],
            bmi: isMale ? [
                // Boys BMI-for-age
                {age:0, p3:11.0, p15:12.2, p50:13.4, p85:14.8, p97:16.2},
                {age:3, p3:14.0, p15:15.2, p50:16.2, p85:17.5, p97:18.5},
                {age:6, p3:14.5, p15:15.5, p50:16.8, p85:18.0, p97:19.0},
                {age:12, p3:14.0, p15:15.2, p50:16.5, p85:17.8, p97:18.8},
                {age:24, p3:13.5, p15:14.8, p50:16.0, p85:17.5, p97:18.5},
                {age:36, p3:13.2, p15:14.3, p50:15.5, p85:16.8, p97:17.8},
                {age:48, p3:13.0, p15:14.0, p50:15.3, p85:16.6, p97:17.6},
                {age:60, p3:12.8, p15:13.8, p50:15.1, p85:16.6, p97:17.8},
                {age:72, p3:12.8, p15:13.7, p50:15.3, p85:17.0, p97:18.5},
                {age:96, p3:13.0, p15:14.0, p50:15.7, p85:17.8, p97:19.8},
                {age:120, p3:13.4, p15:14.5, p50:16.5, p85:19.2, p97:21.5},
                {age:144, p3:14.0, p15:15.2, p50:17.5, p85:20.8, p97:23.5},
                {age:168, p3:15.0, p15:16.5, p50:19.0, p85:22.5, p97:25.5},
                {age:192, p3:16.2, p15:17.8, p50:20.5, p85:24.0, p97:27.5},
                {age:216, p3:17.0, p15:18.5, p50:21.5, p85:25.0, p97:28.5},
            ] : [
                // Girls BMI-for-age
                {age:0, p3:10.8, p15:12.0, p50:13.3, p85:14.6, p97:16.0},
                {age:3, p3:13.5, p15:14.8, p50:15.8, p85:17.2, p97:18.2},
                {age:6, p3:14.0, p15:15.2, p50:16.4, p85:17.8, p97:18.8},
                {age:12, p3:13.8, p15:15.0, p50:16.4, p85:17.8, p97:18.8},
                {age:24, p3:13.3, p15:14.5, p50:15.7, p85:17.3, p97:18.5},
                {age:36, p3:13.0, p15:14.0, p50:15.3, p85:16.8, p97:18.0},
                {age:48, p3:12.7, p15:13.7, p50:15.0, p85:16.5, p97:17.8},
                {age:60, p3:12.5, p15:13.5, p50:14.8, p85:16.5, p97:18.0},
                {age:72, p3:12.5, p15:13.5, p50:15.0, p85:17.0, p97:18.8},
                {age:96, p3:12.8, p15:13.8, p50:15.5, p85:17.8, p97:20.2},
                {age:120, p3:13.2, p15:14.3, p50:16.5, p85:19.5, p97:22.5},
                {age:144, p3:14.0, p15:15.2, p50:17.8, p85:21.2, p97:24.5},
                {age:168, p3:15.0, p15:16.5, p50:19.5, p85:23.0, p97:26.5},
                {age:192, p3:16.0, p15:17.5, p50:20.5, p85:24.0, p97:27.5},
                {age:216, p3:16.5, p15:18.0, p50:21.0, p85:24.5, p97:28.0},
            ],
            head: isMale ? [
                // Boys head circumference (cm) 0-60 months
                {age:0, p3:32.1, p15:33.1, p50:34.5, p85:35.8, p97:36.9},
                {age:3, p3:38.0, p15:39.2, p50:40.5, p85:41.9, p97:42.9},
                {age:6, p3:40.8, p15:42.0, p50:43.3, p85:44.6, p97:45.6},
                {age:9, p3:42.6, p15:43.7, p50:45.0, p85:46.3, p97:47.4},
                {age:12, p3:43.6, p15:44.7, p50:46.1, p85:47.4, p97:48.5},
                {age:18, p3:44.9, p15:46.0, p50:47.4, p85:48.8, p97:49.9},
                {age:24, p3:45.8, p15:46.9, p50:48.3, p85:49.7, p97:50.8},
                {age:36, p3:46.8, p15:47.8, p50:49.5, p85:50.8, p97:51.8},
                {age:48, p3:47.4, p15:48.5, p50:50.0, p85:51.5, p97:52.5},
                {age:60, p3:47.8, p15:48.8, p50:50.4, p85:51.8, p97:52.8},
            ] : [
                // Girls head circumference (cm) 0-60 months
                {age:0, p3:31.5, p15:32.4, p50:33.9, p85:35.1, p97:36.2},
                {age:3, p3:37.2, p15:38.3, p50:39.5, p85:40.8, p97:41.9},
                {age:6, p3:39.7, p15:40.8, p50:42.2, p85:43.4, p97:44.5},
                {age:9, p3:41.2, p15:42.4, p50:43.7, p85:45.0, p97:46.0},
                {age:12, p3:42.3, p15:43.5, p50:44.9, p85:46.1, p97:47.2},
                {age:18, p3:43.5, p15:44.7, p50:46.2, p85:47.6, p97:48.7},
                {age:24, p3:44.4, p15:45.5, p50:47.2, p85:48.6, p97:49.7},
                {age:36, p3:45.5, p15:46.6, p50:48.1, p85:49.7, p97:50.8},
                {age:48, p3:46.0, p15:47.1, p50:48.7, p85:50.2, p97:51.3},
                {age:60, p3:46.4, p15:47.5, p50:49.1, p85:50.6, p97:51.7},
            ]
        };
    }
})();
@endif

// ========================================
// Examination Quick View Functions
// ========================================
function viewExamination(id) {
    const examinations = @json($patient->examinations);
    const exam = examinations.find(e => e.id === id);
    
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
    

     document.getElementById('examinationFullLink').href = '{{ route("clinic.examinations.index") }}/' + exam.id;
    document.getElementById('examinationPrintLink').href = '{{ route("clinic.examinations.index") }}/' + exam.id + '/print';
    
    const modal = new bootstrap.Modal(document.getElementById('viewExaminationModal'));
    modal.show();
}
</script>
