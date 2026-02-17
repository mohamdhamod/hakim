{{-- Workspace appointments list (AJAX partial) --}}
@forelse($appointments as $appointment)
    <div class="appointment-row p-3 border-bottom" data-appointment-id="{{ $appointment->id }}">
        <div class="d-flex align-items-start gap-2">
            {{-- Status Icon --}}
            <div class="rounded-circle bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'completed' ? 'info' : ($appointment->status === 'cancelled' ? 'secondary' : 'warning')) }} bg-opacity-10 p-2 flex-shrink-0">
                <i class="fas fa-user text-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'completed' ? 'info' : ($appointment->status === 'cancelled' ? 'secondary' : 'warning')) }}"></i>
            </div>

            {{-- Info --}}
            <div class="flex-grow-1" style="min-width: 0;">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                    @if($appointment->clinic_patient_id && $appointment->clinicPatient)
                        <a href="{{ route('clinic.patients.show', $appointment->clinicPatient) }}" class="fw-medium text-dark text-truncate text-decoration-none hover-primary">{{ $appointment->patient_display_name }}</a>
                    @else
                        <span class="fw-medium text-dark text-truncate">{{ $appointment->patient_display_name }}</span>
                    @endif
                    <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : ($appointment->status === 'completed' ? 'info' : 'secondary')) }} bg-opacity-10 text-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : ($appointment->status === 'completed' ? 'info' : 'secondary')) }} flex-shrink-0 small">
                        {{ __('translation.clinic_chat.status_' . $appointment->status) }}
                    </span>
                </div>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                    @if($appointment->patient_phone)
                        <span class="mx-1">·</span><i class="fas fa-phone me-1"></i>{{ $appointment->patient_phone }}
                    @endif
                </small>
                @if($appointment->reason)
                    <div class="small text-muted mt-1 text-truncate">
                        <i class="fas fa-comment-medical me-1"></i>{{ Str::limit($appointment->reason, 40) }}
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="d-flex gap-1 mt-2 flex-nowrap">
                    @if($appointment->status === 'pending')
                        <button type="button" class="btn btn-sm btn-success"
                                onclick="confirmAppointment({{ $appointment->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ __('translation.clinic_chat.confirm') }}">
                            <i class="fas fa-check"></i>
                        </button>
                    @endif

                    @if($appointment->status === 'confirmed')
                        <button type="button" class="btn btn-sm btn-info"
                                onclick="completeAppointment({{ $appointment->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ __('translation.clinic_chat.complete') }}">
                            <i class="fas fa-check-double"></i>
                        </button>
                    @endif

                    @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                        <button type="button" class="btn btn-sm btn-danger"
                                onclick="cancelAppointmentModal({{ $appointment->id }})"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ __('translation.clinic_chat.cancel') }}">
                            <i class="fas fa-times"></i>
                        </button>
                    @endif

                    @if(!$appointment->patient_id && !$appointment->clinic_patient_id && $appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                        <button type="button" class="btn btn-sm btn-primary"
                                onclick="createPatientFromAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient_name ?? '') }}', '{{ $appointment->patient_phone ?? '' }}', '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') }}')"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ __('translation.clinic_chat.register_patient') }}">
                            <i class="fas fa-user-plus"></i>
                        </button>
                    @elseif($appointment->clinicPatient)
                        <a href="{{ route('clinic.patients.show', $appointment->clinicPatient) }}"
                           class="btn btn-sm btn-secondary"
                           data-bs-toggle="tooltip" data-bs-placement="top"
                           title="{{ __('translation.clinic.view_patient') }}">
                            <i class="fas fa-eye"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fas fa-calendar-times text-muted fs-1 mb-3" style="opacity: 0.3;"></i>
        <p class="text-muted mb-0">{{ __('translation.clinic_chat.no_appointments_today') }}</p>
    </div>
@endforelse
