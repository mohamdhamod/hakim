<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('translation.examination.report') }} - {{ $examination->examination_number }}</title>
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
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            background: #f5f5f5;
            padding: 8px;
            margin-bottom: 10px;
            border-left: 3px solid #0066cc;
        }
        .section-content {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .vital-signs {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .vital-box {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .vital-value {
            font-size: 18px;
            font-weight: bold;
            color: #0066cc;
        }
        .vital-label {
            font-size: 10px;
            color: #666;
        }
        .prescription {
            background: #fffbeb;
            border: 1px solid #ffc107;
            padding: 15px;
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
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
        @media print {
            .no-print { display: none; }
            body { print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $examination->clinic->display_name }}</h1>
            <p>{{ $examination->clinic->address }}</p>
            @if($examination->clinic->phone)
                <p>{{ __('translation.common.phone') }}: {{ $examination->clinic->phone }}</p>
            @endif
        </div>
        
        <div class="header">
            <h1>{{ $clinic->display_name }}</h1>
            <p>{{ $clinic->address }}</p>
            @if($clinic->phone)
                <p>{{ __('translation.common.phone') }}: {{ $clinic->phone }}</p>
            @endif
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h3>{{ __('translation.patient.info') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.file_number') }}:</span>
                    <span>{{ $examination->patient->file_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.name') }}:</span>
                    <span>{{ $examination->patient->full_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.age') }}:</span>
                    <span>{{ $examination->patient->age ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.patient.gender') }}:</span>
                    <span>{{ $examination->patient->gender_label }}</span>
                </div>
            </div>

            <div class="info-box">
                <h3>{{ __('translation.examination.info') }}</h3>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.examination.number') }}:</span>
                    <span>{{ $examination->examination_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.examination.date') }}:</span>
                    <span>{{ $examination->examination_date->format('Y-m-d H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('translation.examination.doctor') }}:</span>
                    <span>{{ $examination->doctor->name }}</span>
                </div>
            </div>
        </div>

        @if($examination->chief_complaint)
        <div class="section">
            <div class="section-title">{{ __('translation.examination.chief_complaint') }}</div>
            <div class="section-content">{{ $examination->chief_complaint }}</div>
        </div>
        @endif

        @if($examination->temperature || $examination->blood_pressure || $examination->pulse_rate)
        <div class="section">
            <div class="section-title">{{ __('translation.examination.vital_signs') }}</div>
            <div class="vital-signs">
                @if($examination->temperature)
                <div class="vital-box">
                    <div class="vital-value">{{ $examination->temperature }}°C</div>
                    <div class="vital-label">{{ __('translation.examination.temperature') }}</div>
                </div>
                @endif
                @if($examination->blood_pressure)
                <div class="vital-box">
                    <div class="vital-value">{{ $examination->blood_pressure }}</div>
                    <div class="vital-label">{{ __('translation.examination.blood_pressure') }}</div>
                </div>
                @endif
                @if($examination->pulse_rate)
                <div class="vital-box">
                    <div class="vital-value">{{ $examination->pulse_rate }}</div>
                    <div class="vital-label">{{ __('translation.examination.pulse_rate') }}</div>
                </div>
                @endif
                @if($examination->oxygen_saturation)
                <div class="vital-box">
                    <div class="vital-value">{{ $examination->oxygen_saturation }}%</div>
                    <div class="vital-label">SpO2</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($examination->diagnosis)
        <div class="section">
            <div class="section-title">{{ __('translation.examination.diagnosis') }}</div>
            <div class="section-content">
                {{ $examination->diagnosis }}
            </div>
        </div>
        @endif

        @if($examination->prescriptions)
        <div class="section">
            <div class="section-title">{{ __('translation.examination.prescriptions') }}</div>
            <div class="prescription">
                <pre style="white-space: pre-wrap; font-family: inherit;">{{ $examination->prescriptions }}</pre>
            </div>
        </div>
        @endif

        @if($examination->follow_up_date)
        <div class="section">
            <div class="section-title">{{ __('translation.examination.follow_up') }}</div>
            <div class="section-content">
                <strong>{{ __('translation.examination.follow_up_date') }}:</strong> {{ $examination->follow_up_date->format('Y-m-d') }}
                @if($examination->follow_up_notes)
                    <br>{{ $examination->follow_up_notes }}
                @endif
            </div>
        </div>
        @endif

        <div class="footer">
            <div class="signature">
                <div class="signature-line">
                    {{ $examination->doctor->name }}
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
