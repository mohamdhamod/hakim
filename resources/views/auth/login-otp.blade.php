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
                                    <span class="d-flex align-items-center gap-1">
                                        <span class="avatar avatar-xs rounded-circle">
                                            <span class="avatar-title">
                                                <img src="{{ $config_images[\App\Enums\ConfigEnum::LOGO]->image_path ?? asset('images/img.png') }}" alt="{{ __('translation.app.name') }}" class="avatar-img rounded-circle" height="48">
                                            </span>
                                        </span>
                                    </span>
                                </a>

                                <p class="text-muted w-lg-75 mt-3">
                                    {{ __('translation.auth.otp_page_message') }}
                                </p>
                            </div>

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div>
                                <form action="{{ route('login.otp.request') }}" method="POST" class="mb-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="otp_email" class="form-label">
                                            {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                                        </label>
                                             <input type="email" id="otp_email" name="email" class="form-control" placeholder="{{ __('translation.auth.email_placeholder') }}"
                                               value="{{ old('email', session('otp_login_email')) }}" required>
                                        @error('email')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-semibold py-2">
                                            {{ __('translation.auth.send_otp_button') }}
                                        </button>
                                    </div>
                                </form>

                                @if (session('otp_login_email'))
                                    <p class="text-muted small mb-2">
                                        {{ __('translation.auth.otp_check_inbox_hint') }}
                                    </p>
                                    <form action="{{ route('login.otp.verify') }}" method="POST" class="mb-3">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ session('otp_login_email') }}">

                                        <div class="mb-3">
                                            <label for="otp" class="form-label">
                                                {{ __('translation.auth.otp_code') }} <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" id="otp" name="otp" class="form-control" inputmode="numeric" placeholder="{{ __('translation.auth.otp_placeholder') }}" required>
                                            @error('otp')
                                            <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success fw-semibold py-2">
                                                {{ __('translation.auth.verify_otp_button') }}
                                            </button>
                                        </div>
                                    </form>
                                @endif

                                <p class="text-muted text-center mt-4 mb-0">
                                    <a class="text-decoration-underline link-offset-3 fw-semibold" href="{{ route('login') }}">
                                        {{ __('translation.auth.sign_in_with_password') }}
                                    </a>
                                </p>
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
