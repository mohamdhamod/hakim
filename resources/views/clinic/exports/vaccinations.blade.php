<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('translation.vaccination_record') }} - {{ $patient->full_name }}</title>
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
            border-bottom: 3px solid #8e44ad;
        }
        .header h1 {
            font-size: 20px;
            color: #8e44ad;
            margin-bottom: 5px;
        }
        .header .clinic-name {
            font-size: 16px;
            color: #9b59b6;
            margin-bottom: 3px;
        }
        .header .export-info {
            font-size: 9px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .patient-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .patient-card table {
            width: 100%;
            color: white;
        }
        .patient-card td {
            padding: 5px 8px;
        }
        .patient-card td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .summary-cards {
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .summary-card {
            display: table-cell;
            width: 33%;
            padding: 12px;
            text-align: center;
            border-radius: 5px;
            margin-right: 10px;
        }
        .summary-card.completed {
            background: #d4edda;
            border: 2px solid #27ae60;
        }
        .summary-card.scheduled {
            background: #fff3cd;
            border: 2px solid #f39c12;
        }
        .summary-card.missed {
            background: #f8d7da;
            border: 2px solid #e74c3c;
        }
        .summary-card .number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .summary-card .label {
            font-size: 10px;
            color: #555;
        }
        .vaccine-group {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .vaccine-group-title {
            background: #9b59b6;
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
            background: #34495e;
            color: white;
            padding: 8px;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
            font-size: 10px;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ecf0f1;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
        }
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
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
        .dose-indicator {
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            background: #3498db;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        .notes-section {
            margin-top: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-left: 4px solid #9b59b6;
            font-size: 10px;
        }
        .signature-section {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px dashed #95a5a6;
        }
        .signature-block {
            display: inline-block;
            width: 45%;
            text-align: center;
            margin: 10px 2%;
        }
        .signature-line {
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 40px;
            font-size: 10px;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #95a5a6;
            font-style: italic;
            font-size: 14px;
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
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <div class="clinic-name">{{ $clinic->display_name }}</div>
        <h1>📋 {{ __('translation.vaccination_record') }}</h1>
        <div class="export-info">
            {{ __('translation.exported_by') }}: {{ $doctor->name }} | 
            {{ __('translation.export_date') }}: {{ $exportDate->format('Y-m-d H:i') }}
        </div>
    </div>

    {{-- Patient Card --}}
    <div class="patient-card">
        <table>
            <tr>
                <td>{{ __('translation.patient_name') }}</td>
                <td>{{ $patient->full_name }}</td>
                <td>{{ __('translation.file_number') }}</td>
                <td>{{ $patient->file_number }}</td>
            </tr>
            <tr>
                <td>{{ __('translation.date_of_birth') }}</td>
                <td>{{ $patient->date_of_birth?->format('Y-m-d') ?? '-' }}</td>
                <td>{{ __('translation.age') }}</td>
                <td>{{ $patient->age }} {{ __('translation.years') }}</td>
            </tr>
            <tr>
                <td>{{ __('translation.gender') }}</td>
                <td>{{ __('translation.' . $patient->gender) }}</td>
                <td>{{ __('translation.blood_type') }}</td>
                <td>{{ $patient->blood_type ?? '-' }}</td>
            </tr>
        </table>
    </div>

    @if($patient->vaccinationRecords->count() > 0)
        {{-- Summary Statistics --}}
        <div class="summary-cards">
            <div class="summary-card completed">
                <div class="number">{{ $patient->vaccinationRecords->where('status', 'completed')->count() }}</div>
                <div class="label">{{ __('translation.completed') }}</div>
            </div>
            <div class="summary-card scheduled">
                <div class="number">{{ $patient->vaccinationRecords->where('status', 'scheduled')->count() }}</div>
                <div class="label">{{ __('translation.scheduled') }}</div>
            </div>
            <div class="summary-card missed">
                <div class="number">{{ $patient->vaccinationRecords->where('status', 'missed')->count() }}</div>
                <div class="label">{{ __('translation.missed') }}</div>
            </div>
        </div>

        {{-- Vaccination Records Table --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 8%">{{ __('translation.dose') }}</th>
                    <th style="width: 25%">{{ __('translation.vaccine_name') }}</th>
                    <th style="width: 12%">{{ __('translation.date_given') }}</th>
                    <th style="width: 12%">{{ __('translation.next_dose_date') }}</th>
                    <th style="width: 15%">{{ __('translation.batch_number') }}</th>
                    <th style="width: 10%">{{ __('translation.status') }}</th>
                    <th style="width: 18%">{{ __('translation.notes') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->vaccinationRecords->sortBy('vaccination_date') as $vac)
                <tr>
                    <td>
                        <span class="dose-indicator">{{ $vac->dose_number }}</span>
                    </td>
                    <td>
                        <strong>{{ $vac->vaccinationType->name }}</strong>
                        @if($vac->vaccinationType->description)
                            <br><span style="font-size: 9px; color: #7f8c8d;">{{ Str::limit($vac->vaccinationType->description, 40) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($vac->status === 'completed')
                            {{ $vac->vaccination_date->format('Y-m-d') }}
                        @else
                            <span style="color: #95a5a6;">{{ __('translation.pending') }}</span>
                        @endif
                    </td>
                    <td>
                        @if($vac->next_dose_due_date)
                            {{ $vac->next_dose_due_date->format('Y-m-d') }}
                            @if($vac->status === 'scheduled' && $vac->next_dose_due_date->isPast())
                                <br><span class="badge badge-danger">{{ __('translation.overdue') }}</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $vac->batch_number ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $vac->status === 'completed' ? 'success' : ($vac->status === 'missed' ? 'danger' : 'warning') }}">
                            {{ __('translation.' . $vac->status) }}
                        </span>
                    </td>
                    <td>
                        @if($vac->notes)
                            {{ Str::limit($vac->notes, 30) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Important Notes Section --}}
        @if($patient->vaccinationRecords->where('status', 'missed')->count() > 0 || 
            $patient->vaccinationRecords->where('status', 'scheduled')->filter(fn($v) => $v->next_dose_due_date && $v->next_dose_due_date->isPast())->count() > 0)
        <div class="notes-section">
            <strong>⚠ {{ __('translation.important_notes') }}:</strong><br>
            @if($patient->vaccinationRecords->where('status', 'missed')->count() > 0)
                • {{ __('translation.missed_vaccinations_detected') }}: {{ $patient->vaccinationRecords->where('status', 'missed')->count() }}<br>
            @endif
            @if($patient->vaccinationRecords->where('status', 'scheduled')->filter(fn($v) => $v->next_dose_due_date && $v->next_dose_due_date->isPast())->count() > 0)
                • {{ __('translation.overdue_vaccinations') }}: {{ $patient->vaccinationRecords->where('status', 'scheduled')->filter(fn($v) => $v->next_dose_due_date && $v->next_dose_due_date->isPast())->count() }}<br>
            @endif
            {{ __('translation.please_schedule_appointment') }}.
        </div>
        @endif

    @else
        <div class="no-data">
            {{ __('translation.no_vaccination_records_available') }}
        </div>
    @endif

    {{-- Signature Section --}}
    <div class="signature-section">
        <div class="signature-block">
            <div class="signature-line">
                {{ __('translation.doctor_signature') }}<br>
                {{ $doctor->name }}
            </div>
        </div>
        <div class="signature-block">
            <div class="signature-line">
                {{ __('translation.clinic_stamp') }}<br>
                {{ $clinic->display_name }}
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        {{ __('translation.official_vaccination_record') }} | {{ $clinic->display_name }} | 
        {{ __('translation.keep_for_school_records') }}
    </div>
</body>
</html>
