@extends('layout.main')
@include('layout.extra_meta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start text-start mb-3">
            <div class="col-xxl-12 col-xl-12">
        <span class="badge bg-light text-dark shadow px-2 py-1 mb-2 fs-6">
            <i class="bi bi-people me-1"></i> {{ __('translation.users.page_title') }}
        </span>
                <p class="fs-6 text-muted mb-0">
                    {{ __('translation.users.page_description') }}
                </p>
            </div>
        </div>

        <!-- Create User Card -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('translation.auth.profile') }}</h5>
                        <div class="card-action">
                            <button class="card-action-item border-0 btn" type="button">
                                <i class="bi bi-chevron-up"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="formCreatUser" class="row g-3" action="{{ route('users.update',$model->id) }}"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <!-- First Name -->
                            <div class="col-md-6 ">
                                <label for="name" class="form-label">
                                    {{ __('translation.auth.name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                       placeholder="{{ __('translation.auth.name') }}"
                                       class="form-control @error('name') is-invalid @enderror" required
                                       value="{{ old('name',$model->name) }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <label for="phone" class="form-label">{{ __('translation.auth.phone') }}</label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                       value="{{ old('phone',$model->phone) }}" required>
                            </div>

                            <!-- Country -->
                            <div class="col-md-6">
                                <label for="country_id" class="form-label">
                                    {{ __('translation.auth.country') }} <span class="text-danger">*</span>
                                </label>
                                <select id="country_id" name="country_id" class="form-select select2 @error('country_id') is-invalid @enderror" required>
                                    <option value="">{{ __('translation.auth.select_country') }}</option>
                                    @foreach(\App\Models\Country::where('is_active', 1)->orderedWithPriority()->get() as $country)
                                        <option value="{{ $country->id }}" 
                                                data-flag="{{ $country->flag_url }}" 
                                                {{ old('country_id', $model->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 ">
                                <label for="email" class="form-label">
                                    {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                        placeholder="{{ __('translation.auth.email_placeholder') }}"
                                       class="form-control @error('email') is-invalid @enderror" required
                                       value="{{ old('email',$model->email) }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Roles -->
                            <div class="col-md-6">
                                <label for="roles" class="form-label">{{ __('translation.users.roles') }} <span class="text-danger">*</span></label>
                                <select class="form-control select2 @error('roles') is-invalid @enderror"
                                        id="roles" name="roles[]" required multiple
                                        data-placeholder="{{ __('translation.messages.select_an_option') }}">
                                    @foreach(\App\Enums\RoleEnum::ALL as $role)
                                        <option value="{{$role}}" {{ in_array($role, old('roles', $model->getRoleNames()->toArray())) ? 'selected' : '' }} >{{$role}}</option>
                                    @endforeach
                                </select>
                                @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Upload New Image -->
                            <div class="col-lg-12">
                                <label for="image" class="form-label"><a href="{{ $model->full_path }}" target="_blank"><img src="{{ $model->full_path }}" alt="{{ $model->full_name }}" class="avatar-sm rounded-circle img-thumbnail" style="object-fit: cover;"> </a>{{ __('translation.users.image_optional') }} </label>
                                <input type="file" id="image" name="image" class="form-control" accept=".jpg,.jpeg,.png">
                                <div class="form-text"> {{ __('translation.users.image_optional') }}</div>
                            </div>


                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">{{ __('translation.submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Change Password -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ __('translation.auth.change_password') }}</h5>
                        <div class="card-action">
                            <button type="button" class="card-action-item border-0 btn">
                                <i class="bi bi-chevron-up"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row g-3" id="formUpdatePassword" method="POST" enctype="multipart/form-data"
                              action="{{ route('users.change_password',$model->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="col-lg-12">
                                <label for="password" class="form-label">
                                    {{ __('translation.auth.new_password') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control"
                                           placeholder="{{ __('translation.auth.new_password') }}" required>
                                    <button type="button" class="btn btn-light btn-icon togglePassword" aria-label="Show/Hide Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="password_confirmation" class="form-label">
                                    {{ __('translation.auth.password_confirmation') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                           placeholder="{{ __('translation.auth.password_confirmation') }}" required>
                                    <button type="button" class="btn btn-light btn-icon togglePassword" aria-label="Show/Hide Password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-lg-12 d-flex justify-content-between">
                                <button class="btn btn-primary" type="submit">{{ __('translation.submit') }}</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script>
        // Attach handlers from general.js: password toggle and bind submit handler correctly
        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formCreatUser'); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formUpdatePassword'); } catch(e) { console.error(e); }
            
            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                // Country dropdown with flags
                $('#country_id').select2({
                    placeholder: '{{ __('translation.auth.select_country') }}',
                    allowClear: false,
                    width: '100%',
                    templateResult: formatCountryOption,
                    templateSelection: formatCountryOption
                });
                
                // Other select2 dropdowns
                $('.select2').not('#country_id').select2({
                    placeholder: function() {
                        return $(this).data('placeholder') || '{{ __('translation.common.select') }}';
                    },
                    allowClear: false,
                    width: '100%'
                });
            }
        });

        // Format country option with flag
        function formatCountryOption(country) {
            if (!country.id) {
                return country.text;
            }
            
            var flagUrl = $(country.element).data('flag');
            if (!flagUrl) {
                return country.text;
            }
            
            var $country = $(
                '<span style="display: flex; align-items: center;">' +
                '<img src="' + flagUrl + '" class="img-flag" style="width: 20px; height: 15px; margin-right: 8px; object-fit: cover; border: 1px solid #ddd;" onerror="this.style.display=\'none\'" /> ' +
                '<span>' + country.text + '</span>' +
                '</span>'
            );
            return $country;
        }
    </script>
@endpush
