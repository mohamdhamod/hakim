<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('translation.lab_tests_report') }} - {{ $patient->full_name }}</title>
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
            border-bottom: 3px solid #16a085;
        }
        .header h1 {
            font-size: 20px;
            color: #16a085;
            margin-bottom: 5px;
        }
        .header .clinic-name {
            font-size: 16px;
            color: #27ae60;
            margin-bottom: 3px;
        }
        .header .export-info {
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .patient-info {
            background: #e8f8f5;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #16a085;
        }
        .patient-info table {
            width: 100%;
        }
        .patient-info td {
            padding: 4px 8px;
        }
        .patient-info td:first-child {
            font-weight: bold;
            width: 25%;
        }
        .test-group {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .test-group-title {
            background: #27ae60;
            color: white;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th {
            background: #2c3e50;
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
        table tr.abnormal {
            background: #ffebee;
        }
        table tr.critical {
            background: #ffcdd2;
            font-weight: bold;
        }
        .result-value {
            font-weight: bold;
            font-size: 12px;
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
        .range-info {
            font-size: 9px;
            color: #7f8c8d;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
            font-style: italic;
            font-size: 14px;
        }
        .legend {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            font-size: 9px;
        }
        .legend-item {
            display: inline-block;
            margin-right: 15px;
        }
        .legend-color {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            border-radius: 2px;
            vertical-align: middle;
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
        .interpretation-note {
            margin-top: 15px;
            padding: 10px;
            background: #fff3cd;
            border-left: 4px solid #f39c12;
            font-size: 10px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="clinic-name">{{ $clinic->display_name }}</div>
        <h1>{{ __('translation.lab_tests_report') }}</h1>
        <div class="export-info">
            {{ __('translation.exported_by') }}: {{ $doctor->name }} | 
            {{ __('translation.export_date') }}: {{ $exportDate->format('Y-m-d H:i') }}
        </div>
    </div>

    {{-- Patient Information --}}
    <div class="patient-info">
        <table>
            <tr>
                <td>{{ __('translation.patient_name') }}</td>
                <td>{{ $patient->full_name }}</td>
                <td>{{ __('translation.file_number') }}</td>
                <td>{{ $patient->file_number }}</td>
            </tr>
            <tr>
                <td>{{ __('translation.age') }}</td>
                <td>{{ $patient->age }} {{ __('translation.years') }}</td>
                <td>{{ __('translation.gender') }}</td>
                <td>{{ __('translation.' . $patient->gender) }}</td>
            </tr>
        </table>
    </div>

    @if($patient->labTestResults->count() > 0)
        {{-- Lab Tests Table --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 12%">{{ __('translation.date') }}</th>
                    <th style="width: 25%">{{ __('translation.test_name') }}</th>
                    <th style="width: 15%">{{ __('translation.result') }}</th>
                    <th style="width: 20%">{{ __('translation.normal_range') }}</th>
                    <th style="width: 18%">{{ __('translation.interpretation') }}</th>
                    <th style="width: 10%">{{ __('translation.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->labTestResults as $test)
                <tr class="{{ in_array($test->interpretation, ['abnormal_low', 'abnormal_high']) ? 'abnormal' : ($test->interpretation === 'critical' ? 'critical' : '') }}">
                    <td>{{ $test->test_date->format('Y-m-d') }}</td>
                    <td>
                        {{ $test->labTestType->name }}
                        @if($test->labTestType->category)
                            <br><span class="range-info">({{ $test->labTestType->category }})</span>
                        @endif
                    </td>
                    <td class="result-value">
                        {{ $test->result_value }} {{ $test->labTestType->unit }}
                        @if($test->result_text)
                            <br><span class="range-info">{{ Str::limit($test->result_text, 30) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($test->labTestType->normal_range_min && $test->labTestType->normal_range_max)
                            {{ $test->labTestType->normal_range_min }} - {{ $test->labTestType->normal_range_max }}
                            {{ $test->labTestType->unit }}
                        @else
                            <span class="range-info">{{ $test->labTestType->normal_range_text ?? '-' }}</span>
                        @endif
                    </td>
                    <td>
                        @if($test->interpretation === 'normal')
                            {{ __('translation.within_normal_limits') }}
                        @elseif($test->interpretation === 'abnormal_low')
                            {{ __('translation.below_normal') }}
                        @elseif($test->interpretation === 'abnormal_high')
                            {{ __('translation.above_normal') }}
                        @elseif($test->interpretation === 'critical')
                            {{ __('translation.critical_value') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($test->interpretation === 'normal')
                            <span class="badge badge-success">✓</span>
                        @elseif($test->interpretation === 'critical')
                            <span class="badge badge-danger">!!!</span>
                        @elseif(in_array($test->interpretation, ['abnormal_low', 'abnormal_high']))
                            <span class="badge badge-warning">!</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Legend --}}
        <div class="legend">
            <strong>{{ __('translation.legend') }}:</strong>
            <div class="legend-item">
                <span class="legend-color" style="background: white;"></span>
                {{ __('translation.normal_values') }}
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background: #ffebee;"></span>
                {{ __('translation.abnormal_values') }}
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background: #ffcdd2;"></span>
                {{ __('translation.critical_values') }}
            </div>
        </div>

        {{-- Interpretation Note --}}
        @if($patient->labTestResults->whereIn('interpretation', ['abnormal_low', 'abnormal_high', 'critical'])->count() > 0)
        <div class="interpretation-note">
            <strong>⚠ {{ __('translation.important_note') }}:</strong>
            {{ __('translation.abnormal_results_detected') }}.
            {{ __('translation.please_consult_doctor') }}.
        </div>
        @endif

    @else
        <div class="no-data">
            {{ __('translation.no_lab_tests_available') }}
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        {{ __('translation.confidential_document') }} | {{ $clinic->display_name }} | 
        {{ __('translation.doctor') }}: {{ $doctor->name }}
    </div>
</body>
</html>
