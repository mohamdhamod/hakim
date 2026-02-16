{{-- Workspace appointments list (AJAX partial) --}}
@forelse($appointments as $appointment)
    <div class="appointment-row p-3 border-bottom" data-appointment-id="{{ $appointment->id }}">
        {{-- Appointment Info Row --}}
        <div class="d-flex align-items-center mb-2">
            <div class="rounded-circle bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'completed' ? 'info' : 'warning') }} bg-opacity-10 p-2 me-3">
                <i class="fas fa-user text-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'completed' ? 'info' : 'warning') }}"></i>
            </div>
            <div class="flex-grow-1" style="min-width: 0;">
                <div class="fw-medium text-dark text-truncate">{{ $appointment->patient_display_name }}</div>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                    @if($appointment->patient_phone)
                        <span class="mx-1">•</span><i class="fas fa-phone me-1"></i>{{ $appointment->patient_phone }}
                    @endif
                </small>
                @if($appointment->reason)
                    <div class="small text-muted mt-1">
                        <i class="fas fa-comment-medical me-1"></i>{{ Str::limit($appointment->reason, 50) }}
                    </div>
                @endif
            </div>
            <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : ($appointment->status === 'completed' ? 'info' : 'secondary')) }} bg-opacity-10 text-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : ($appointment->status === 'completed' ? 'info' : 'secondary')) }}">
                {{ __('translation.clinic_chat.status_' . $appointment->status) }}
            </span>
        </div>
        
        {{-- Action Buttons Row --}}
        @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
        <div class="d-flex gap-2 ms-5 ps-2">
            @if($appointment->status === 'pending')
                <button type="button" 
                        class="btn btn-sm btn-outline-success" 
                        onclick="confirmAppointment({{ $appointment->id }})"
                        title="{{ __('translation.clinic_chat.confirm_appointment') }}">
                    <i class="fas fa-check me-1"></i>{{ __('translation.clinic_chat.confirm') }}
                </button>
            @endif
            
            @if($appointment->status === 'confirmed')
                <button type="button" 
                        class="btn btn-sm btn-outline-info" 
                        onclick="completeAppointment({{ $appointment->id }})"
                        title="{{ __('translation.clinic_chat.complete_appointment') }}">
                    <i class="fas fa-check-double me-1"></i>{{ __('translation.clinic_chat.complete') }}
                </button>
            @endif
            
            <button type="button" 
                    class="btn btn-sm btn-outline-danger" 
                    onclick="cancelAppointmentModal({{ $appointment->id }})"
                    title="{{ __('translation.clinic_chat.cancel_appointment') }}">
                <i class="fas fa-times me-1"></i>{{ __('translation.clinic_chat.cancel') }}
            </button>
            
            @if(!$appointment->patient_id)
                <button type="button" 
                        class="btn btn-sm btn-outline-primary" 
                        onclick="createPatientFromAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient_name ?? '') }}', '{{ $appointment->patient_phone ?? '' }}', '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') }}')"
                        title="{{ __('translation.clinic_chat.register_as_patient') }}">
                    <i class="fas fa-user-plus me-1"></i>{{ __('translation.clinic_chat.register_patient') }}
                </button>
            @else
                <a href="{{ route('clinic.patients.show', $appointment->patient) }}" 
                   class="btn btn-sm btn-outline-secondary"
                   title="{{ __('translation.clinic.view_patient') }}">
                    <i class="fas fa-eye me-1"></i>{{ __('translation.clinic.view_patient') }}
                </a>
            @endif
        </div>
        @elseif($appointment->status === 'completed' && $appointment->patient_id)
        <div class="d-flex gap-2 ms-5 ps-2">
            <a href="{{ route('clinic.patients.show', $appointment->patient) }}" 
               class="btn btn-sm btn-outline-secondary"
               title="{{ __('translation.clinic.view_patient') }}">
                <i class="fas fa-eye me-1"></i>{{ __('translation.clinic.view_patient') }}
            </a>
        </div>
        @endif
    </div>
@empty
    <div class="text-center py-5">
        <i class="fas fa-calendar-times text-muted fs-1 mb-3" style="opacity: 0.3;"></i>
        <p class="text-muted mb-0">{{ __('translation.clinic_chat.no_appointments_today') }}</p>
    </div>
@endforelse
