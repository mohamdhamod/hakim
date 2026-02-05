@extends('layout.auth.main')
@include('layout.extra_meta')

@push('styles')
<style>
    /* Google Sign-In button container */
    #google-signin-container {
        display: flex;
        justify-content: center;
        min-height: 44px;
    }
    #google-signin-container > div {
        width: 100% !important;
    }
    #google-signin-container iframe {
        width: 100% !important;
    }
</style>
@endpush

@section('content')
    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Brand / Logo -->
                            <div class="auth-brand mb-4">
                                <a class="logo-dark text-decoration-none" href="{{ route('home') }}">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle">
                                    <span class="avatar-title">
                                            <img src="{{ $config_images[\App\Enums\ConfigEnum::LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img rounded-circle" height="48">

                                        </span>
                                </span>
                                    </span>
                                </a>

                                <a class="logo-light text-decoration-none" href="{{ route('home') }}">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle">
                                    <span class="avatar-title">
                                        <img src="{{ $config_images[\App\Enums\ConfigEnum::DARK_LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img" height="48">
                                        </span>
                                </span>
                                    </span>
                                </a>

                                <p class="text-muted w-lg-75 mt-3">
                                    {{ __('translation.auth.sign_in_message') }}
                                </p>
                            </div>

                            <!-- Login Form -->
                            <div>
                                @if (session('auth_error'))
                                    <div class="alert alert-warning py-2" role="alert">
                                        {{ session('auth_error') }}
                                    </div>
                                @endif
                                <form id="formLogin" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                                 placeholder="{{ __('translation.auth.email_placeholder') }}"
                                               class="form-control" required>
                                        @error('email')
                                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            {{ __('translation.auth.password') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group flex-nowrap">
                                            <input type="password" id="password" name="password"
                                                   class="form-control"
                                                   placeholder="{{ __('translation.auth.password') }}" required>
                                            <button type="button" class="btn btn-light btn-icon togglePassword flex-shrink-0" aria-label="Show/Hide Password" style="min-width: 42px;">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-start align-items-center mb-3">
                                        <div class="form-check d-flex align-items-center gap-2">
                                            <input type="checkbox" id="rememberMe" name="remember" class="form-check-input form-check-input-light fs-14 flex-shrink-0 mt-0" style="min-width: 16px; min-height: 16px;">
                                            <label for="rememberMe" class="form-check-label mb-0">{{ __('translation.auth.remember_me') }}</label>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-semibold py-2">
                                            {{ __('translation.auth.sign_in_button') }}
                                        </button>
                                    </div>

                                    <div class="mt-3">
                                        <p class="small text-muted text-center mb-2">
                                            {{ __('translation.auth.login_cta_hint') }}
                                        </p>
                                        <div class="d-grid gap-2">
                                            {{-- Google Sign-In: Hidden in WebView, shown in regular browsers --}}
                                            @if(config('services.google.client_id'))
                                                <div id="google-signin-wrapper">
                                                    <div id="google-signin-container"></div>
                                                </div>
                                                {{-- Message shown in WebView when Google is blocked --}}
                                                <div id="webview-google-notice" class="d-none">
                                                    <div class="alert alert-info py-2 mb-2 small">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        {{ __('translation.auth.google_webview_notice') }}
                                                    </div>
                                                </div>
                                                <noscript>
                                                    <a class="btn btn-outline-dark" href="{{ route('oauth.google.redirect') }}">
                                                        <i class="bi bi-google me-1"></i>{{ __('translation.auth.login_cta_google') }}
                                                    </a>
                                                </noscript>
                                            @else
                                                <a class="btn btn-outline-dark" href="{{ route('oauth.google.redirect') }}">
                                                    <i class="bi bi-google me-1"></i>{{ __('translation.auth.login_cta_google') }}
                                                </a>
                                            @endif
                                            <a class="btn btn-outline-primary" href="{{ route('login.otp') }}">{{ __('translation.auth.login_cta_otp') }}</a>
                                            <a class="btn btn-outline-secondary" href="{{ route('password.request') }}">{{ __('translation.auth.login_cta_reset_password') }}</a>
                                            <a class="btn btn-primary" href="{{ route('register') }}">{{ __('translation.auth.login_cta_register') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <p class="text-center text-muted mt-4 mb-0">
                        {{ __('translation.layout.home.footer.rights', ['year' => date('Y')]) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('modules.i18n')
    
    {{-- Google Identity Services (GIS) SDK --}}
    @if(config('services.google.client_id'))
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        const GOOGLE_CLIENT_ID = @json(config('services.google.client_id'));
        const GOOGLE_TOKEN_URL = @json(route('oauth.google.token'));
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Detect WebView
        function isWebView() {
            const ua = navigator.userAgent || navigator.vendor || window.opera;
            // Android WebView
            if (/wv/.test(ua) || /Android.*Version\/[\d.]+.*Chrome\/[\d.]+ Mobile/.test(ua) && !/Chrome\/[\d.]+ Mobile Safari/.test(ua)) {
                return true;
            }
            // iOS WebView
            if (/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(ua)) {
                return true;
            }
            // Generic WebView indicators
            if (/\bwv\b|WebView|FBAN|FBAV|Instagram|Twitter|Line\//i.test(ua)) {
                return true;
            }
            return false;
        }

        // Handle Google Sign-In response
        function handleGoogleCredentialResponse(response) {
            if (!response.credential) {
                console.error('No credential received from Google');
                return;
            }

            // Show loading state
            const container = document.getElementById('google-signin-container');
            if (container) {
                container.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            }

            // Send token to Laravel backend for verification
            fetch(GOOGLE_TOKEN_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    credential: response.credential
                }),
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Show error and re-render button
                    alert(data.message || 'Login failed. Please try again.');
                    initializeGoogleSignIn();
                }
            })
            .catch(error => {
                console.error('Google Sign-In error:', error);
                alert('An error occurred. Please try again.');
                initializeGoogleSignIn();
            });
        }

        // Initialize Google Sign-In button
        function initializeGoogleSignIn() {
            const wrapper = document.getElementById('google-signin-wrapper');
            const container = document.getElementById('google-signin-container');
            const notice = document.getElementById('webview-google-notice');
            
            // If WebView detected, hide Google button and show notice
            if (isWebView()) {
                if (wrapper) wrapper.classList.add('d-none');
                if (notice) notice.classList.remove('d-none');
                return;
            }
            
            if (!container || !window.google?.accounts?.id) return;

            // Clear container
            container.innerHTML = '';

            // Initialize Google Identity Services
            google.accounts.id.initialize({
                client_id: GOOGLE_CLIENT_ID,
                callback: handleGoogleCredentialResponse,
                auto_select: false,
                cancel_on_tap_outside: true,
                context: 'signin',
                ux_mode: 'popup',
                itp_support: true
            });

            // Render the button
            google.accounts.id.renderButton(container, {
                type: 'standard',
                theme: 'outline',
                size: 'large',
                text: 'signin_with',
                shape: 'rectangular',
                logo_alignment: 'center',
                width: container.offsetWidth || 300,
                locale: @json(app()->getLocale())
            });
        }

        // Initialize when GIS library loads
        window.onload = function() {
            // Check for WebView first
            if (isWebView()) {
                const wrapper = document.getElementById('google-signin-wrapper');
                const notice = document.getElementById('webview-google-notice');
                if (wrapper) wrapper.classList.add('d-none');
                if (notice) notice.classList.remove('d-none');
                return;
            }
            
            // Wait for GIS library to be ready
            if (window.google?.accounts?.id) {
                initializeGoogleSignIn();
            } else {
                // Retry after a short delay if not loaded yet
                setTimeout(function() {
                    if (window.google?.accounts?.id) {
                        initializeGoogleSignIn();
                    } else {
                        // Fallback: show regular link if GIS fails to load
                        const container = document.getElementById('google-signin-container');
                        if (container) {
                            container.innerHTML = '<a class="btn btn-outline-dark w-100" href="{{ route('oauth.google.redirect') }}"><i class="bi bi-google me-1"></i>{{ __('translation.auth.login_cta_google') }}</a>';
                        }
                    }
                }, 2000);
            }
        };
    </script>
    @endif

    <script>
        // Attach handlers from general.js: password toggle and bind submit handler correctly
        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formLogin'); } catch(e) { console.error(e); }
        });
    </script>
@endpush
