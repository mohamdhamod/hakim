<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('translation.comprehensive_report') }} - {{ $patient->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
        }
        .report-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .info-box h3 {
            font-size: 14px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
            color: #0066cc;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            min-width: 120px;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background: #f5f5f5;
            padding: 8px;
            margin-bottom: 10px;
            border-left: 3px solid #0066cc;
        }
        [dir="rtl"] .section-title {
            border-left: none;
            border-right: 3px solid #0066cc;
        }
        .section-content {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th {
            background: #0066cc;
            color: white;
            padding: 8px;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            font-size: 11px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-resolved {
            background: #cce5ff;
            color: #004085;
        }
        .status-controlled {
            background: #fff3cd;
            color: #856404;
        }
        .vital-signs {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }
        .vital-box {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .vital-value {
            font-size: 16px;
            font-weight: bold;
            color: #0066cc;
        }
        .vital-label {
            font-size: 10px;
            color: #666;
        }
        .exam-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .exam-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .exam-date {
            font-weight: bold;
            color: #0066cc;
        }
        .prescription {
            background: #fffbeb;
            border: 1px solid #ffc107;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .signature {
            float: right;
            text-align: center;
            width: 200px;
        }
        [dir="rtl"] .signature {
            float: left;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        .no-data {
            color: #999;
            font-style: italic;
            padding: 10px;
            text-align: center;
        }
        /* Growth Chart Styles */
        .growth-summary-table td {
            padding: 10px 8px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ddd;
        }
        .growth-summary-value {
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
        }
        .growth-summary-unit {
            font-size: 9px;
            color: #666;
        }
        .growth-summary-label {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
        .growth-summary-percentile {
            margin-top: 4px;
        }
        .badge {
            padding: 2px 6px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            border-radius: 3px;
        }
        .badge-success {
            background: #d4edda;
            color: #28a745;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background: #f8d7da;
            color: #dc3545;
        }
        .chart-container {
            border: 1px solid #ddd;
            background: white;
            margin: 4px 0;
            page-break-inside: avoid;
            border-radius: 5px;
        }
        .chart-header {
            padding: 6px 10px;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        .chart-header-weight { background: #0066cc; }
        .chart-header-height { background: #28a745; }
        .chart-header-bmi { background: #6610f2; }
        .chart-header-head { background: #17a2b8; }
        .chart-body {
            padding: 5px;
            text-align: center;
        }
        .chart-img {
            width: 100%;
            height: auto;
            max-height: 150px;
        }
        .chart-value-box {
            text-align: center;
            font-size: 9px;
            color: #333;
            margin-top: 3px;
            padding: 4px 8px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .chart-legend {
            margin: 8px 0;
            padding: 6px 10px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .chart-legend td {
            padding: 3px 8px;
            font-size: 8px;
            text-align: center;
            vertical-align: middle;
            border: none;
            background: transparent;
        }
        .legend-line {
            display: inline-block;
            width: 14px;
            height: 2px;
            margin-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 3px;
            vertical-align: middle;
        }
        .legend-line-red { background: #dc3545; }
        .legend-line-orange { background: #ffc107; }
        .legend-line-green { background: #28a745; height: 3px; }
        .legend-box {
            display: inline-block;
            width: 10px;
            height: 6px;
            margin-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 3px;
            vertical-align: middle;
        }
        .legend-danger { background: rgba(220, 53, 69, 0.3); }
        .legend-caution { background: rgba(255, 193, 7, 0.4); }
        .legend-normal { background: rgba(40, 167, 69, 0.3); }
        .legend-patient { background: #0066cc; border-radius: 50%; width: 6px; height: 6px; }
        @media print {
            .no-print { display: none; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>{{ $clinic->display_name }}</h1>
            <p>{{ $clinic->address }}</p>
            @if($clinic->phone)
                <p>{{ __('translation.common.phone') }}: {{ $clinic->phone }}</p>
            @endif
        </div>

        <div class="report-title">{{ __('translation.comprehensive_report') }}</div>

        {{-- Patient Information --}}
        @php
            $ageYears = null;
            $ageMonths = null;
            if ($patient->date_of_birth) {
                $birthDate = \Carbon\Carbon::parse($patient->date_of_birth);
                $now = \Carbon\Carbon::now();
                $ageYears = (int) $birthDate->diffInYears($now);
                $ageMonths = (int) $birthDate->copy()->addYears($ageYears)->diffInMonths($now);
            }
        @endphp
        <div class="info-grid">
            <div class="info-box">
                <h3>{{ __('translation.patient.info') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.file_number') }}:</span>
                    <span>{{ $patient->file_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.name') }}:</span>
                    <span>{{ $patient->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.date_of_birth') }}:</span>
                    <span>{{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.age') }}:</span>
                    <span>
                        @if($ageYears !== null)
                            {{ $ageYears }} {{ __('translation.years') }} {{ $ageMonths > 0 ? '، ' . $ageMonths . ' ' . __('translation.months') : '' }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.gender') }}:</span>
                    <span>{{ $patient->gender_label }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.blood_type') }}:</span>
                    <span>{{ $patient->blood_type_label ?? '-' }}</span>
                </div>
            </div>

            <div class="info-box">
                <h3>{{ __('translation.contact_info') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.phone') }}:</span>
                    <span>{{ $patient->phone ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.email') }}:</span>
                    <span>{{ $patient->email ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.address') }}:</span>
                    <span>{{ $patient->address ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.emergency_contact') }}:</span>
                    <span>
                        @if($patient->emergency_contact_name)
                            {{ $patient->emergency_contact_name }}
                            @if($patient->emergency_contact_phone)
                                ({{ $patient->emergency_contact_phone }})
                            @endif
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.export.date') }}:</span>
                    <span>{{ $exportDate->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Medical History Section --}}
        <div class="section">
            <div class="section-title">{{ __('translation.patient.medical_history') }}</div>
            <div class="info-grid">
                <div class="info-box">
                    <h3>{{ __('translation.patient.allergies') }}</h3>
                    <p style="margin: 0; white-space: pre-wrap;">{{ $patient->allergies ?: __('translation.no_data') }}</p>
                </div>
                <div class="info-box">
                    <h3>{{ __('translation.patient.family_history') }}</h3>
                    <p style="margin: 0; white-space: pre-wrap;">{{ $patient->family_history ?: __('translation.no_data') }}</p>
                </div>
            </div>
            @if($patient->medical_history)
            <div style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-radius: 5px;">
                <strong>{{ __('translation.patient.medical_history_details') }}:</strong>
                <p style="margin: 5px 0 0 0; white-space: pre-wrap;">{{ $patient->medical_history }}</p>
            </div>
            @endif
            @if($patient->notes)
            <div style="margin-top: 10px; padding: 10px; background: #fff3cd; border-radius: 5px; border-left: 3px solid #ffc107;">
                <strong>{{ __('translation.notes') }}:</strong>
                <p style="margin: 5px 0 0 0; white-space: pre-wrap;">{{ $patient->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Chronic Diseases Section --}}
        @if($clinic->hasService('chronic_diseases'))
        <div class="section">
            <div class="section-title">{{ __('translation.chronic_diseases') }}</div>
            @if($patient->chronicDiseases->count() > 0)
            @foreach($patient->chronicDiseases as $disease)
            <div style="border: 1px solid #ddd; border-radius: 5px; padding: 10px; margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 8px;">
                    <strong style="color: #0066cc;">{{ $disease->chronicDiseaseType->name ?? $disease->custom_name }}</strong>
                    <span class="status-badge status-{{ $disease->status }}">
                        {{ __('translation.chronic_disease.status_' . $disease->status) }}
                    </span>
                </div>
                <table style="margin-bottom: 0;">
                    <tr>
                        <td style="width: 25%; border: none; padding: 3px 5px;"><strong>{{ __('translation.chronic_disease.diagnosed_at') }}:</strong></td>
                        <td style="border: none; padding: 3px 5px;">{{ $disease->diagnosed_at ? $disease->diagnosed_at->format('Y-m-d') : '-' }}</td>
                        <td style="width: 25%; border: none; padding: 3px 5px;"><strong>{{ __('translation.severity') }}:</strong></td>
                        <td style="border: none; padding: 3px 5px;">{{ $disease->severity ? __('translation.' . $disease->severity) : '-' }}</td>
                    </tr>
                    @if($disease->treatment_plan)
                    <tr>
                        <td style="border: none; padding: 3px 5px;"><strong>{{ __('translation.treatment_plan') }}:</strong></td>
                        <td colspan="3" style="border: none; padding: 3px 5px;">{{ $disease->treatment_plan }}</td>
                    </tr>
                    @endif
                    @if($disease->notes)
                    <tr>
                        <td style="border: none; padding: 3px 5px;"><strong>{{ __('translation.notes') }}:</strong></td>
                        <td colspan="3" style="border: none; padding: 3px 5px;">{{ $disease->notes }}</td>
                    </tr>
                    @endif
                </table>
                
                {{-- Monitoring Records --}}
                @if($disease->monitoringRecords && $disease->monitoringRecords->count() > 0)
                <div style="margin-top: 10px; background: #f9f9f9; padding: 8px; border-radius: 3px;">
                    <strong style="font-size: 11px; color: #666;">{{ __('translation.monitoring_records') }}:</strong>
                    <table style="margin-top: 5px; margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="font-size: 10px; padding: 4px;">{{ __('translation.date') }}</th>
                                <th style="font-size: 10px; padding: 4px;">{{ __('translation.parameter_name') }}</th>
                                <th style="font-size: 10px; padding: 4px;">{{ __('translation.value') }}</th>
                                <th style="font-size: 10px; padding: 4px;">{{ __('translation.status') }}</th>
                                <th style="font-size: 10px; padding: 4px;">{{ __('translation.notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disease->monitoringRecords as $monitoring)
                            <tr>
                                <td style="font-size: 10px; padding: 4px;">{{ $monitoring->monitoring_date->format('Y-m-d') }}</td>
                                <td style="font-size: 10px; padding: 4px;">{{ $monitoring->parameter_name }}</td>
                                <td style="font-size: 10px; padding: 4px;">{{ $monitoring->parameter_value }} {{ $monitoring->parameter_unit }}</td>
                                <td style="font-size: 10px; padding: 4px;">
                                    @if($monitoring->status)
                                    <span class="status-badge status-{{ $monitoring->status }}">{{ __('translation.' . $monitoring->status) }}</span>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td style="font-size: 10px; padding: 4px;">{{ Str::limit($monitoring->notes, 30) ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
            @endforeach
            @else
            <div class="no-data">{{ __('translation.no_chronic_diseases') }}</div>
            @endif
        </div>
        @endif

        {{-- All Examinations Section --}}
        <div class="section">
            <div class="section-title">{{ __('translation.examination.all_examinations') }} ({{ $patient->examinations->count() }})</div>
            @if($patient->examinations->count() > 0)
                @foreach($patient->examinations as $exam)
                <div class="exam-card">
                    <div class="exam-header">
                        <span class="exam-date">
                            <strong>#{{ $exam->examination_number }}</strong> - {{ $exam->examination_date->format('Y-m-d H:i') }}
                        </span>
                        <span>{{ __('translation.examination.doctor') }}: {{ $exam->doctor->name ?? '-' }}</span>
                    </div>
                    
                    {{-- Vital Signs --}}
                    @if($exam->temperature || $exam->blood_pressure || $exam->pulse_rate || $exam->respiratory_rate || $exam->oxygen_saturation || $exam->weight || $exam->height)
                    <div class="vital-signs">
                        @if($exam->temperature)
                        <div class="vital-box">
                            <div class="vital-value">{{ $exam->temperature }}°C</div>
                            <div class="vital-label">{{ __('translation.examination.temperature') }}</div>
                        </div>
                        @endif
                        @if($exam->blood_pressure)
                        <div class="vital-box">
                            <div class="vital-value">{{ $exam->blood_pressure }}</div>
                            <div class="vital-label">{{ __('translation.examination.blood_pressure') }}</div>
                        </div>
                        @endif
                        @if($exam->pulse_rate)
                        <div class="vital-box">
                            <div class="vital-value">{{ $exam->pulse_rate }}</div>
                            <div class="vital-label">{{ __('translation.examination.pulse_rate') }}</div>
                        </div>
                        @endif
                        @if($exam->respiratory_rate)
                        <div class="vital-box">
                            <div class="vital-value">{{ $exam->respiratory_rate }}</div>
                            <div class="vital-label">{{ __('translation.examination.respiratory_rate') }}</div>
                        </div>
                        @endif
                        @if($exam->oxygen_saturation)
                        <div class="vital-box">
                            <div class="vital-value">{{ $exam->oxygen_saturation }}%</div>
                            <div class="vital-label">SpO2</div>
                        </div>
                        @endif
                        @if($exam->weight)
                        <div class="vital-box">
                            <div class="vital-value">{{ $exam->weight }} kg</div>
                            <div class="vital-label">{{ __('translation.weight') }}</div>
                        </div>
                        @endif
                        @if($exam->height)
                        <div class="vital-box">
                            <div class="vital-value">{{ $exam->height }} cm</div>
                            <div class="vital-label">{{ __('translation.height') }}</div>
                        </div>
                        @endif
                        @if($exam->bmi)
                        <div class="vital-box">
                            <div class="vital-value">{{ number_format($exam->bmi, 1) }}</div>
                            <div class="vital-label">{{ __('translation.bmi') }}</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Chief Complaint --}}
                    @if($exam->chief_complaint)
                    <div style="margin-bottom: 8px;">
                        <strong style="color: #0066cc;">{{ __('translation.examination.chief_complaint') }}:</strong>
                        <span>{{ $exam->chief_complaint }}</span>
                    </div>
                    @endif

                    {{-- Present Illness History --}}
                    @if($exam->present_illness_history)
                    <div style="margin-bottom: 8px;">
                        <strong>{{ __('translation.examination.present_illness_history') }}:</strong>
                        <span>{{ $exam->present_illness_history }}</span>
                    </div>
                    @endif

                    {{-- Physical Examination --}}
                    @if($exam->physical_examination)
                    <div style="margin-bottom: 8px;">
                        <strong>{{ __('translation.examination.physical_examination') }}:</strong>
                        <span>{{ $exam->physical_examination }}</span>
                    </div>
                    @endif

                    {{-- Diagnosis with ICD Code --}}
                    @if($exam->diagnosis)
                    <div style="margin-bottom: 8px; background: #e8f4fd; padding: 8px; border-radius: 4px; border-left: 3px solid #0066cc;">
                        <strong style="color: #0066cc;">{{ __('translation.examination.diagnosis') }}:</strong>
                        <span>{{ $exam->diagnosis }}</span>
                        @if($exam->icd_code)
                        <span style="background: #0066cc; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 5px;">ICD: {{ $exam->icd_code }}</span>
                        @endif
                    </div>
                    @endif

                    {{-- Treatment Plan --}}
                    @if($exam->treatment_plan)
                    <div style="margin-bottom: 8px;">
                        <strong>{{ __('translation.examination.treatment_plan') }}:</strong>
                        <span>{{ $exam->treatment_plan }}</span>
                    </div>
                    @endif

                    {{-- Prescriptions --}}
                    @if($exam->prescriptions)
                    <div class="prescription">
                        <strong>{{ __('translation.examination.prescriptions') }}:</strong>
                        <pre style="white-space: pre-wrap; font-family: inherit; margin-top: 5px;">{{ $exam->prescriptions }}</pre>
                    </div>
                    @endif

                    {{-- Lab Tests Ordered/Results --}}
                    @if($exam->lab_tests_ordered || $exam->lab_tests_results)
                    <div style="margin-top: 8px; background: #f5f5f5; padding: 8px; border-radius: 4px;">
                        @if($exam->lab_tests_ordered)
                        <div style="margin-bottom: 5px;">
                            <strong>{{ __('translation.examination.lab_tests_ordered') }}:</strong>
                            <span>{{ $exam->lab_tests_ordered }}</span>
                        </div>
                        @endif
                        @if($exam->lab_tests_results)
                        <div>
                            <strong>{{ __('translation.examination.lab_tests_results') }}:</strong>
                            <span>{{ $exam->lab_tests_results }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Imaging Ordered/Results --}}
                    @if($exam->imaging_ordered || $exam->imaging_results)
                    <div style="margin-top: 8px; background: #f5f5f5; padding: 8px; border-radius: 4px;">
                        @if($exam->imaging_ordered)
                        <div style="margin-bottom: 5px;">
                            <strong>{{ __('translation.examination.imaging_ordered') }}:</strong>
                            <span>{{ $exam->imaging_ordered }}</span>
                        </div>
                        @endif
                        @if($exam->imaging_results)
                        <div>
                            <strong>{{ __('translation.examination.imaging_results') }}:</strong>
                            <span>{{ $exam->imaging_results }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Follow Up --}}
                    @if($exam->follow_up_date)
                    <div style="margin-top: 8px; background: #fff3cd; padding: 8px; border-radius: 4px; border-left: 3px solid #ffc107;">
                        <strong style="color: #856404;">{{ __('translation.examination.follow_up') }}:</strong>
                        <span>{{ $exam->follow_up_date->format('Y-m-d') }}</span>
                        @if($exam->follow_up_notes)
                        <span> - {{ $exam->follow_up_notes }}</span>
                        @endif
                    </div>
                    @endif

                    {{-- Doctor Notes --}}
                    @if($exam->doctor_notes)
                    <div style="margin-top: 8px; font-style: italic; color: #666;">
                        <strong>{{ __('translation.doctor_notes') }}:</strong>
                        <span>{{ $exam->doctor_notes }}</span>
                    </div>
                    @endif
                </div>
                @endforeach
            @else
            <div class="no-data">{{ __('translation.examination.no_examinations') }}</div>
            @endif
        </div>

        {{-- Lab Tests Section --}}
        @if($clinic->hasService('lab_tests'))
        <div class="section">
            <div class="section-title">{{ __('translation.lab_tests') }}</div>
            @if($patient->labTestResults->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>{{ __('translation.test_date') }}</th>
                        <th>{{ __('translation.test_type') }}</th>
                        <th>{{ __('translation.result_value') }}</th>
                        <th>{{ __('translation.normal_range') }}</th>
                        <th>{{ __('translation.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->labTestResults as $test)
                    <tr>
                        <td>{{ $test->test_date->format('Y-m-d') }}</td>
                        <td>{{ $test->labTestType->name ?? '-' }}</td>
                        <td>{{ $test->result_value }} {{ $test->labTestType->unit ?? '' }}</td>
                        <td>{{ $test->labTestType->normal_range ?? '-' }}</td>
                        <td>
                            @if($test->is_abnormal)
                            <span style="color: #dc3545; font-weight: bold;">{{ __('translation.abnormal_results') }}</span>
                            @else
                            <span style="color: #28a745;">{{ __('translation.normal') }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-data">{{ __('translation.no_lab_tests') }}</div>
            @endif
        </div>
        @endif

        {{-- Vaccinations Section --}}
        @if($clinic->hasService('vaccinations'))
        <div class="section">
            <div class="section-title">{{ __('translation.vaccinations') }}</div>
            @if($patient->vaccinationRecords->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>{{ __('translation.vaccination_date') }}</th>
                        <th>{{ __('translation.vaccination_type') }}</th>
                        <th>{{ __('translation.dose') }}</th>
                        <th>{{ __('translation.batch_number') }}</th>
                        <th>{{ __('translation.administered_by') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->vaccinationRecords as $vaccination)
                    <tr>
                        <td>{{ $vaccination->vaccination_date->format('Y-m-d') }}</td>
                        <td>{{ $vaccination->vaccinationType->name ?? '-' }}</td>
                        <td>{{ $vaccination->dose_number ?? '-' }}</td>
                        <td>{{ $vaccination->batch_number ?? '-' }}</td>
                        <td>{{ $vaccination->administeredBy->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="no-data">{{ __('translation.no_vaccinations') }}</div>
            @endif
        </div>
        @endif

        {{-- Growth Measurements Section (for children) --}}
        @if($clinic->hasService('growth_chart'))
        @if($patient->age < 18 && $patient->growthMeasurements->count() > 0)
        @php
            $measurements = $patient->growthMeasurements->sortBy('age_months');
            $latest = $patient->growthMeasurements->first();
            $showHead = $patient->age < 6;
            $isMale = $patient->gender === 'male';
            
            // Badge helper
            $getBadgeClass = function($percentile) {
                if ($percentile === null) return '';
                if ($percentile < 3 || $percentile > 97) return 'badge-danger';
                if ($percentile < 15 || $percentile > 85) return 'badge-warning';
                return 'badge-success';
            };
            
            // WHO Reference Data
            $whoWeight = $isMale ? [
                ['age'=>0,'p3'=>2.5,'p15'=>2.9,'p50'=>3.3,'p85'=>3.9,'p97'=>4.4],
                ['age'=>3,'p3'=>4.7,'p15'=>5.4,'p50'=>6.4,'p85'=>7.2,'p97'=>7.8],
                ['age'=>6,'p3'=>6.2,'p15'=>7.1,'p50'=>7.9,'p85'=>8.8,'p97'=>9.5],
                ['age'=>9,'p3'=>7.2,'p15'=>8.1,'p50'=>8.9,'p85'=>9.9,'p97'=>10.5],
                ['age'=>12,'p3'=>7.8,'p15'=>8.8,'p50'=>9.6,'p85'=>10.8,'p97'=>11.5],
                ['age'=>18,'p3'=>8.8,'p15'=>9.9,'p50'=>10.9,'p85'=>12.1,'p97'=>13.0],
                ['age'=>24,'p3'=>9.7,'p15'=>10.8,'p50'=>12.2,'p85'=>13.6,'p97'=>14.7],
                ['age'=>36,'p3'=>11.3,'p15'=>12.5,'p50'=>14.3,'p85'=>16.2,'p97'=>17.7],
                ['age'=>48,'p3'=>12.7,'p15'=>14.2,'p50'=>16.3,'p85'=>18.8,'p97'=>20.7],
                ['age'=>60,'p3'=>14.1,'p15'=>15.9,'p50'=>18.3,'p85'=>21.2,'p97'=>23.6],
            ] : [
                ['age'=>0,'p3'=>2.4,'p15'=>2.8,'p50'=>3.2,'p85'=>3.7,'p97'=>4.2],
                ['age'=>3,'p3'=>4.4,'p15'=>5.0,'p50'=>5.8,'p85'=>6.6,'p97'=>7.2],
                ['age'=>6,'p3'=>5.8,'p15'=>6.5,'p50'=>7.3,'p85'=>8.2,'p97'=>8.8],
                ['age'=>9,'p3'=>6.7,'p15'=>7.5,'p50'=>8.2,'p85'=>9.3,'p97'=>10.0],
                ['age'=>12,'p3'=>7.1,'p15'=>8.1,'p50'=>8.9,'p85'=>10.1,'p97'=>10.9],
                ['age'=>18,'p3'=>8.1,'p15'=>9.2,'p50'=>10.2,'p85'=>11.6,'p97'=>12.6],
                ['age'=>24,'p3'=>9.0,'p15'=>10.2,'p50'=>11.5,'p85'=>13.1,'p97'=>14.3],
                ['age'=>36,'p3'=>10.6,'p15'=>12.0,'p50'=>13.9,'p85'=>16.0,'p97'=>17.8],
                ['age'=>48,'p3'=>12.1,'p15'=>13.8,'p50'=>16.1,'p85'=>18.8,'p97'=>21.1],
                ['age'=>60,'p3'=>13.5,'p15'=>15.5,'p50'=>18.2,'p85'=>21.5,'p97'=>24.5],
            ];
            
            $whoHeight = $isMale ? [
                ['age'=>0,'p3'=>46.3,'p15'=>48.0,'p50'=>49.9,'p85'=>51.8,'p97'=>53.4],
                ['age'=>3,'p3'=>57.6,'p15'=>59.5,'p50'=>61.4,'p85'=>63.4,'p97'=>65.0],
                ['age'=>6,'p3'=>63.6,'p15'=>65.4,'p50'=>67.6,'p85'=>69.8,'p97'=>71.6],
                ['age'=>9,'p3'=>68.0,'p15'=>69.7,'p50'=>72.0,'p85'=>74.2,'p97'=>76.2],
                ['age'=>12,'p3'=>71.0,'p15'=>73.0,'p50'=>75.7,'p85'=>78.1,'p97'=>80.2],
                ['age'=>18,'p3'=>76.9,'p15'=>79.2,'p50'=>82.3,'p85'=>85.0,'p97'=>87.3],
                ['age'=>24,'p3'=>81.7,'p15'=>84.1,'p50'=>87.8,'p85'=>91.0,'p97'=>93.4],
                ['age'=>36,'p3'=>89.0,'p15'=>91.9,'p50'=>96.1,'p85'=>99.8,'p97'=>102.7],
                ['age'=>48,'p3'=>95.4,'p15'=>98.9,'p50'=>103.3,'p85'=>107.3,'p97'=>110.7],
                ['age'=>60,'p3'=>101.2,'p15'=>105.0,'p50'=>110.0,'p85'=>114.5,'p97'=>118.0],
            ] : [
                ['age'=>0,'p3'=>45.6,'p15'=>47.2,'p50'=>49.1,'p85'=>51.0,'p97'=>52.7],
                ['age'=>3,'p3'=>56.2,'p15'=>58.0,'p50'=>59.8,'p85'=>61.8,'p97'=>63.5],
                ['age'=>6,'p3'=>61.8,'p15'=>63.5,'p50'=>65.7,'p85'=>68.0,'p97'=>69.8],
                ['age'=>9,'p3'=>66.0,'p15'=>67.7,'p50'=>70.1,'p85'=>72.6,'p97'=>74.5],
                ['age'=>12,'p3'=>69.2,'p15'=>71.4,'p50'=>74.0,'p85'=>76.7,'p97'=>78.9],
                ['age'=>18,'p3'=>74.0,'p15'=>76.8,'p50'=>80.7,'p85'=>84.2,'p97'=>87.0],
                ['age'=>24,'p3'=>80.0,'p15'=>82.5,'p50'=>86.4,'p85'=>90.0,'p97'=>92.5],
                ['age'=>36,'p3'=>87.4,'p15'=>90.4,'p50'=>95.1,'p85'=>99.4,'p97'=>102.5],
                ['age'=>48,'p3'=>94.0,'p15'=>97.5,'p50'=>102.7,'p85'=>107.5,'p97'=>111.0],
                ['age'=>60,'p3'=>99.9,'p15'=>103.8,'p50'=>109.4,'p85'=>114.8,'p97'=>118.8],
            ];
            
            $whoBmi = $isMale ? [
                ['age'=>0,'p3'=>11.0,'p15'=>12.2,'p50'=>13.4,'p85'=>14.8,'p97'=>16.1],
                ['age'=>12,'p3'=>14.4,'p15'=>15.5,'p50'=>16.8,'p85'=>18.2,'p97'=>19.3],
                ['age'=>24,'p3'=>13.9,'p15'=>14.8,'p50'=>16.0,'p85'=>17.3,'p97'=>18.4],
                ['age'=>36,'p3'=>13.5,'p15'=>14.3,'p50'=>15.5,'p85'=>16.9,'p97'=>18.1],
                ['age'=>48,'p3'=>13.3,'p15'=>14.1,'p50'=>15.3,'p85'=>16.8,'p97'=>18.2],
                ['age'=>60,'p3'=>13.2,'p15'=>14.0,'p50'=>15.2,'p85'=>16.9,'p97'=>18.5],
            ] : [
                ['age'=>0,'p3'=>10.8,'p15'=>12.0,'p50'=>13.2,'p85'=>14.6,'p97'=>15.9],
                ['age'=>12,'p3'=>14.0,'p15'=>15.0,'p50'=>16.3,'p85'=>17.8,'p97'=>19.0],
                ['age'=>24,'p3'=>13.6,'p15'=>14.5,'p50'=>15.7,'p85'=>17.1,'p97'=>18.3],
                ['age'=>36,'p3'=>13.2,'p15'=>14.0,'p50'=>15.2,'p85'=>16.7,'p97'=>18.0],
                ['age'=>48,'p3'=>13.0,'p15'=>13.8,'p50'=>15.0,'p85'=>16.6,'p97'=>18.1],
                ['age'=>60,'p3'=>12.9,'p15'=>13.7,'p50'=>15.0,'p85'=>16.8,'p97'=>18.5],
            ];
            
            $whoHead = $isMale ? [
                ['age'=>0,'p3'=>32.4,'p15'=>33.5,'p50'=>34.5,'p85'=>35.5,'p97'=>36.5],
                ['age'=>3,'p3'=>38.3,'p15'=>39.5,'p50'=>40.5,'p85'=>41.5,'p97'=>42.5],
                ['age'=>6,'p3'=>41.5,'p15'=>42.5,'p50'=>43.5,'p85'=>44.5,'p97'=>45.5],
                ['age'=>12,'p3'=>44.0,'p15'=>45.0,'p50'=>46.0,'p85'=>47.0,'p97'=>48.0],
                ['age'=>24,'p3'=>46.5,'p15'=>47.5,'p50'=>48.5,'p85'=>49.5,'p97'=>50.5],
                ['age'=>36,'p3'=>47.5,'p15'=>48.5,'p50'=>49.5,'p85'=>50.5,'p97'=>51.5],
                ['age'=>60,'p3'=>48.5,'p15'=>49.5,'p50'=>50.5,'p85'=>51.5,'p97'=>52.5],
            ] : [
                ['age'=>0,'p3'=>31.9,'p15'=>33.0,'p50'=>34.0,'p85'=>35.0,'p97'=>36.0],
                ['age'=>3,'p3'=>37.0,'p15'=>38.0,'p50'=>39.0,'p85'=>40.0,'p97'=>41.0],
                ['age'=>6,'p3'=>40.0,'p15'=>41.0,'p50'=>42.0,'p85'=>43.0,'p97'=>44.0],
                ['age'=>12,'p3'=>42.5,'p15'=>43.5,'p50'=>44.5,'p85'=>45.5,'p97'=>46.5],
                ['age'=>24,'p3'=>45.0,'p15'=>46.0,'p50'=>47.0,'p85'=>48.0,'p97'=>49.0],
                ['age'=>36,'p3'=>46.5,'p15'=>47.5,'p50'=>48.5,'p85'=>49.5,'p97'=>50.5],
                ['age'=>60,'p3'=>47.5,'p15'=>48.5,'p50'=>49.5,'p85'=>50.5,'p97'=>51.5],
            ];
            
            // Interpolate WHO values
            $interpolateWho = function($data, $ageMonths) {
                if ($ageMonths <= $data[0]['age']) return $data[0];
                if ($ageMonths >= end($data)['age']) return end($data);
                
                $prev = $data[0];
                foreach ($data as $row) {
                    if ($row['age'] >= $ageMonths) {
                        if ($row['age'] == $ageMonths) return $row;
                        $ratio = ($ageMonths - $prev['age']) / max(1, $row['age'] - $prev['age']);
                        return [
                            'age' => $ageMonths,
                            'p3' => $prev['p3'] + $ratio * ($row['p3'] - $prev['p3']),
                            'p15' => $prev['p15'] + $ratio * ($row['p15'] - $prev['p15']),
                            'p50' => $prev['p50'] + $ratio * ($row['p50'] - $prev['p50']),
                            'p85' => $prev['p85'] + $ratio * ($row['p85'] - $prev['p85']),
                            'p97' => $prev['p97'] + $ratio * ($row['p97'] - $prev['p97']),
                        ];
                    }
                    $prev = $row;
                }
                return end($data);
            };
            
            // Chart dimensions
            $chartWidth = 380;
            $chartHeight = 180;
            $marginLeft = 35;
            $marginRight = 10;
            $marginTop = 10;
            $marginBottom = 25;
            $plotWidth = $chartWidth - $marginLeft - $marginRight;
            $plotHeight = $chartHeight - $marginTop - $marginBottom;
            
            // Function to create chart image
            $createChartImage = function($whoData, $measurements, $valueField, $minVal, $maxVal, $unit, $title) use ($interpolateWho, $chartWidth, $chartHeight, $marginLeft, $marginRight, $marginTop, $marginBottom, $plotWidth, $plotHeight) {
                $img = imagecreatetruecolor($chartWidth, $chartHeight);
                imagesavealpha($img, true);
                
                $white = imagecolorallocate($img, 255, 255, 255);
                $gray = imagecolorallocate($img, 229, 231, 235);
                $darkGray = imagecolorallocate($img, 107, 114, 128);
                $lightGray = imagecolorallocate($img, 243, 244, 246);
                $red = imagecolorallocate($img, 220, 53, 69);
                $orange = imagecolorallocate($img, 253, 126, 20);
                $green = imagecolorallocate($img, 40, 167, 69);
                $blue = imagecolorallocate($img, 26, 35, 126);
                $lightRed = imagecolorallocatealpha($img, 220, 53, 69, 100);
                $lightOrange = imagecolorallocatealpha($img, 255, 193, 7, 95);
                $lightGreen = imagecolorallocatealpha($img, 40, 167, 69, 100);
                
                imagefill($img, 0, 0, $white);
                
                imagesetthickness($img, 1);
                $gridLines = 5;
                for ($i = 0; $i <= $gridLines; $i++) {
                    $y = $marginTop + ($i * $plotHeight / $gridLines);
                    imageline($img, $marginLeft, $y, $chartWidth - $marginRight, $y, $lightGray);
                }
                
                $maxAge = end($whoData)['age'];
                $ageStep = $maxAge <= 24 ? 6 : 12;
                
                for ($age = 0; $age <= $maxAge; $age += $ageStep) {
                    $x = $marginLeft + ($age / $maxAge * $plotWidth);
                    imageline($img, $x, $marginTop, $x, $chartHeight - $marginBottom, $lightGray);
                }
                
                $valToY = function($val) use ($minVal, $maxVal, $marginTop, $plotHeight) {
                    return $marginTop + $plotHeight - (($val - $minVal) / ($maxVal - $minVal) * $plotHeight);
                };
                
                $ageToX = function($age) use ($maxAge, $marginLeft, $plotWidth) {
                    return $marginLeft + ($age / $maxAge * $plotWidth);
                };
                
                $pointsPerSegment = 10;
                $curvePoints = ['p97' => [], 'p85' => [], 'p50' => [], 'p15' => [], 'p3' => []];
                
                for ($i = 0; $i < count($whoData) - 1; $i++) {
                    $startAge = $whoData[$i]['age'];
                    $endAge = $whoData[$i + 1]['age'];
                    
                    for ($j = 0; $j <= $pointsPerSegment; $j++) {
                        $age = $startAge + ($endAge - $startAge) * ($j / $pointsPerSegment);
                        $who = $interpolateWho($whoData, $age);
                        $x = $ageToX($age);
                        
                        foreach (['p97', 'p85', 'p50', 'p15', 'p3'] as $p) {
                            $curvePoints[$p][] = ['x' => $x, 'y' => $valToY($who[$p])];
                        }
                    }
                }
                
                // Fill zones
                $topY = $marginTop;
                for ($i = 0; $i < count($curvePoints['p97']) - 1; $i++) {
                    $pts = [$curvePoints['p97'][$i]['x'], $topY, $curvePoints['p97'][$i + 1]['x'], $topY, $curvePoints['p97'][$i + 1]['x'], $curvePoints['p97'][$i + 1]['y'], $curvePoints['p97'][$i]['x'], $curvePoints['p97'][$i]['y']];
                    imagefilledpolygon($img, $pts, $lightRed);
                }
                for ($i = 0; $i < count($curvePoints['p97']) - 1; $i++) {
                    $pts = [$curvePoints['p97'][$i]['x'], $curvePoints['p97'][$i]['y'], $curvePoints['p97'][$i + 1]['x'], $curvePoints['p97'][$i + 1]['y'], $curvePoints['p85'][$i + 1]['x'], $curvePoints['p85'][$i + 1]['y'], $curvePoints['p85'][$i]['x'], $curvePoints['p85'][$i]['y']];
                    imagefilledpolygon($img, $pts, $lightOrange);
                }
                for ($i = 0; $i < count($curvePoints['p85']) - 1; $i++) {
                    $pts = [$curvePoints['p85'][$i]['x'], $curvePoints['p85'][$i]['y'], $curvePoints['p85'][$i + 1]['x'], $curvePoints['p85'][$i + 1]['y'], $curvePoints['p15'][$i + 1]['x'], $curvePoints['p15'][$i + 1]['y'], $curvePoints['p15'][$i]['x'], $curvePoints['p15'][$i]['y']];
                    imagefilledpolygon($img, $pts, $lightGreen);
                }
                for ($i = 0; $i < count($curvePoints['p15']) - 1; $i++) {
                    $pts = [$curvePoints['p15'][$i]['x'], $curvePoints['p15'][$i]['y'], $curvePoints['p15'][$i + 1]['x'], $curvePoints['p15'][$i + 1]['y'], $curvePoints['p3'][$i + 1]['x'], $curvePoints['p3'][$i + 1]['y'], $curvePoints['p3'][$i]['x'], $curvePoints['p3'][$i]['y']];
                    imagefilledpolygon($img, $pts, $lightOrange);
                }
                $bottomY = $chartHeight - $marginBottom;
                for ($i = 0; $i < count($curvePoints['p3']) - 1; $i++) {
                    $pts = [$curvePoints['p3'][$i]['x'], $curvePoints['p3'][$i]['y'], $curvePoints['p3'][$i + 1]['x'], $curvePoints['p3'][$i + 1]['y'], $curvePoints['p3'][$i + 1]['x'], $bottomY, $curvePoints['p3'][$i]['x'], $bottomY];
                    imagefilledpolygon($img, $pts, $lightRed);
                }
                
                // Draw curves
                imagesetthickness($img, 2);
                for ($i = 0; $i < count($curvePoints['p97']) - 1; $i++) {
                    imageline($img, $curvePoints['p97'][$i]['x'], $curvePoints['p97'][$i]['y'], $curvePoints['p97'][$i + 1]['x'], $curvePoints['p97'][$i + 1]['y'], $red);
                    imageline($img, $curvePoints['p3'][$i]['x'], $curvePoints['p3'][$i]['y'], $curvePoints['p3'][$i + 1]['x'], $curvePoints['p3'][$i + 1]['y'], $red);
                }
                for ($i = 0; $i < count($curvePoints['p85']) - 1; $i++) {
                    imageline($img, $curvePoints['p85'][$i]['x'], $curvePoints['p85'][$i]['y'], $curvePoints['p85'][$i + 1]['x'], $curvePoints['p85'][$i + 1]['y'], $orange);
                    imageline($img, $curvePoints['p15'][$i]['x'], $curvePoints['p15'][$i]['y'], $curvePoints['p15'][$i + 1]['x'], $curvePoints['p15'][$i + 1]['y'], $orange);
                }
                imagesetthickness($img, 3);
                for ($i = 0; $i < count($curvePoints['p50']) - 1; $i++) {
                    imageline($img, $curvePoints['p50'][$i]['x'], $curvePoints['p50'][$i]['y'], $curvePoints['p50'][$i + 1]['x'], $curvePoints['p50'][$i + 1]['y'], $green);
                }
                
                // Patient data
                $patientPoints = [];
                foreach ($measurements as $m) {
                    $val = $m->$valueField;
                    if ($val === null) continue;
                    $age = $m->age_months;
                    if ($age > $maxAge) continue;
                    $patientPoints[] = ['x' => $ageToX($age), 'y' => $valToY($val), 'age' => $age];
                }
                usort($patientPoints, fn($a, $b) => $a['age'] <=> $b['age']);
                
                imagesetthickness($img, 3);
                for ($i = 0; $i < count($patientPoints) - 1; $i++) {
                    imageline($img, $patientPoints[$i]['x'], $patientPoints[$i]['y'], $patientPoints[$i + 1]['x'], $patientPoints[$i + 1]['y'], $blue);
                }
                imagesetthickness($img, 1);
                foreach ($patientPoints as $pt) {
                    imagefilledellipse($img, $pt['x'], $pt['y'], 10, 10, $blue);
                    imageellipse($img, $pt['x'], $pt['y'], 10, 10, $white);
                }
                
                // Axes
                imagesetthickness($img, 2);
                imageline($img, $marginLeft, $marginTop, $marginLeft, $chartHeight - $marginBottom, $darkGray);
                imageline($img, $marginLeft, $chartHeight - $marginBottom, $chartWidth - $marginRight, $chartHeight - $marginBottom, $darkGray);
                
                $fontSize = 2;
                for ($i = 0; $i <= $gridLines; $i++) {
                    $val = $maxVal - ($i * ($maxVal - $minVal) / $gridLines);
                    $y = $marginTop + ($i * $plotHeight / $gridLines);
                    imagestring($img, $fontSize, 5, $y - 6, number_format($val, 0), $darkGray);
                }
                for ($age = 0; $age <= $maxAge; $age += $ageStep) {
                    $x = $marginLeft + ($age / $maxAge * $plotWidth);
                    imagestring($img, $fontSize, $x - 5, $chartHeight - 15, $age . 'm', $darkGray);
                }
                imagestring($img, $fontSize, 5, 2, $unit, $darkGray);
                
                ob_start();
                imagepng($img);
                $imageData = ob_get_clean();
                imagedestroy($img);
                
                return 'data:image/png;base64,' . base64_encode($imageData);
            };
            
            $wMin = 2; $wMax = max(26, ($latest->weight_kg ?? 15) + 6);
            $hMin = 45; $hMax = max(125, ($latest->height_cm ?? 80) + 15);
            $bMin = 10; $bMax = 22;
            $headMin = 30; $headMax = 55;
            
            $weightChartImg = $createChartImage($whoWeight, $measurements, 'weight_kg', $wMin, $wMax, 'kg', 'Weight');
            $heightChartImg = $createChartImage($whoHeight, $measurements, 'height_cm', $hMin, $hMax, 'cm', 'Height');
            $bmiChartImg = $createChartImage($whoBmi, $measurements, 'bmi', $bMin, $bMax, 'BMI', 'BMI');
            $headChartImg = $createChartImage($whoHead, $measurements, 'head_circumference_cm', $headMin, $headMax, 'cm', 'Head');
        @endphp
        
        <div class="section">
            <div class="section-title">{{ __('translation.growth_chart') }}</div>
            
            {{-- Latest Measurements Summary --}}
            <table class="growth-summary-table" style="margin-bottom: 10px;">
                <tr>
                    <td style="border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 3px solid #0066cc;">
                        <div class="growth-summary-value">{{ $latest->weight_kg ?? '-' }}<span class="growth-summary-unit"> kg</span></div>
                        <div class="growth-summary-label">{{ __('translation.weight') }}</div>
                        @if($latest->weight_percentile)
                        <div class="growth-summary-percentile"><span class="badge {{ $getBadgeClass($latest->weight_percentile) }}">P{{ round($latest->weight_percentile) }}</span></div>
                        @endif
                    </td>
                    <td style="border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 3px solid #28a745;">
                        <div class="growth-summary-value">{{ $latest->height_cm ?? '-' }}<span class="growth-summary-unit"> cm</span></div>
                        <div class="growth-summary-label">{{ __('translation.height') }}</div>
                        @if($latest->height_percentile)
                        <div class="growth-summary-percentile"><span class="badge {{ $getBadgeClass($latest->height_percentile) }}">P{{ round($latest->height_percentile) }}</span></div>
                        @endif
                    </td>
                    <td style="border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 3px solid #6610f2;">
                        <div class="growth-summary-value">{{ $latest->bmi ? number_format($latest->bmi, 1) : '-' }}</div>
                        <div class="growth-summary-label">{{ __('translation.bmi') }}</div>
                        @if($latest->bmi_percentile)
                        <div class="growth-summary-percentile"><span class="badge {{ $getBadgeClass($latest->bmi_percentile) }}">P{{ round($latest->bmi_percentile) }}</span></div>
                        @endif
                    </td>
                    @if($showHead && $latest->head_circumference_cm)
                    <td style="border-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 3px solid #17a2b8;">
                        <div class="growth-summary-value">{{ $latest->head_circumference_cm }}<span class="growth-summary-unit"> cm</span></div>
                        <div class="growth-summary-label">{{ __('translation.head_circumference') }}</div>
                        @if($latest->head_circumference_percentile)
                        <div class="growth-summary-percentile"><span class="badge {{ $getBadgeClass($latest->head_circumference_percentile) }}">P{{ round($latest->head_circumference_percentile) }}</span></div>
                        @endif
                    </td>
                    @endif
                </tr>
            </table>
            
            {{-- Chart Legend --}}
            <table class="chart-legend">
                <tr>
                    <td><span class="legend-line legend-line-green"></span>P50</td>
                    <td><span class="legend-line legend-line-orange"></span>P15/P85</td>
                    <td><span class="legend-line legend-line-red"></span>P3/P97</td>
                    <td><span class="legend-box legend-normal"></span>{{ __('translation.normal') }}</td>
                    <td><span class="legend-box legend-caution"></span>{{ __('translation.growth.zone_caution') }}</td>
                    <td><span class="legend-box legend-danger"></span>{{ __('translation.growth.zone_extreme') }}</td>
                    <td><span class="legend-box legend-patient"></span>{{ __('translation.growth.patient_data') }}</td>
                </tr>
            </table>
            
            {{-- Growth Charts --}}
            <table style="width: 100%;">
                <tr>
                    <td width="50%" style="vertical-align: top; padding: 2px;">
                        <div class="chart-container">
                            <div class="chart-header chart-header-weight">{{ __('translation.weight_for_age') }}</div>
                            <div class="chart-body">
                                <img src="{{ $weightChartImg }}" class="chart-img" alt="Weight Chart">
                                <div class="chart-value-box">
                                    <b>{{ $latest->weight_kg ?? '-' }} kg</b> = 
                                    <span class="badge {{ $getBadgeClass($latest->weight_percentile) }}">P{{ round($latest->weight_percentile ?? 50) }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="50%" style="vertical-align: top; padding: 2px;">
                        <div class="chart-container">
                            <div class="chart-header chart-header-height">{{ __('translation.height_for_age') }}</div>
                            <div class="chart-body">
                                <img src="{{ $heightChartImg }}" class="chart-img" alt="Height Chart">
                                <div class="chart-value-box">
                                    <b>{{ $latest->height_cm ?? '-' }} cm</b> = 
                                    <span class="badge {{ $getBadgeClass($latest->height_percentile) }}">P{{ round($latest->height_percentile ?? 50) }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; margin-top: 5px;">
                <tr>
                    <td width="50%" style="vertical-align: top; padding: 2px;">
                        <div class="chart-container">
                            <div class="chart-header chart-header-bmi">{{ __('translation.bmi_for_age') }}</div>
                            <div class="chart-body">
                                <img src="{{ $bmiChartImg }}" class="chart-img" alt="BMI Chart">
                                <div class="chart-value-box">
                                    <b>{{ $latest->bmi ? number_format($latest->bmi, 1) : '-' }}</b> = 
                                    <span class="badge {{ $getBadgeClass($latest->bmi_percentile) }}">P{{ round($latest->bmi_percentile ?? 50) }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td width="50%" style="vertical-align: top; padding: 2px;">
                        <div class="chart-container">
                            <div class="chart-header chart-header-head">{{ __('translation.head_circumference_for_age') }}</div>
                            <div class="chart-body">
                                <img src="{{ $headChartImg }}" class="chart-img" alt="Head Circumference Chart">
                                <div class="chart-value-box">
                                    <b>{{ $latest->head_circumference_cm ?? '-' }} cm</b> = 
                                    <span class="badge {{ $getBadgeClass($latest->head_circumference_percentile) }}">P{{ round($latest->head_circumference_percentile ?? 50) }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
            
            {{-- Measurements Table --}}
            <table style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>{{ __('translation.measurement_date') }}</th>
                        <th>{{ __('translation.age') }}</th>
                        <th>{{ __('translation.weight') }}</th>
                        <th>{{ __('translation.height') }}</th>
                        <th>{{ __('translation.head_circumference') }}</th>
                        <th>{{ __('translation.bmi') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->growthMeasurements->take(6) as $measurement)
                    <tr>
                        <td>{{ $measurement->measurement_date->format('Y-m-d') }}</td>
                        <td>{{ $measurement->age_months }} {{ __('translation.months') }}</td>
                        <td>{{ $measurement->weight ? $measurement->weight . ' kg' : '-' }}</td>
                        <td>{{ $measurement->height ? $measurement->height . ' cm' : '-' }}</td>
                        <td>{{ $measurement->head_circumference ? $measurement->head_circumference . ' cm' : '-' }}</td>
                        <td>{{ $measurement->bmi ? number_format($measurement->bmi, 1) : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @endif

        {{-- Footer --}}
        <div class="footer">
            <div class="signature">
                <div class="signature-line">
                    {{ $doctor->name }}
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
