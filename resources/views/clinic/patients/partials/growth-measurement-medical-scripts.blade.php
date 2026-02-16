<script>
    // ===========================
    // Growth Measurements Scripts
    // (Following specialties pattern)
    // ===========================

    document.addEventListener('DOMContentLoaded', function() {
        // Edit button handler
        document.querySelectorAll('.edit-btn[data-type="growth"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const json = this.getAttribute('data-model');
                const data = JSON.parse(json);
                window.openGrowthEdit(data);
            });
        });

        // View button handler
        document.querySelectorAll('.view-btn[data-type="growth"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const json = this.getAttribute('data-model');
                const data = JSON.parse(json);
                window.openGrowthView(data);
            });
        });

        // Delete button handler
        document.querySelectorAll('.delete-btn[data-type="growth"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const json = this.getAttribute('data-model');
                const data = JSON.parse(json);
                const deleteUrl = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/growth-charts") }}/${data.id}`;
                confirmDelete(data, deleteUrl, window.i18n, data.measurement_date || '');
            });
        });

        // Handle growth measurement form submission
        const addGrowthForm = document.querySelector('.add-growth-form');
        if (addGrowthForm && !addGrowthForm.__handleSubmitBound) {
            addGrowthForm.__handleSubmitBound = true;
            addGrowthForm.addEventListener('submit', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                window.handleFormSubmit(e, this);
            });
        }
    });

    // ===========================
    // Open Edit (like specialties openEdit)
    // ===========================

    window.openGrowthEdit = function(data) {
        const modal = document.getElementById('newGrowthMeasurementModal');
        const form = modal.querySelector('.add-growth-form');
        const modalTitle = modal.querySelector('.modal-title');
        const saveButton = modal.querySelector('#growthMeasurementSaveBtn');

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
        form.action = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/growth-charts") }}/${data.id}`;

        // Update modal title and button
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="fas fa-edit me-2"></i>{{ __("translation.edit_measurement") }}';
        }
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.general.update") }}';
        }

        // Clear previous errors
        clearFormErrors(modal);

        // Normalize dates (ISO → YYYY-MM-DD for date inputs)
        const normalized = { ...data };
        ['measurement_date'].forEach(key => {
            if (normalized[key]) normalized[key] = String(normalized[key]).split('T')[0];
        });

        // Fill form with data (matches fields by name attribute — like specialties)
        fillForm(modal, normalized);

        // Show modal
        showModal(modal);
    };

    // ===========================
    // Open View (like specialties openView)
    // ===========================

    window.openGrowthView = function(data) {
        const modal = document.getElementById('viewGrowthMeasurementModal');
        const dataContainer = modal.querySelector('#growthMeasurementDetailsContent');

        if (!modal || !dataContainer) {
            console.error('View modal or data container not found');
            return;
        }

        // Format date
        const measurementDate = new Date(data.measurement_date).toLocaleDateString('{{ app()->getLocale() }}', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        // Calculate age display
        let ageDisplay = '-';
        if (data.age_months) {
            if (data.age_months < 12) {
                ageDisplay = `${data.age_months} {{ __('translation.months') }}`;
            } else {
                const years = Math.floor(data.age_months / 12);
                const months = data.age_months % 12;
                ageDisplay = `${years} {{ __('translation.years') }}`;
                if (months > 0) {
                    ageDisplay += ` ${months} {{ __('translation.months') }}`;
                }
            }
        }

        // Interpretation badge
        let interpretationBadge = '';
        if (data.interpretation) {
            let badgeClass = 'success';
            let badgeText = data.interpretation;

            if (data.interpretation === 'attention_needed') {
                badgeClass = 'danger';
                badgeText = '{{ __("translation.attention_needed") }}';
            } else if (data.interpretation === 'monitor') {
                badgeClass = 'warning';
                badgeText = '{{ __("translation.monitor") }}';
            } else if (data.interpretation === 'normal') {
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
                        <h4 class="mb-0 text-primary">${data.weight_kg} <small>kg</small></h4>
                        ${data.weight_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(data.weight_percentile)}%</small>` : ''}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center p-3 rounded" style="background: rgba(13, 202, 240, 0.1);">
                        <div class="small text-muted mb-1">{{ __('translation.height') }}</div>
                        <h4 class="mb-0 text-info">${data.height_cm} <small>cm</small></h4>
                        ${data.height_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(data.height_percentile)}%</small>` : ''}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center p-3 rounded" style="background: rgba(102, 16, 242, 0.1);">
                        <div class="small text-muted mb-1">{{ __('translation.bmi') }}</div>
                        <h4 class="mb-0" style="color: #6610f2;">${data.bmi ? Math.round(data.bmi * 10) / 10 : '-'}</h4>
                        ${data.bmi_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(data.bmi_percentile)}%</small>` : ''}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center p-3 rounded" style="background: rgba(220, 53, 69, 0.1);">
                        <div class="small text-muted mb-1">{{ __('translation.head_circumference') }}</div>
                        <h4 class="mb-0 text-danger">${data.head_circumference_cm || '-'} ${data.head_circumference_cm ? '<small>cm</small>' : ''}</h4>
                        ${data.head_circumference_percentile ? `<small class="text-muted">{{ __('translation.percentile') }}: ${Math.round(data.head_circumference_percentile)}%</small>` : ''}
                    </div>
                </div>
                ${interpretationBadge}
                ${data.notes ? `
                    <div class="col-12">
                        <label class="text-muted small">{{ __('translation.notes') }}</label>
                        <p class="text-muted mb-0">${data.notes}</p>
                    </div>
                ` : ''}
            </div>
        `;

        dataContainer.innerHTML = content;

        // Show modal
        showModal(modal);
    };

    // ===========================
    // Open Create (like specialties openCreate)
    // ===========================

    window.openGrowthCreate = function() {
        const modal = document.getElementById('newGrowthMeasurementModal');
        const form = modal.querySelector('.add-growth-form');
        const modalTitle = modal.querySelector('.modal-title');
        const saveButton = modal.querySelector('#growthMeasurementSaveBtn');

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
        form.action = '{{ route("patients.growth-charts.store", $patient) }}';

        // Update modal title and button
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="fas fa-chart-line me-2"></i>{{ __("translation.add_measurement") }}';
        }
        if (saveButton) {
            saveButton.innerHTML = '<i class="fas fa-save me-2"></i>{{ __("translation.common.save") }}';
        }

        // Clear previous errors
        clearFormErrors(modal);

        // Set default date
        document.getElementById('measurementDate').value = '{{ date("Y-m-d") }}';

        // Show modal
        showModal(modal);
    };

    // ===========================
    // Inline Growth Charts
    // ===========================

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
</script>
