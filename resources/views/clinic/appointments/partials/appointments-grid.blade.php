{{-- Appointments Grid --}}
<div class="row g-4">
    @forelse($appointments as $appointment)
        @php
            $statusColors = [
                'pending' => 'warning',
                'confirmed' => 'success',
                'completed' => 'info',
                'cancelled' => 'danger',
            ];
            $statusColor = $statusColors[$appointment->status] ?? 'secondary';
            $statusIcons = [
                'pending' => 'clock',
                'confirmed' => 'check',
                'completed' => 'check-double',
                'cancelled' => 'times',
            ];
            $statusIcon = $statusIcons[$appointment->status] ?? 'calendar';
        @endphp
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    {{-- Header with status --}}
                    <div class="d-flex align-items-start mb-3">
                        <div class="avatar-circle bg-{{ $statusColor }}-subtle text-{{ $statusColor }} me-3" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-{{ $statusIcon }} fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $appointment->patient_display_name }}</h6>
                            <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }}">
                                {{ __('translation.clinic_chat.status_' . $appointment->status) }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Appointment info --}}
                    <div class="appointment-info small mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar text-muted me-2" style="width: 16px;"></i>
                            <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</span>
                        </div>
                        
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-muted me-2" style="width: 16px;"></i>
                            <span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</span>
                        </div>
                        
                        @if($appointment->patient_phone)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                            <span dir="ltr">{{ $appointment->patient_phone }}</span>
                        </div>
                        @endif
                        
                        @if($appointment->reason)
                        <div class="d-flex align-items-start">
                            <i class="fas fa-comment-medical text-muted me-2 mt-1" style="width: 16px;"></i>
                            <span class="text-muted">{{ Str::limit($appointment->reason, 50) }}</span>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Actions --}}
                    @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                    <div class="d-flex flex-wrap gap-2">
                        @if($appointment->status === 'pending')
                            <button type="button" class="btn btn-sm btn-success flex-grow-1" onclick="confirmAppointment({{ $appointment->id }})">
                                <i class="fas fa-check me-1"></i>{{ __('translation.clinic_chat.confirm') }}
                            </button>
                        @endif
                        
                        @if($appointment->status === 'confirmed')
                            <button type="button" class="btn btn-sm btn-info flex-grow-1" onclick="completeAppointment({{ $appointment->id }})">
                                <i class="fas fa-check-double me-1"></i>{{ __('translation.clinic_chat.complete') }}
                            </button>
                        @endif
                        
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelAppointmentModal({{ $appointment->id }})">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        @if(!$appointment->patient_id && !$appointment->clinic_patient_id)
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="createPatientFromAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient_name ?? '') }}', '{{ $appointment->patient_phone ?? '' }}', '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') }}')" title="{{ __('translation.clinic_chat.register_as_patient') }}">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        @elseif($appointment->clinicPatient)
                            <a href="{{ route('clinic.patients.show', $appointment->clinicPatient) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('translation.clinic.view_patient') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif
                    </div>
                    @elseif($appointment->clinicPatient)
                    <div class="d-flex gap-2">
                        <a href="{{ route('clinic.patients.show', $appointment->clinicPatient) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="fas fa-eye me-1"></i>{{ __('translation.clinic.view_patient') }}
                        </a>
                    </div>
                    @else
                    <div class="text-center text-muted small py-2">
                        <i class="fas fa-{{ $appointment->status === 'completed' ? 'check-circle text-info' : 'times-circle text-danger' }} me-1"></i>
                        {{ __('translation.clinic_chat.status_' . $appointment->status) }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-calendar-times text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="mt-3 text-muted">{{ __('translation.clinic_chat.no_appointments') }}</h5>
                <p class="text-muted">{{ __('translation.clinic_chat.no_appointments_message') }}</p>
            </div>
        </div>
    @endforelse
</div>

@if($appointments->hasPages())
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
@endif

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
.avatar-circle {
    flex-shrink: 0;
}
.stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.1) !important;
}
.bg-warning-subtle { background-color: rgba(255, 193, 7, 0.15) !important; }
.bg-success-subtle { background-color: rgba(25, 135, 84, 0.15) !important; }
.bg-info-subtle { background-color: rgba(13, 202, 240, 0.15) !important; }
.bg-danger-subtle { background-color: rgba(220, 53, 69, 0.15) !important; }
</style>
