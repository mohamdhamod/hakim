<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('translation.growth_chart_report') }} - {{ $patient->full_name }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px solid #e67e22;
        }
        .header h1 {
            font-size: 18px;
            color: #e67e22;
            margin-bottom: 5px;
        }
        .header .clinic-name {
            font-size: 14px;
            color: #d35400;
            margin-bottom: 3px;
        }
        .header .export-info {
            font-size: 8px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .patient-header {
            display: table;
            width: 100%;
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .patient-header .info-group {
            display: table-cell;
            width: 25%;
            padding: 5px 10px;
        }
        .patient-header .label {
            font-size: 8px;
            opacity: 0.9;
        }
        .patient-header .value {
            font-size: 12px;
            font-weight: bold;
            margin-top: 2px;
        }
        .summary-section {
            margin-bottom: 15px;
            padding: 10px;
            background: #fef5e7;
            border-left: 4px solid #f39c12;
            border-radius: 3px;
        }
        .summary-section table {
            width: 100%;
        }
        .summary-section td {
            padding: 4px 8px;
            font-size: 9px;
        }
        .summary-section td:first-child {
            font-weight: bold;
            width: 25%;
        }
        .measurements-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 10px;
        }
        .measurements-table th {
            background: #34495e;
            color: white;
            padding: 6px 4px;
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #2c3e50;
        }
        .measurements-table td {
            padding: 5px 4px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .measurements-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .measurements-table tr:hover {
            background: #e8f8f5;
        }
        .percentile-cell {
            font-size: 8px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            color: white;
        }
        .badge-success { background: #27ae60; }
        .badge-danger { background: #e74c3c; }
        .badge-warning { background: #f39c12; color: #333; }
        .badge-info { background: #3498db; }
        .status-severely-low { background: #c0392b; color: white; }
        .status-low { background: #e67e22; color: white; }
        .status-normal { background: #27ae60; color: white; }
        .status-high { background: #f39c12; color: #333; }
        .status-severely-high { background: #8e44ad; color: white; }
        .interpretation-legend {
            margin-top: 10px;
            padding: 8px;
            background: #ecf0f1;
            border-radius: 3px;
            font-size: 8px;
        }
        .interpretation-legend .legend-item {
            display: inline-block;
            margin-right: 12px;
        }
        .interpretation-legend .color-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            margin-right: 3px;
            border-radius: 2px;
            vertical-align: middle;
        }
        .who-reference {
            margin-top: 10px;
            padding: 8px;
            background: #d6eaf8;
            border-left: 4px solid #3498db;
            font-size: 8px;
        }
        .trends-summary {
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .trends-summary h3 {
            font-size: 11px;
            margin-bottom: 8px;
            color: #2c3e50;
        }
        .trends-summary table {
            width: 100%;
            font-size: 9px;
        }
        .trends-summary td {
            padding: 4px 8px;
        }
        .trend-arrow {
            font-size: 14px;
            font-weight: bold;
        }
        .trend-up { color: #e74c3c; }
        .trend-down { color: #3498db; }
        .trend-stable { color: #95a5a6; }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
            font-style: italic;
            font-size: 14px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 25px;
            text-align: center;
            font-size: 8px;
            color: #7f8c8d;
            border-top: 1px solid #ecf0f1;
            padding-top: 6px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="clinic-name">{{ $clinic->name }}</div>
        <h1>📊 {{ __('translation.growth_chart_report') }}</h1>
        <div class="export-info">
            {{ __('translation.exported_by') }}: {{ $doctor->name }} | 
            {{ __('translation.export_date') }}: {{ $exportDate->format('Y-m-d H:i') }}
        </div>
    </div>

    {{-- Patient Header --}}
    <div class="patient-header">
        <div class="info-group">
            <div class="label">{{ __('translation.patient_name') }}</div>
            <div class="value">{{ $patient->full_name }}</div>
        </div>
        <div class="info-group">
            <div class="label">{{ __('translation.file_number') }}</div>
            <div class="value">{{ $patient->file_number }}</div>
        </div>
        <div class="info-group">
            <div class="label">{{ __('translation.date_of_birth') }}</div>
            <div class="value">{{ $patient->birth_date->format('Y-m-d') }}</div>
        </div>
        <div class="info-group">
            <div class="label">{{ __('translation.current_age') }}</div>
            <div class="value">{{ $patient->age }} {{ __('translation.years') }}</div>
        </div>
    </div>

    @if($patient->growthMeasurements->count() > 0)
        {{-- Latest Measurement Summary --}}
        @php
            $latest = $patient->growthMeasurements->first();
        @endphp
        <div class="summary-section">
            <table>
                <tr>
                    <td>{{ __('translation.latest_measurement') }}:</td>
                    <td>{{ $latest->measurement_date->format('Y-m-d') }} ({{ floor($latest->age_months / 12) }} {{ __('translation.years') }} {{ $latest->age_months % 12 }} {{ __('translation.months') }})</td>
                    <td>{{ __('translation.weight') }}:</td>
                    <td>{{ $latest->weight_kg ?? '-' }} kg</td>
                    <td>{{ __('translation.height') }}:</td>
                    <td>{{ $latest->height_cm ?? '-' }} cm</td>
                    <td>{{ __('translation.bmi') }}:</td>
                    <td>{{ $latest->bmi ? number_format($latest->bmi, 1) : '-' }}</td>
                </tr>
            </table>
        </div>

        {{-- Growth Measurements Table --}}
        <table class="measurements-table">
            <thead>
                <tr>
                    <th rowspan="2">{{ __('translation.date') }}</th>
                    <th rowspan="2">{{ __('translation.age') }}<br>({{ __('translation.years') }}/{{ __('translation.months') }})</th>
                    <th colspan="2">{{ __('translation.weight') }} (kg)</th>
                    <th colspan="2">{{ __('translation.height') }} (cm)</th>
                    <th colspan="2">{{ __('translation.bmi') }}</th>
                    @if($patient->age < 6)
                        <th colspan="2">{{ __('translation.head_circumference') }} (cm)</th>
                    @endif
                    <th rowspan="2">{{ __('translation.overall_status') }}</th>
                    <th rowspan="2">{{ __('translation.notes') }}</th>
                </tr>
                <tr>
                    <th>{{ __('translation.value') }}</th>
                    <th>%tile</th>
                    <th>{{ __('translation.value') }}</th>
                    <th>%tile</th>
                    <th>{{ __('translation.value') }}</th>
                    <th>%tile</th>
                    @if($patient->age < 6)
                        <th>{{ __('translation.value') }}</th>
                        <th>%tile</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($patient->growthMeasurements as $m)
                <tr>
                    <td>{{ $m->measurement_date->format('Y-m-d') }}</td>
                    <td>{{ floor($m->age_months / 12) }}y {{ $m->age_months % 12 }}m</td>
                    
                    {{-- Weight --}}
                    <td><strong>{{ $m->weight_kg ?? '-' }}</strong></td>
                    <td class="percentile-cell">
                        @if($m->weight_percentile)
                            <span class="badge status-{{ $m->weight_interpretation ?? 'normal' }}">
                                {{ number_format($m->weight_percentile, 1) }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    
                    {{-- Height --}}
                    <td><strong>{{ $m->height_cm ?? '-' }}</strong></td>
                    <td class="percentile-cell">
                        @if($m->height_percentile)
                            <span class="badge status-{{ $m->height_interpretation ?? 'normal' }}">
                                {{ number_format($m->height_percentile, 1) }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    
                    {{-- BMI --}}
                    <td><strong>{{ $m->bmi ? number_format($m->bmi, 1) : '-' }}</strong></td>
                    <td class="percentile-cell">
                        @if($m->bmi_percentile)
                            <span class="badge status-{{ $m->bmi_interpretation ?? 'normal' }}">
                                {{ number_format($m->bmi_percentile, 1) }}%
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    
                    {{-- Head Circumference (if child < 6 years) --}}
                    @if($patient->age < 6)
                        <td><strong>{{ $m->head_circumference_cm ?? '-' }}</strong></td>
                        <td class="percentile-cell">
                            @if($m->head_circumference_percentile)
                                <span class="badge status-{{ $m->head_circumference_interpretation ?? 'normal' }}">
                                    {{ number_format($m->head_circumference_percentile, 1) }}%
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    @endif
                    
                    {{-- Overall Status --}}
                    <td>
                        @if($m->interpretation === 'normal')
                            <span class="badge badge-success">{{ __('translation.normal') }}</span>
                        @elseif($m->interpretation === 'monitor')
                            <span class="badge badge-warning">{{ __('translation.monitor') }}</span>
                        @elseif($m->interpretation === 'attention_needed')
                            <span class="badge badge-danger">{{ __('translation.attention_needed') }}</span>
                        @endif
                    </td>
                    
                    {{-- Notes --}}
                    <td style="font-size: 8px;">{{ Str::limit($m->notes, 20) ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- WHO Percentile Interpretation Legend --}}
        <div class="interpretation-legend">
            <strong>{{ __('translation.who_percentile_interpretation') }}:</strong>
            <div class="legend-item">
                <span class="color-box status-severely-low"></span>
                &lt; 3% {{ __('translation.severely_low') }}
            </div>
            <div class="legend-item">
                <span class="color-box status-low"></span>
                3-15% {{ __('translation.low') }}
            </div>
            <div class="legend-item">
                <span class="color-box status-normal"></span>
                15-85% {{ __('translation.normal') }}
            </div>
            <div class="legend-item">
                <span class="color-box status-high"></span>
                85-97% {{ __('translation.high') }}
            </div>
            <div class="legend-item">
                <span class="color-box status-severely-high"></span>
                &gt; 97% {{ __('translation.severely_high') }}
            </div>
        </div>

        {{-- WHO Reference Note --}}
        <div class="who-reference">
            <strong>ℹ {{ __('translation.important') }}:</strong>
            {{ __('translation.who_growth_standards_note') }}
        </div>

        {{-- Growth Trends Summary --}}
        @if($patient->growthMeasurements->count() >= 2)
        @php
            $first = $patient->growthMeasurements->last();
            $last = $patient->growthMeasurements->first();
            $weightChange = $last->weight_kg && $first->weight_kg ? $last->weight_kg - $first->weight_kg : null;
            $heightChange = $last->height_cm && $first->height_cm ? $last->height_cm - $first->height_cm : null;
            $bmiChange = $last->bmi && $first->bmi ? $last->bmi - $first->bmi : null;
        @endphp
        <div class="trends-summary">
            <h3>{{ __('translation.growth_trends') }} ({{ $first->measurement_date->format('Y-m-d') }} → {{ $last->measurement_date->format('Y-m-d') }})</h3>
            <table>
                <tr>
                    <td><strong>{{ __('translation.weight_change') }}:</strong></td>
                    <td>
                        @if($weightChange)
                            <span class="trend-arrow {{ $weightChange > 0 ? 'trend-up' : ($weightChange < 0 ? 'trend-down' : 'trend-stable') }}">
                                {{ $weightChange > 0 ? '↑' : ($weightChange < 0 ? '↓' : '→') }}
                            </span>
                            {{ number_format(abs($weightChange), 1) }} kg
                        @else
                            -
                        @endif
                    </td>
                    <td><strong>{{ __('translation.height_change') }}:</strong></td>
                    <td>
                        @if($heightChange)
                            <span class="trend-arrow {{ $heightChange > 0 ? 'trend-up' : 'trend-stable' }}">↑</span>
                            {{ number_format($heightChange, 1) }} cm
                        @else
                            -
                        @endif
                    </td>
                    <td><strong>{{ __('translation.bmi_change') }}:</strong></td>
                    <td>
                        @if($bmiChange)
                            <span class="trend-arrow {{ $bmiChange > 0 ? 'trend-up' : ($bmiChange < 0 ? 'trend-down' : 'trend-stable') }}">
                                {{ $bmiChange > 0 ? '↑' : ($bmiChange < 0 ? '↓' : '→') }}
                            </span>
                            {{ number_format(abs($bmiChange), 1) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        @endif

    @else
        <div class="no-data">
            📊 {{ __('translation.no_growth_measurements_available') }}
        </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        {{ __('translation.confidential_document') }} | {{ $clinic->name }} | 
        {{ __('translation.based_on_who_growth_standards') }}
    </div>
</body>
</html>
