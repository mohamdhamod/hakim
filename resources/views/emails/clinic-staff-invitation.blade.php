<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('translation.clinic.staff_invitation_title') }}</title>
    <style>
        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Segoe UI', 'Tahoma', 'Arial', sans-serif" : "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif" }};
            line-height: 1.8;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            direction: {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
        }
        .container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
        }
        .hero h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .hero p {
            margin: 0;
            opacity: 0.9;
        }
        .content {
            margin-bottom: 30px;
        }
        .invite-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        .invite-details p {
            margin: 8px 0;
        }
        .invite-details strong {
            color: #667eea;
        }
        .credentials-box {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .credentials-box h3 {
            color: #92400e;
            margin-top: 0;
        }
        .credential {
            background: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-family: monospace;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            margin: 10px 5px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
        }
        .cta {
            text-align: center;
            margin: 30px 0;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .role-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
            justify-content: center;
        }
        .feature {
            background: #f3f4f6;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
        }
        .warning {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 15px;
            color: #991b1b;
            font-size: 13px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏥 {{ config('app.name') }}</div>
        </div>
        
        <div class="hero">
            <h1>{{ __('translation.clinic.you_are_invited') }}</h1>
            <p>{{ __('translation.clinic.invited_to_join', ['name' => $inviter->name]) }}</p>
        </div>
        
        <div class="content">
            <p>{{ __('translation.clinic.hello') }},</p>
            
            <p>{{ __('translation.clinic.invitation_message', ['inviter' => $inviter->name, 'clinic' => $clinic->display_name]) }}</p>
            
            <div class="invite-details">
                <p><strong>{{ __('translation.clinic.clinic_name') }}:</strong> {{ $clinic->display_name }}</p>
                <p><strong>{{ __('translation.clinic.your_role') }}:</strong> 
                    <span class="role-badge">{{ __('translation.clinic.patient_editor_role') }}</span>
                </p>
                <p><strong>{{ __('translation.clinic.invited_by') }}:</strong> {{ $inviter->name }} ({{ $inviter->email }})</p>
            </div>

            <div class="credentials-box">
                <h3>🔐 {{ __('translation.clinic.your_login_credentials') }}</h3>
                <p>{{ __('translation.clinic.use_credentials_to_login') }}</p>
                <p><strong>{{ __('translation.common.email') }}:</strong></p>
                <div class="credential">{{ $inviteeEmail }}</div>
                <p><strong>{{ __('translation.clinic.password') }}:</strong></p>
                <div class="credential">{{ $password }}</div>
            </div>

            <div class="warning">
                ⚠️ {{ __('translation.clinic.change_password_warning') }}
            </div>

            <div style="margin: 20px 0;">
                <p><strong>{{ __('translation.clinic.what_you_can_do') }}:</strong></p>
                <div class="features">
                    <span class="feature">👥 {{ __('translation.clinic.manage_patients') }}</span>
                    <span class="feature">📋 {{ __('translation.clinic.view_examinations') }}</span>
                    <span class="feature">💉 {{ __('translation.clinic.manage_vaccinations') }}</span>
                    <span class="feature">🧪 {{ __('translation.clinic.manage_lab_tests') }}</span>
                    <span class="feature">📊 {{ __('translation.clinic.growth_measurements') }}</span>
                </div>
            </div>
        </div>
        
        <div class="cta">
            <a href="{{ route('login') }}" class="btn btn-primary">{{ __('translation.clinic.login_now') }}</a>
        </div>
        
        <div class="footer">
            <p>{{ __('translation.clinic.email_sent_by', ['app' => config('app.name')]) }}</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. {{ __('translation.all_rights_reserved') }}</p>
        </div>
    </div>
</body>
</html>
