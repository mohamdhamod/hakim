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

                            <!-- Login Link -->
                            <p class="text-muted text-center mt-4 mb-0">
                                {{ __('translation.auth.already_have_account') }}
                                <a href="{{ route('login') }}" class="text-decoration-underline link-offset-3 fw-semibold">
                                    {{ __('translation.auth.sign_in') }}
                                </a>
                            </p>

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

    @push('scripts')
            @include('modules.i18n')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                try { if (window.handleSubmit) handleSubmit('#formRegisterStart'); } catch(e) { console.error(e); }
            });
        </script>
    @endpush
