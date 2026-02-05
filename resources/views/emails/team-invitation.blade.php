<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Team Invitation') }}</title>
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
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .role-admin { background: #dbeafe; color: #1e40af; }
        .role-editor { background: #dcfce7; color: #166534; }
        .role-reviewer { background: #fef3c7; color: #92400e; }
        .role-viewer { background: #f3f4f6; color: #4b5563; }
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
        .btn-secondary {
            background: white;
            color: #667eea !important;
            border: 2px solid #667eea;
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
        .new-user-box {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .new-user-box h3 {
            color: #065f46;
            margin-top: 0;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
            justify-content: center;
        }
        .feature {
            background: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏥 Hakim Clinics</div>
        </div>
        
        <div class="hero">
            <h1>{{ __('You\'re Invited!') }}</h1>
            <p>{{ __(':name has invited you to join their team', ['name' => $inviter->name]) }}</p>
        </div>
        
        <div class="content">
            <p>{{ __('Hello') }}{{ $isNewUser ? '' : ' ' . ($inviteeEmail) }},</p>
            
            <p>{{ __(':inviter has invited you to collaborate on :workspace team workspace.', ['inviter' => $inviter->name, 'workspace' => $workspace->name]) }}</p>
            
            <div class="invite-details">
                <p><strong>{{ __('Team:') }}</strong> {{ $workspace->name }}</p>
                @if($workspace->description)
                <p><strong>{{ __('Description:') }}</strong> {{ $workspace->description }}</p>
                @endif
                <p><strong>{{ __('Your Role:') }}</strong> 
                    @php
                        $roleNames = [
                            'admin' => __('Admin'),
                            'editor' => __('Editor'),
                            'reviewer' => __('Reviewer'),
                            'viewer' => __('Viewer'),
                        ];
                    @endphp
                    <span class="role-badge role-{{ $role }}">{{ $roleNames[$role] ?? ucfirst($role) }}</span>
                </p>
                <p><strong>{{ __('Invited by:') }}</strong> {{ $inviter->name }} ({{ $inviter->email }})</p>
            </div>

            @if($isNewUser)
            <div class="new-user-box">
                <h3>🎉 {{ __('Welcome to Hakim Clinics!') }}</h3>
                <p>{{ __('You\'re new here! Hakim Clinics helps teams manage clinics, appointments, and patient communication in one place.') }}</p>
                <div class="features">
                    <span class="feature">📅 {{ __('Appointment Booking') }}</span>
                    <span class="feature">🏥 {{ __('Clinic Management') }}</span>
                    <span class="feature">👥 {{ __('Team Collaboration') }}</span>
                    <span class="feature">🧾 {{ __('Patient Records') }}</span>
                </div>
                <p><strong>{{ __('Create your free account to accept the invitation and start collaborating!') }}</strong></p>
            </div>
            @endif
        </div>
        
        <div class="cta">
            @if($isNewUser)
                <a href="{{ $registerUrl }}" class="btn btn-primary">{{ __('Create Account & Join Team') }}</a>
            @else
                <a href="{{ $acceptUrl }}" class="btn btn-primary">{{ __('Accept Invitation') }}</a>
            @endif
        </div>
        
        <p style="color: #6b7280; font-size: 14px; text-align: center;">
            {{ __('If you don\'t want to join this team, you can ignore this email.') }}
        </p>
        
        <div class="footer">
            <p>{{ __('This email was sent by Hakim Clinics') }}</p>
            <p>© {{ date('Y') }} Hakim Clinics. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</body>
</html>
