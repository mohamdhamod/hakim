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

                                <p class="text-muted w-lg-75 mt-3">
                                    {{ __('translation.auth.continue_registration_message') }}
                                </p>

                                @if(session('status'))
                                    <div class="alert alert-info py-2 small">{{ session('status') }}</div>
                                @endif
                            </div>

                            <form id="formRegisterComplete" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="registration_token" value="{{ $token }}">

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ $email }}" class="form-control" readonly required>
                                    @error('email')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        {{ __('translation.auth.name') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $prefill_name ?? '') }}" placeholder="{{ __('translation.auth.name') }}" class="form-control" required>
                                    @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="col-lg-12 mb-3">
                                <label for="phone" class="form-label">{{ __('translation.auth.phone') }} <span class="text-muted small">({{ __('translation.common.optional') }})</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>

                                <!-- User Type Selection -->
                                <div class="mb-3">
                                    <label for="user_type" class="form-label">
                                        {{ __('translation.auth.user_type') }} <span class="text-danger">*</span>
                                    </label>
                                    <select id="user_type" name="user_type" class="form-select" required>
                                        <option value="">{{ __('translation.auth.select_user_type') }}</option>
                                        <option value="patient" {{ old('user_type') == 'patient' ? 'selected' : '' }}>
                                            {{ __('translation.auth.user_type_patient') }}
                                        </option>
                                        <option value="doctor" {{ old('user_type') == 'doctor' ? 'selected' : '' }}>
                                            {{ __('translation.auth.user_type_doctor') }}
                                        </option>
                                    </select>
                                    @error('user_type')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <!-- Specialty Selection (Only for Doctors) -->
                                <div class="mb-3" id="specialty_container" style="display: none;">
                                    <label for="specialty_id" class="form-label">
                                        {{ __('translation.auth.specialty') }} <span class="text-danger">*</span>
                                    </label>
                                    <select id="specialty_id" name="specialty_id" class="form-select">
                                        <option value="">{{ __('translation.auth.select_specialty') }}</option>
                                        @foreach(\App\Models\Specialty::active()->ordered()->get() as $specialty)
                                            <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('specialty_id')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <!-- Clinic Address (Only for Doctors) -->
                                <div class="mb-3" id="clinic_address_container" style="display: none;">
                                    <label for="clinic_address" class="form-label">
                                        {{ __('translation.auth.clinic_address') }}
                                    </label>
                                    <textarea id="clinic_address" name="clinic_address" 
                                              class="form-control" rows="2"
                                              placeholder="{{ __('translation.auth.clinic_address_placeholder') }}">{{ old('clinic_address') }}</textarea>
                                    @error('clinic_address')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" id="term_and_policy" name="term_and_policy" class="form-check-input fs-16" value="1" required>
                                        <label for="term_and_policy" class="form-check-label text-muted" style="line-height: 1.6;">
                                            {{ __('translation.auth.i_agree_to') }}
                                            <a href="{{ route('terms-conditions.index') }}" target="_blank" class="text-primary text-decoration-underline fw-semibold">{{ __('translation.auth.terms_and_conditions') }}</a>
                                            {{ __('translation.auth.and') }}
                                            <a href="{{ route('privacy-policy.index') }}" target="_blank" class="text-primary text-decoration-underline fw-semibold">{{ __('translation.auth.privacy_policy') }}</a>
                                        </label>
                                    </div>
                                    @error('term_and_policy')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary fw-semibold py-2">
                                        {{ __('translation.auth.create_account') }}
                                    </button>
                                </div>

                                <p class="text-muted text-center mt-4 mb-0">
                                    {{ __('translation.auth.already_have_account') }}
                                    <a href="{{ route('login') }}" class="text-decoration-underline link-offset-3 fw-semibold">
                                        {{ __('translation.auth.sign_in') }}
                                    </a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-center text-muted mt-4 mb-0">
                {{ __('translation.layout.home.footer.rights', ['year' => date('Y')]) }}
            </p>
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
            try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formRegisterComplete'); } catch(e) { console.error(e); }
            
            // Get elements
            const userTypeSelect = document.getElementById('user_type');
            const specialtyContainer = document.getElementById('specialty_container');
            const clinicAddressContainer = document.getElementById('clinic_address_container');
            const specialtyInput = document.getElementById('specialty_id');

            function toggleClinicFields() {
                const value = userTypeSelect.value;
                if (value === 'doctor') {
                    specialtyContainer.style.display = 'block';
                    clinicAddressContainer.style.display = 'block';
                    specialtyInput.setAttribute('required', 'required');
                } else {
                    specialtyContainer.style.display = 'none';
                    clinicAddressContainer.style.display = 'none';
                    specialtyInput.removeAttribute('required');
                }
            }

            // Initialize Choices.js for user type dropdown
            window.loadChoices().then(function(Choices) {
                new Choices(userTypeSelect, {
                    searchEnabled: false,
                    itemSelectText: '',
                    shouldSort: false,
                    allowHTML: true
                });

                // Initialize Choices.js for specialty dropdown
                new Choices(specialtyInput, {
                    searchEnabled: true,
                    itemSelectText: '',
                    shouldSort: false,
                    allowHTML: true,
                    searchPlaceholderValue: '{{ __("translation.common.search") }}'
                });

                // Listen for change event after Choices.js is initialized
                userTypeSelect.addEventListener('change', toggleClinicFields);
                
                // Check initial state
                toggleClinicFields();
            });
        });
    </script>
@endpush
