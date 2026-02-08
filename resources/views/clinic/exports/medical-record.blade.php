<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('translation.medical_record') }} - {{ $patient->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c3e50;
        }
        .header h1 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .header .clinic-name {
            font-size: 16px;
            color: #3498db;
            margin-bottom: 3px;
        }
        .header .export-info {
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .patient-info {
            background: #ecf0f1;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .patient-info table {
            width: 100%;
        }
        .patient-info td {
            padding: 4px 8px;
        }
        .patient-info td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            background: #3498db;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th {
            background: #34495e;
            color: white;
            padding: 8px;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            font-size: 10px;
            font-weight: bold;
        }
        table td {
            padding: 6px 8px;
            border-bottom: 1px solid #ecf0f1;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
        }
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .badge-success { background: #27ae60; }
        .badge-danger { background: #e74c3c; }
        .badge-warning { background: #f39c12; color: #333; }
        .badge-info { background: #3498db; }
        .badge-primary { background: #9b59b6; }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #95a5a6;
            font-style: italic;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 8px;
        }
        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="clinic-name">{{ $clinic->name }}</div>
        <h1>{{ __('translation.medical_record') }}</h1>
        <div class="export-info">
            {{ __('translation.exported_by') }}: {{ $doctor->name }} | 
            {{ __('translation.export_date') }}: {{ $exportDate->format('Y-m-d H:i') }}
        </div>
    </div>

    {{-- Patient Information --}}
    <div class="patient-info">
        <table>
            <tr>
                <td>{{ __('translation.file_number') }}</td>
                <td>{{ $patient->file_number }}</td>
                <td>{{ __('translation.patient_name') }}</td>
                <td>{{ $patient->full_name }}</td>
            </tr>
            <tr>
                <td>{{ __('translation.age') }}</td>
                <td>{{ $patient->age }} {{ __('translation.years') }}</td>
                <td>{{ __('translation.gender') }}</td>
                <td>{{ __('translation.' . $patient->gender) }}</td>
            </tr>
            <tr>
                <td>{{ __('translation.phone') }}</td>
                <td>{{ $patient->phone }}</td>
                <td>{{ __('translation.blood_type') }}</td>
                <td>{{ $patient->blood_type ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- Chronic Diseases Section --}}
    @if($patient->chronicDiseases->count() > 0)
    <div class="section">
        <div class="section-title">{{ __('translation.chronic_diseases') }}</div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('translation.disease') }}</th>
                    <th>{{ __('translation.diagnosis_date') }}</th>
                    <th>{{ __('translation.severity') }}</th>
                    <th>{{ __('translation.status') }}</th>
                    <th>{{ __('translation.next_followup') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->chronicDiseases as $disease)
                <tr>
                    <td>{{ $disease->chronicDiseaseType->name }}</td>
                    <td>{{ $disease->diagnosis_date->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge badge-{{ $disease->severity === 'severe' ? 'danger' : ($disease->severity === 'moderate' ? 'warning' : 'success') }}">
                            {{ __('translation.' . $disease->severity) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-info">
                            {{ __('translation.' . $disease->status) }}
                        </span>
                    </td>
                    <td>{{ $disease->next_followup_date ? $disease->next_followup_date->format('Y-m-d') : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Lab Tests Section --}}
    @if($patient->labTestResults->count() > 0)
    <div class="section">
        <div class="section-title">{{ __('translation.recent_lab_tests') }} ({{ __('translation.last') }} 20)</div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('translation.date') }}</th>
                    <th>{{ __('translation.test_name') }}</th>
                    <th>{{ __('translation.result') }}</th>
                    <th>{{ __('translation.normal_range') }}</th>
                    <th>{{ __('translation.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->labTestResults as $test)
                <tr>
                    <td>{{ $test->test_date->format('Y-m-d') }}</td>
                    <td>{{ $test->labTestType->name }}</td>
                    <td>{{ $test->result_value }} {{ $test->labTestType->unit }}</td>
                    <td>
                        @if($test->labTestType->normal_range_min && $test->labTestType->normal_range_max)
                            {{ $test->labTestType->normal_range_min }} - {{ $test->labTestType->normal_range_max }}
                        @else
                            {{ $test->labTestType->normal_range_text ?? '-' }}
                        @endif
                    </td>
                    <td>
                        @if($test->interpretation === 'normal')
                            <span class="badge badge-success">{{ __('translation.normal') }}</span>
                        @elseif(in_array($test->interpretation, ['abnormal_low', 'abnormal_high', 'critical']))
                            <span class="badge badge-danger">{{ __('translation.abnormal') }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Vaccinations Section --}}
    @if($patient->vaccinationRecords->count() > 0)
    <div class="section">
        <div class="section-title">{{ __('translation.vaccinations') }}</div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('translation.vaccine') }}</th>
                    <th>{{ __('translation.date') }}</th>
                    <th>{{ __('translation.dose_number') }}</th>
                    <th>{{ __('translation.next_dose') }}</th>
                    <th>{{ __('translation.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->vaccinationRecords as $vac)
                <tr>
                    <td>{{ $vac->vaccinationType->name }}</td>
                    <td>{{ $vac->vaccination_date->format('Y-m-d') }}</td>
                    <td>{{ $vac->dose_number }}</td>
                    <td>{{ $vac->next_dose_due_date ? $vac->next_dose_due_date->format('Y-m-d') : '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $vac->status === 'completed' ? 'success' : 'warning' }}">
                            {{ __('translation.' . $vac->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Growth Measurements Section (for children) --}}
    @if($patient->age < 18 && $patient->growthMeasurements->count() > 0)
    <div class="section">
        <div class="section-title">{{ __('translation.growth_measurements') }} ({{ __('translation.last') }} 10)</div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('translation.date') }}</th>
                    <th>{{ __('translation.age') }}</th>
                    <th>{{ __('translation.weight') }} (kg)</th>
                    <th>{{ __('translation.height') }} (cm)</th>
                    <th>{{ __('translation.bmi') }}</th>
                    <th>{{ __('translation.interpretation') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->growthMeasurements as $m)
                <tr>
                    <td>{{ $m->measurement_date->format('Y-m-d') }}</td>
                    <td>{{ floor($m->age_months / 12) }}y {{ $m->age_months % 12 }}m</td>
                    <td>{{ $m->weight_kg ?? '-' }}</td>
                    <td>{{ $m->height_cm ?? '-' }}</td>
                    <td>{{ $m->bmi ? number_format($m->bmi, 1) : '-' }}</td>
                    <td>
                        @if($m->interpretation === 'normal')
                            <span class="badge badge-success">{{ __('translation.normal') }}</span>
                        @elseif($m->interpretation === 'monitor')
                            <span class="badge badge-warning">{{ __('translation.monitor') }}</span>
                        @elseif($m->interpretation === 'attention_needed')
                            <span class="badge badge-danger">{{ __('translation.attention_needed') }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Recent Examinations --}}
    @if($patient->examinations->count() > 0)
    <div class="section">
        <div class="section-title">{{ __('translation.recent_examinations') }} ({{ __('translation.last') }} 20)</div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('translation.date') }}</th>
                    <th>{{ __('translation.examination_number') }}</th>
                    <th>{{ __('translation.chief_complaint') }}</th>
                    <th>{{ __('translation.diagnosis') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->examinations as $exam)
                <tr>
                    <td>{{ $exam->examination_date->format('Y-m-d') }}</td>
                    <td>{{ $exam->examination_number }}</td>
                    <td>{{ Str::limit($exam->chief_complaint, 50) }}</td>
                    <td>{{ Str::limit($exam->diagnosis, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        {{ __('translation.confidential_document') }} | {{ $clinic->name }} | 
        {{ __('translation.page') }} <span class="page-number"></span>
    </div>
</body>
</html>
