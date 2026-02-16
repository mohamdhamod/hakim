{{-- Appointments List --}}
@php
    $statusColors = [
        'pending' => 'warning',
        'confirmed' => 'success',
        'completed' => 'info',
        'cancelled' => 'danger',
    ];
    $statusIcons = [
        'pending' => 'clock',
        'confirmed' => 'check',
        'completed' => 'check-double',
        'cancelled' => 'times',
    ];
@endphp

@if($appointments->count() > 0)
    {{-- Desktop Table --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('translation.clinic_chat.patient') }}</th>
                    <th>{{ __('translation.clinic_chat.date') }}</th>
                    <th>{{ __('translation.clinic_chat.time') }}</th>
                    <th>{{ __('translation.patient.phone') }}</th>
                    <th>{{ __('translation.clinic_chat.status') }}</th>
                    <th>{{ __('translation.clinic_chat.reason') }}</th>
                    <th width="150">{{ __('translation.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    @php $sc = $statusColors[$appointment->status] ?? 'secondary'; @endphp
                    <tr>
                        <td class="small fw-medium">{{ $appointment->patient_display_name }}</td>
                        <td class="small">
                            <i class="fas fa-calendar text-primary me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}
                        </td>
                        <td class="small">
                            <i class="fas fa-clock text-muted me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                        </td>
                        <td class="small">
                            @if($appointment->patient_phone)
                                <span dir="ltr">{{ $appointment->patient_phone }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="small">
                            <span class="badge bg-{{ $sc }} bg-opacity-10 text-{{ $sc }}">
                                {{ __('translation.clinic_chat.status_' . $appointment->status) }}
                            </span>
                        </td>
                        <td class="small">{{ Str::limit($appointment->reason, 30) ?: '-' }}</td>
                        <td>
                            <div class="d-flex gap-1 flex-nowrap">
                                @if($appointment->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="confirmAppointment({{ $appointment->id }})" data-bs-toggle="tooltip" title="{{ __('translation.clinic_chat.confirm') }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                @if($appointment->status === 'confirmed')
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="completeAppointment({{ $appointment->id }})" data-bs-toggle="tooltip" title="{{ __('translation.clinic_chat.complete') }}">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                @endif
                                @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="cancelAppointmentModal({{ $appointment->id }})" data-bs-toggle="tooltip" title="{{ __('translation.clinic_chat.cancel') }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                @if(!$appointment->patient_id && !$appointment->clinic_patient_id && $appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="createPatientFromAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient_name ?? '') }}', '{{ $appointment->patient_phone ?? '' }}', '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') }}')" data-bs-toggle="tooltip" title="{{ __('translation.clinic_chat.register_patient') }}">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                @elseif($appointment->clinicPatient)
                                    <a href="{{ route('clinic.patients.show', $appointment->clinicPatient) }}" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="{{ __('translation.clinic.view_patient') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="d-md-none p-3">
        @foreach($appointments as $appointment)
            @php $sc = $statusColors[$appointment->status] ?? 'secondary'; @endphp
            <div class="card mb-3 border rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center" style="min-width: 0;">
                            <div class="rounded-circle bg-{{ $sc }} bg-opacity-10 p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;">
                                <i class="fas fa-{{ $statusIcons[$appointment->status] ?? 'calendar' }} text-{{ $sc }}"></i>
                            </div>
                            <div style="min-width: 0;">
                                <h6 class="mb-0 fw-bold text-truncate">{{ $appointment->patient_display_name }}</h6>
                            </div>
                        </div>
                        <span class="badge bg-{{ $sc }} bg-opacity-10 text-{{ $sc }} ms-2" style="flex-shrink: 0;">
                            {{ __('translation.clinic_chat.status_' . $appointment->status) }}
                        </span>
                    </div>
                    <div class="small mt-2">
                        <div class="d-flex flex-wrap gap-3 mb-1">
                            <span><i class="fas fa-calendar text-primary me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}</span>
                            <span><i class="fas fa-clock text-muted me-1"></i>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</span>
                        </div>
                        @if($appointment->patient_phone)
                            <div class="mb-1"><i class="fas fa-phone text-muted me-1"></i><span dir="ltr">{{ $appointment->patient_phone }}</span></div>
                        @endif
                        @if($appointment->reason)
                            <div class="text-muted"><i class="fas fa-comment-medical me-1"></i>{{ Str::limit($appointment->reason, 60) }}</div>
                        @endif
                    </div>
                    @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                    <div class="row g-1 mt-3 pt-2 border-top">
                        @if($appointment->status === 'pending')
                            <div class="col">
                                <button class="btn btn-sm btn-success w-100" onclick="confirmAppointment({{ $appointment->id }})">
                                    <i class="fas fa-check me-1"></i>{{ __('translation.clinic_chat.confirm') }}
                                </button>
                            </div>
                        @endif
                        @if($appointment->status === 'confirmed')
                            <div class="col">
                                <button class="btn btn-sm btn-info w-100" onclick="completeAppointment({{ $appointment->id }})">
                                    <i class="fas fa-check-double me-1"></i>{{ __('translation.clinic_chat.complete') }}
                                </button>
                            </div>
                        @endif
                        <div class="col">
                            <button class="btn btn-sm btn-outline-danger w-100" onclick="cancelAppointmentModal({{ $appointment->id }})">
                                <i class="fas fa-times me-1"></i>{{ __('translation.clinic_chat.cancel') }}
                            </button>
                        </div>
                        @if(!$appointment->patient_id && !$appointment->clinic_patient_id)
                            <div class="col-12 mt-1">
                                <button class="btn btn-sm btn-outline-primary w-100" onclick="createPatientFromAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient_name ?? '') }}', '{{ $appointment->patient_phone ?? '' }}', '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') }}')">
                                    <i class="fas fa-user-plus me-1"></i>{{ __('translation.clinic_chat.register_patient') }}
                                </button>
                            </div>
                        @elseif($appointment->clinicPatient)
                            <div class="col-12 mt-1">
                                <a href="{{ route('clinic.patients.show', $appointment->clinicPatient) }}" class="btn btn-sm btn-outline-secondary w-100">
                                    <i class="fas fa-eye me-1"></i>{{ __('translation.clinic.view_patient') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    @elseif($appointment->clinicPatient)
                    <div class="mt-3 pt-2 border-top">
                        <a href="{{ route('clinic.patients.show', $appointment->clinicPatient) }}" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="fas fa-eye me-1"></i>{{ __('translation.clinic.view_patient') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-calendar-times text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
        <h5 class="mt-3 text-muted">{{ __('translation.clinic_chat.no_appointments') }}</h5>
        <p class="text-muted">{{ __('translation.clinic_chat.no_appointments_message') }}</p>
    </div>
@endif

@if($appointments->hasPages())
    <div class="mt-4 px-3">
        {{ $appointments->links() }}
    </div>
@endif
