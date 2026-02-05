@extends('layout.auth.main')
@include('layout.extra_meta')
@section('content')
    <div class="auth-box overflow-hidden align-items-center d-flex">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-md-6 col-sm-8">
                    <div class="card">
                        <div class="card-body">
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
                                        <span class="avatar avatar-xs rounded-circle text-bg-dark d-flex align-items-center justify-content-center">
                                            <i class="bi bi-star-fill fs-6 text-white"></i>
                                        </span>
                                        <span class="logo-text text-white fw-bold fs-xl">
                                            {{ __('translation.app.' . config('app.name')) }}
                                        </span>
                                    </span>
                                </a>

                                <p class="text-muted w-lg-75 mt-3">
                                    {{ __('translation.auth.password_email_message') }}
                                </p>
                            </div>

                            <!-- Password Email Form -->
                            <form id="formPasswordEmail" action="{{route('password.email')}}" method="POST">
                                @csrf
                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" id="email" name="email"
                                         placeholder="{{ __('translation.auth.email_placeholder') }}"
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" required
                                           aria-describedby="emailHelp">
                                    @error('email')
                                    <span class="invalid-feedback" id="emailHelp"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                                        {{ __('translation.auth.request_new_password') }}
                                    </button>
                                </div>
                            </form>

                            <p class="text-muted text-center mt-4 mb-0">
                                {{ __('translation.auth.return_to') }}
                                <a class="text-decoration-underline link-offset-3 fw-semibold" href="{{ route('login') }}">
                                    {{ __('translation.auth.sign_in') }}
                                </a>
                            </p>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.handleSubmit) handleSubmit('#formConfirm'); } catch(e) { console.error(e); }
        });
    </script>
@endpush
