@extends('layout.home.main')
@include('layout.extra_meta')
@push('extra_styles')

@endpush
@section('content')
     <div class="mt-4 overflow-hidden align-items-center d-flex">
        <div class="container">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-12 text-start">
            <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                <i class="bi bi-person-gear me-1"></i> {{ __('translation.profile.header.badge') }}
            </span>
                <p class="text-muted mb-0">
                    {{ __('translation.profile.header.description') }}
                </p>
            </div>
        </div>
        <!-- Profile & Password Forms -->
        <div class="row mb-4">
            <!-- Profile Info -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">{{ __('translation.auth.profile') }}</h5>
                        <div class="card-action">
                            <button type="button" class="card-action-item border-0 btn">
                                <i class="bi bi-chevron-up"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="formProfile" class="row g-3" action="{{ route('user-profile-information.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-lg-12">
                                <label for="name" class="form-label">
                                    {{ __('translation.auth.name') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control"
                                       placeholder="{{ __('translation.auth.name') }}" required
                                       value="{{ old('name', auth()->user()->name) }}">
                            </div>

                            <div class="col-lg-12">
                                <label for="phone" class="form-label">{{ __('translation.auth.phone') }} <span class="text-muted small">({{ __('translation.common.optional') }})</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                       value="{{ old('phone', auth()->user()->phone) }}">
                            </div>

                            <div class="col-lg-12">
                                <label for="email" class="form-label">
                                    {{ __('translation.auth.email_address') }} <span class="text-danger">*</span>
                                </label>
                                <input type="email" id="email" name="email" class="form-control"
                                        placeholder="{{ __('translation.auth.email_placeholder') }}" required value="{{ old('email', auth()->user()->email) }}">
                            </div>

                            @if(auth()->user()->hasRole(\App\Enums\RoleEnum::DOCTOR) && auth()->user()->clinic)
                            <div class="col-lg-12">
                                <label for="specialty_id" class="form-label">
                                    {{ __('translation.auth.specialty') }} <span class="text-danger">*</span>
                                </label>
                                <select id="specialty_id" name="specialty_id" class="form-select choices-select" required>
                                    <option value="">{{ __('translation.auth.select_specialty') }}</option>
                                    @foreach(\App\Models\Specialty::active()->ordered()->get() as $specialty)
                                        <option value="{{ $specialty->id }}" 
                                                {{ old('specialty_id', auth()->user()->clinic->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="clinic_address" class="form-label">
                                    {{ __('translation.clinic.address') }}
                                </label>
                                <input type="text" id="clinic_address" name="clinic_address" class="form-control"
                                       placeholder="{{ __('translation.auth.clinic_address_placeholder') }}"
                                       value="{{ old('clinic_address', auth()->user()->clinic->address) }}">
                            </div>

                            <div class="col-lg-12">
                                <label for="clinic_services" class="form-label">
                                    {{ __('translation.clinic.services') }}
                                </label>
                                <select id="clinic_services" name="clinic_services[]" class="form-select choices-select" multiple>
                                    @foreach(\App\Models\ClinicService::active()->ordered()->get() as $service)
                                        <option value="{{ $service->id }}" 
                                                {{ in_array($service->id, old('clinic_services', auth()->user()->clinic->services->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('translation.clinic.services_hint') }}</small>
                                @error('clinic_services')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <label class="form-label">{{ __('translation.clinic.logo') }}</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="clinic-logo-preview rounded-3 overflow-hidden bg-light border">
                                        <img id="clinic_logo_preview" 
                                             src="{{ auth()->user()->clinic->logo_path }}" 
                                             alt="{{ auth()->user()->clinic->name }}"
                                             class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" id="clinic_logo" name="clinic_logo" 
                                               class="form-control form-control-sm" accept="image/*"
                                               onchange="previewClinicLogo(this)">
                                        <small class="text-muted">{{ __('translation.clinic.logo_hint') }}</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">{{ __('translation.submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Team Management Card (Doctors Only) --}}
                @if(auth()->user()->hasRole(\App\Enums\RoleEnum::DOCTOR) && auth()->user()->clinic)
                @php
                    $teamMembers = auth()->user()->clinic->editors()
                        ->withPivot(['is_active', 'invited_at', 'accepted_at'])
                        ->orderBy('clinic_users.created_at', 'desc')
                        ->get();
                @endphp
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people text-primary me-2"></i>{{ __('translation.clinic.team_management') }}
                        </h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#inviteModal">
                            <i class="bi bi-person-plus me-1"></i>{{ __('translation.clinic.invite_member') }}
                        </button>
                    </div>
                    <div class="card-body">
                        @if($teamMembers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0">{{ __('translation.clinic.member') }}</th>
                                            <th class="border-0">{{ __('translation.clinic.status') }}</th>
                                            <th class="border-0 text-center">{{ __('translation.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teamMembers as $member)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold small">{{ $member->name }}</div>
                                                            <small class="text-muted">{{ $member->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($member->pivot->is_active)
                                                        <span class="badge bg-success rounded-pill">{{ __('translation.clinic.active') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill">{{ __('translation.clinic.inactive') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <form action="{{ route('clinic.team.toggle-status', $member->pivot->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm {{ $member->pivot->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $member->pivot->is_active ? __('translation.clinic.deactivate') : __('translation.clinic.activate') }}">
                                                                <i class="bi {{ $member->pivot->is_active ? 'bi-pause' : 'bi-play' }}"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('clinic.team.resend', $member->pivot->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-info" title="{{ __('translation.clinic.resend_invitation') }}">
                                                                <i class="bi bi-envelope"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('clinic.team.remove', $member->pivot->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('translation.clinic.confirm_remove_member') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('translation.clinic.remove_member') }}">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-people text-muted" style="font-size: 2.5rem;"></i>
                                <p class="text-muted mt-2 mb-0">{{ __('translation.clinic.no_team_members') }}</p>
                                <small class="text-muted">{{ __('translation.clinic.invite_team_hint') }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Invite Modal --}}
                <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="inviteModalLabel">
                                    <i class="bi bi-person-plus text-primary me-2"></i>{{ __('translation.clinic.invite_team_member') }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('clinic.team.invite') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p class="text-muted small mb-3">{{ __('translation.clinic.invite_description') }}</p>
                                    
                                    <div class="mb-3">
                                        <label for="invite_name" class="form-label">{{ __('translation.auth.name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="invite_name" name="name" required placeholder="{{ __('translation.clinic.member_name_placeholder') }}">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="invite_email" class="form-label">{{ __('translation.common.email') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="invite_email" name="email" required placeholder="{{ __('translation.clinic.member_email_placeholder') }}">
                                        <div class="form-text">{{ __('translation.clinic.email_invitation_hint') }}</div>
                                    </div>

                                    <div class="alert alert-info small mb-0">
                                        <i class="bi bi-info-circle me-2"></i>{{ __('translation.clinic.invitation_info') }}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-send me-1"></i>{{ __('translation.clinic.send_invitation') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
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
                              action="{{ route('user-password.update') }}">
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
</div>
@endsection

@push('styles')
<style>
    .clinic-logo-preview {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
    @include('modules.i18n')
    <script>
        // Clinic logo preview function
        function previewClinicLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('clinic_logo_preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            try { if (window.bindPasswordToggle) bindPasswordToggle(); } catch(e) { console.error(e); }
            
            try { if (window.handleSubmit) handleSubmit('#formProfile'); } catch(e) { console.error(e); }
            try { if (window.handleSubmit) handleSubmit('#formUpdatePassword'); } catch(e) { console.error(e); }
        });
    </script>
@endpush
