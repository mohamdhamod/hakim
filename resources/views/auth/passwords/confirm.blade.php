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
                                    {{ __('translation.auth.confirm_password_message') }}
                                </p>
                            </div>

                            <!-- Login Form -->
                            <div>
                                <form id="formConfirm" action="{{ route('password.confirm') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
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

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <a class="text-decoration-underline link-offset-3 text-muted" href="{{route('password.request')}}">
                                            {{ __('translation.auth.forgot_password') }}
                                        </a>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary fw-semibold py-2">
                                            {{ __('translation.auth.password_confirmation') }}
                                        </button>
                                    </div>
                                </form>

                                <p class="text-muted text-center mt-4 mb-0">
                                    {{ __('translation.auth.new_here') }}
                                    <a class="text-decoration-underline link-offset-3 fw-semibold" href="{{route('login')}}">
                                        {{ __('translation.auth.sign_in') }}
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

@push('scripts')
    <script>
        @include('modules.i18n')
        // Attach handlers from general.js: password toggle and bind submit handler correctly
        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formConfirm'); } catch(e) { console.error(e); }
        });
    </script>
@endpush
