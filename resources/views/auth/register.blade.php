@extends('layout.auth.main')
@include('layout.extra_meta')


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
                                    <a class="logo-dark text-decoration-none" href="{{ route('home') }}">
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle">
                                    <span class="avatar-title">
                                        <img src="{{ $config_images[\App\Enums\ConfigEnum::LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img rounded-circle" height="48">
                                    </span>
                                </span>
                                    </span>
                                    </a>
                                </a>

                                <a class="logo-light text-decoration-none" href="{{ route('home') }}">
                                <span class="d-flex align-items-center gap-1">
                                    <span class="avatar avatar-xs rounded-circle text-bg-dark d-flex align-items-center justify-content-center">
                                        <i class="bi bi-star-fill fs-6 text-white"></i>
                                    </span>
                                    <span class="logo-text text-white fw-bold fs-xl">
                                        {{ __('translation.app.' . config('app.name', 'Laravel')) }}
                                    </span>
                                </span>
                                </a>

                                <p class="text-muted w-lg-75 mt-3">
                                    {{ __('translation.auth.registration_email_only_message') }}
                                </p>

                                @if(session('status'))
                                    <div class="alert alert-info py-2 small">{{ session('status') }}</div>
                                @endif
                            </div>
                            <!-- Register Form -->
                            <form id="formRegisterStart" action="{{ route('register.start') }}" method="POST" enctype="multipart/form-data">
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

                                <!-- Submit -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                                        {{ __('translation.auth.send_continue_registration_link') }}
                                    </button>
                                </div>

                            <div class="mt-3">
                                        <p class="small text-muted text-center mb-2">
                                            {{ __('translation.auth.register_cta_hint') }}
                                        </p>
                                        <div class="d-grid gap-2">
                                            {{-- Google Sign-Up --}}
                                            @if(config('services.google.client_id'))
                                                <div id="google-signin-wrapper">
                                                    <div id="google-signin-container"></div>
                                                </div>
                                                <div id="webview-google-notice" class="d-none">
                                                    <div class="alert alert-info py-2 mb-2 small">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        {{ __('translation.auth.google_webview_notice') }}
                                                    </div>
                                                </div>
                                                <noscript>
                                                    <a class="btn btn-outline-dark" href="{{ route('oauth.google.redirect') }}">
                                                        <i class="bi bi-google me-1"></i>{{ __('translation.auth.register_cta_google') }}
                                                    </a>
                                                </noscript>
                                            @else
                                                <a class="btn btn-outline-dark" href="{{ route('oauth.google.redirect') }}">
                                                    <i class="bi bi-google me-1"></i>{{ __('translation.auth.register_cta_google') }}
                                                </a>
                                            @endif
                                            <a class="btn btn-outline-primary" href="{{ route('login.otp') }}">{{ __('translation.auth.login_cta_otp') }}</a>
                                            <a class="btn btn-outline-secondary" href="{{ route('password.request') }}">{{ __('translation.auth.login_cta_reset_password') }}</a>
                                            <a class="btn btn-primary" href="{{ route('login') }}">{{ __('translation.auth.sign_in') }}</a>
                                        </div>
                                    </div>

                        </form>
                        </div>


                
                    </div>

                    <!-- Footer -->
                    <p class="text-center text-muted mt-4 mb-0">
                        {{ __('translation.layout.home.footer.rights', ['year' => date('Y')]) }}
                    </p>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 10850; max-width: 360px;">
        <div id="toastContainer" class="toast-container"></div>
    </div>


@endsection

@push('styles')
<style>
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

@push('scripts')
    @include('modules.i18n')

    @if(config('services.google.client_id'))
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        const GOOGLE_CLIENT_ID = @json(config('services.google.client_id'));
        const GOOGLE_TOKEN_URL = @json(route('oauth.google.token'));
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        function isWebView() {
            const ua = navigator.userAgent || navigator.vendor || window.opera;
            if (/wv/.test(ua) || /Android.*Version\/[\d.]+.*Chrome\/[\d.]+ Mobile/.test(ua) && !/Chrome\/[\d.]+ Mobile Safari/.test(ua)) return true;
            if (/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/i.test(ua)) return true;
            if (/\bwv\b|WebView|FBAN|FBAV|Instagram|Twitter|Line\//i.test(ua)) return true;
            return false;
        }

        function handleGoogleCredentialResponse(response) {
            if (!response.credential) return;
            const container = document.getElementById('google-signin-container');
            if (container) container.innerHTML = '<div class="d-flex justify-content-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
            ApiClient.post(GOOGLE_TOKEN_URL, { credential: response.credential })
            .then(data => {
                if (data.success && data.redirect) window.location.href = data.redirect;
                else { SwalHelper.error(data.message || 'Registration failed.'); initializeGoogleSignIn(); }
            })
            .catch(() => { SwalHelper.error('An error occurred.'); initializeGoogleSignIn(); });
        }

        function initializeGoogleSignIn() {
            const wrapper = document.getElementById('google-signin-wrapper');
            const container = document.getElementById('google-signin-container');
            const notice = document.getElementById('webview-google-notice');
            if (isWebView()) { if (wrapper) wrapper.classList.add('d-none'); if (notice) notice.classList.remove('d-none'); return; }
            if (!container || !window.google?.accounts?.id) return;
            container.innerHTML = '';
            google.accounts.id.initialize({
                client_id: GOOGLE_CLIENT_ID,
                callback: handleGoogleCredentialResponse,
                auto_select: false,
                cancel_on_tap_outside: true,
                context: 'signup',
                ux_mode: 'popup',
                itp_support: true
            });
            google.accounts.id.renderButton(container, {
                type: 'standard', theme: 'outline', size: 'large', text: 'signup_with',
                shape: 'rectangular', logo_alignment: 'center',
                width: container.offsetWidth || 300, locale: @json(app()->getLocale())
            });
        }

        window.onload = function() {
            if (isWebView()) {
                const wrapper = document.getElementById('google-signin-wrapper');
                const notice = document.getElementById('webview-google-notice');
                if (wrapper) wrapper.classList.add('d-none');
                if (notice) notice.classList.remove('d-none');
                return;
            }
            if (window.google?.accounts?.id) initializeGoogleSignIn();
            else setTimeout(function() {
                if (window.google?.accounts?.id) initializeGoogleSignIn();
                else {
                    const container = document.getElementById('google-signin-container');
                    if (container) container.innerHTML = '<a class="btn btn-outline-dark w-100" href="{{ route('oauth.google.redirect') }}"><i class="bi bi-google me-1"></i>{{ __('translation.auth.register_cta_google') }}</a>';
                }
            }, 2000);
        };
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.handleSubmit) handleSubmit('#formRegisterStart'); } catch(e) { console.error(e); }
        });
    </script>
@endpush
