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
                                    {{ __('translation.auth.sign_in_message') }}
                                </p>
                            </div>

                            <!-- Login Form -->
                            <div>
                                <form id="formUpdatePassword" method="POST" enctype="multipart/form-data"
                                      action="{{ route('password.update') }}">
                                    @csrf
                                    <input type="hidden" name="token" value="{{$request->token}}">

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
                                        <div class="input-group">
                                            <input type="password" id="password" name="password"
                                                   class="form-control"
                                                   placeholder="{{ __('translation.auth.password') }}" required>
                                            <button type="button" class="btn btn-light btn-icon togglePassword"  aria-label="Show/Hide Password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Confirm password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            {{ __('translation.auth.password_confirmation') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                   class="form-control"
                                                   placeholder="{{ __('translation.auth.password_confirmation') }}" required>
                                            <button type="button" class="btn btn-light btn-icon togglePassword" aria-label="Show/Hide Password">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-semibold py-2">
                                            {{ __('translation.auth.reset_password') }}
                                        </button>
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
    <script>
        // Attach handlers from general.js: password toggle and bind submit handler correctly
        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formUpdatePassword'); } catch(e) { console.error(e); }
        });
    </script>
@endpush



