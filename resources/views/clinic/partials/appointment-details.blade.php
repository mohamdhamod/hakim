<div class="appointment-details">
    {{-- Status Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="mb-0">
                    <i class="bi bi-calendar-check text-primary me-2"></i>
                    {{ __('translation.clinic_chat.appointment_info') }}
                </h6>
                <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }} px-3 py-2">
                    {{ __('translation.clinic_chat.status_' . $appointment->status) }}
                </span>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted small">{{ __('translation.clinic_home.appointment_date') }}</label>
                        <p class="mb-0 fw-semibold">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $appointment->appointment_date->format('Y-m-d') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <label class="text-muted small">{{ __('translation.clinic_home.appointment_time') }}</label>
                        <p class="mb-0 fw-semibold">
                            <i class="bi bi-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Patient Info --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">
                <i class="bi bi-person text-primary me-2"></i>
                {{ __('translation.clinic_chat.patient_info') }}
            </h6>
            
            <div class="d-flex align-items-center mb-3">
                <div class="avatar-circle bg-primary-subtle text-primary me-3" style="width: 50px; height: 50px;">
                    <i class="bi bi-person fs-4"></i>
                </div>
                <div>
                    <h5 class="mb-0">{{ $appointment->patient_display_name }}</h5>
                    @if($appointment->patient)
                        <small class="text-muted">{{ __('translation.clinic_chat.registered_patient') }}</small>
                    @else
                        <small class="text-muted">{{ __('translation.clinic_chat.guest_patient') }}</small>
                    @endif
                </div>
            </div>
            
            <div class="row g-3">
                @if($appointment->patient_phone)
                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="text-muted small">{{ __('translation.auth.phone') }}</label>
                            <p class="mb-0">
                                <i class="bi bi-telephone me-1"></i>
                                <a href="tel:{{ $appointment->patient_phone }}">{{ $appointment->patient_phone }}</a>
                            </p>
                        </div>
                    </div>
                @endif
                @if($appointment->patient_email)
                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="text-muted small">{{ __('translation.auth.email_address') }}</label>
                            <p class="mb-0">
                                <i class="bi bi-envelope me-1"></i>
                                <a href="mailto:{{ $appointment->patient_email }}">{{ $appointment->patient_email }}</a>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Reason --}}
    @if($appointment->reason)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="bi bi-chat-text text-primary me-2"></i>
                    {{ __('translation.clinic_home.visit_reason') }}
                </h6>
                <p class="mb-0">{{ $appointment->reason }}</p>
            </div>
        </div>
    @endif
    
    {{-- Actions --}}
    @if(in_array($appointment->status, ['pending', 'confirmed']))
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="bi bi-lightning text-primary me-2"></i>
                    {{ __('translation.common.actions') }}
                </h6>
                
                <div class="d-flex flex-wrap gap-2">
                    @if($appointment->status === 'pending')
                        <button type="button" class="btn btn-success" onclick="confirmAppointment({{ $appointment->id }}, '{{ $appointment->patient_display_name }}', '{{ $appointment->appointment_date->format('Y-m-d') }} {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}')">
                            <i class="bi bi-check-lg me-1"></i>
                            {{ __('translation.clinic_chat.confirm') }}
                        </button>
                    @endif
                    
                    @if($appointment->status === 'confirmed')
                        <button type="button" class="btn btn-primary" onclick="completeAppointment({{ $appointment->id }})">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ __('translation.clinic_chat.complete') }}
                        </button>
                    @endif
                    
                    <button type="button" class="btn btn-outline-danger" onclick="cancelAppointmentModal({{ $appointment->id }})">
                        <i class="bi bi-x-lg me-1"></i>
                        {{ __('translation.common.cancel') }}
                    </button>
                    
                    @if($appointment->patient_id || $appointment->clinic_patient_id)
                        <a href="{{ route('clinic.patients.show', $appointment->patient_id ?? $appointment->clinic_patient_id) }}" class="btn btn-outline-primary">
                            <i class="bi bi-clipboard-plus me-1"></i>
                            {{ __('translation.clinic_chat.new_examination') }}
                        </a>
                    @else
                        <button type="button" class="btn btn-outline-primary" onclick="createPatientFromAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient_name) }}', '{{ $appointment->patient_phone }}')">
                            <i class="bi bi-person-plus me-1"></i>
                            {{ __('translation.clinic_chat.register_patient') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
