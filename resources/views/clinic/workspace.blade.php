@extends('layout.home.main')

@section('title', __('translation.clinic_chat.workspace_title'))

@section('content')
<div class="container-fluid py-3 py-lg-4">
    {{-- Mobile Header --}}
    <div class="d-lg-none mb-3">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 class="mb-0 fw-bold">{{ __('translation.clinic_chat.welcome_doctor') }} {{ auth()->user()->name }}</h5>
                <small class="text-muted">{{ $clinic->display_name }}</small>
            </div>
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        {{-- Sidebar - Desktop --}}
        <div class="col-lg-3 col-xl-3 d-none d-lg-block">
            <div class="card border-0 shadow-sm sticky-top" style="top: 80px;">
                {{-- Clinic Info --}}
                <div class="card-body border-bottom pb-2">
                    <div class="d-flex align-items-center mb-3">
                        @if($clinic->logo)
                            <div class="rounded-circle overflow-hidden me-2" style="width: 40px; height: 40px;">
                                <img src="{{ $clinic->logo_path }}" alt="{{ $clinic->display_name }}" 
                                     class="w-100 h-100 object-fit-cover">
                            </div>
                        @else
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-2">
                                <i class="fas fa-hospital text-primary"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1" style="min-width: 0;">
                            <h6 class="mb-0 text-truncate">{{ $clinic->display_name }}</h6>
                            <span class="badge bg-success bg-opacity-10 text-success small">{{ __('translation.clinic.status.approved') }}</span>
                        </div>
                    </div>
                    
                    {{-- Mini Stats Cards --}}
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="bg-primary bg-opacity-10 rounded p-2 text-center">
                                <div class="h5 mb-0 text-primary fw-bold">{{ $totalTodayAppointments }}</div>
                                <small class="text-muted small">{{ __('translation.clinic_chat.today') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-warning bg-opacity-10 rounded p-2 text-center">
                                <div class="h5 mb-0 text-warning fw-bold">{{ $totalPendingAppointments }}</div>
                                <small class="text-muted small">{{ __('translation.clinic_chat.pending') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 rounded p-2 text-center">
                                <div class="h5 mb-0 text-success fw-bold">{{ $totalPatients }}</div>
                                <small class="text-muted small">{{ __('translation.clinic.patients') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-info bg-opacity-10 rounded p-2 text-center">
                                <div class="h5 mb-0 text-info fw-bold">{{ $totalExaminations }}</div>
                                <small class="text-muted small">{{ __('translation.clinic.examinations') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card-body border-bottom py-3">
                    <h6 class="text-muted small mb-2">
                        <i class="fas fa-bolt me-1"></i>{{ __('translation.clinic_chat.quick_actions') }}
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('clinic.patients.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-user-plus me-1"></i>{{ __('translation.clinic.new_patient') }}
                        </a>
                        <a href="{{ route('clinic.patients.index') }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-users me-1"></i>{{ __('translation.clinic.view_all_patients') }}
                        </a>
                        <a href="{{ route('clinic.ai-assistant') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-robot me-1"></i>{{ __('translation.ai_assistant.title') }}
                        </a>
                    </div>
                </div>

                {{-- Today's Appointments --}}
                <div class="card-body py-2" style="max-height: 350px; overflow-y: auto;">
                    <h6 class="text-muted small mb-2">
                        <i class="fas fa-calendar-day me-1"></i>{{ __('translation.clinic_chat.today') }} ({{ $todayAppointments->count() }})
                    </h6>
                    @forelse($todayAppointments->take(8) as $appointment)
                        <a href="javascript:void(0)" 
                           class="d-flex align-items-center p-2 rounded text-decoration-none mb-1 appointment-item sidebar-item"
                           data-appointment-id="{{ $appointment->id }}">
                            <i class="fas fa-user-circle text-{{ $appointment->status === 'confirmed' ? 'success' : 'warning' }} me-2"></i>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="text-dark small text-truncate">{{ $appointment->patient_display_name }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</small>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted small mb-0">{{ __('translation.clinic_chat.no_appointments_today') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-12 col-lg-9 col-xl-9">
            {{-- Main Content Area --}}
            <div class="row g-3 g-lg-4">

                {{-- Unified Search Card --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-2">
                            <div class="input-group">
                                <span class="input-group-text bg-primary bg-opacity-10 border-0 rounded-start-3 px-3">
                                    <i class="fas fa-search text-primary small"></i>
                                </span>
                                <input type="text" id="workspaceSearch" class="form-control border-0 shadow-none" placeholder="{{ __('translation.patient.search_placeholder') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Patients --}}
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-users text-success me-2"></i>{{ __('translation.clinic.patients') }}
                                </h6>
                                <span class="badge bg-success rounded-pill">{{ $totalPatients }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0" id="workspacePatientsContainer" style="max-height: 350px; overflow-y: auto;">
                            @include('clinic.partials.workspace-patients-list', ['patients' => $patients])
                        </div>
                        @if($totalPatients > 5)
                            <div class="card-footer bg-transparent border-top text-center py-2">
                                <a href="{{ route('clinic.patients.index') }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-eye me-1"></i>{{ __('translation.common.view_all') }} ({{ $totalPatients }})
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                 {{-- Today's Appointments --}}
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-calendar-day text-primary me-2"></i>{{ __('translation.clinic_chat.today_appointments') }}
                            </h6>
                            <span class="badge bg-primary rounded-pill">{{ $totalTodayAppointments }}</span>
                        </div>
                        <div class="card-body p-0" id="workspaceAppointmentsContainer" style="max-height: 450px; overflow-y: auto;">
                            @include('clinic.partials.workspace-appointments-list', ['appointments' => $todayAppointments])
                        </div>
                        <div class="card-footer bg-transparent border-top text-center py-2">
                            <a href="{{ route('clinic.appointments.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-calendar-alt me-1"></i>{{ __('translation.clinic_chat.view_appointments') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Sidebar Offcanvas --}}
<div class="offcanvas offcanvas-{{ app()->getLocale() === 'ar' ? 'end' : 'start' }}" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title d-flex align-items-center">
            @if($clinic->logo)
                <div class="rounded-circle overflow-hidden me-2" style="width: 32px; height: 32px;">
                    <img src="{{ $clinic->logo_path }}" alt="{{ $clinic->display_name }}" 
                         class="w-100 h-100 object-fit-cover">
                </div>
            @else
                <i class="fas fa-hospital text-primary me-2"></i>
            @endif
            {{ $clinic->display_name }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        {{-- Stats --}}
        <div class="p-3 border-bottom bg-light">
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="bg-primary bg-opacity-10 rounded p-2">
                        <div class="h5 mb-0 text-primary fw-bold">{{ $totalTodayAppointments }}</div>
                        <small class="text-muted">{{ __('translation.clinic_chat.today') }}</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-warning bg-opacity-10 rounded p-2">
                        <div class="h5 mb-0 text-warning fw-bold">{{ $totalPendingAppointments }}</div>
                        <small class="text-muted">{{ __('translation.clinic_chat.pending') }}</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-success bg-opacity-10 rounded p-2">
                        <div class="h5 mb-0 text-success fw-bold">{{ $totalPatients }}</div>
                        <small class="text-muted">{{ __('translation.clinic.patients') }}</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-info bg-opacity-10 rounded p-2">
                        <div class="h5 mb-0 text-info fw-bold">{{ $totalExaminations }}</div>
                        <small class="text-muted">{{ __('translation.clinic.examinations') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="p-3 border-bottom">
            <h6 class="text-muted small mb-2">{{ __('translation.clinic_chat.quick_actions') }}</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('clinic.patients.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i>{{ __('translation.clinic.new_patient') }}
                </a>
            
                <a href="{{ route('clinic.patients.index') }}" class="btn btn-outline-info">
                    <i class="fas fa-users me-2"></i>{{ __('translation.clinic.view_all_patients') }}
                </a>
                <a href="{{ route('clinic.ai-assistant') }}" class="btn btn-outline-success">
                    <i class="fas fa-robot me-2"></i>{{ __('translation.ai_assistant.title') }}
                </a>
            </div>
        </div>

        {{-- Today's Appointments --}}
        <div class="p-3">
            <h6 class="text-muted small mb-2">{{ __('translation.clinic_chat.today_appointments') }}</h6>
            @forelse($todayAppointments->take(5) as $appointment)
                <div class="appointment-mobile-row p-2 rounded mb-2 bg-light" data-appointment-id="{{ $appointment->id }}">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user-circle text-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'completed' ? 'info' : 'warning') }} me-2"></i>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="text-dark small text-truncate">{{ $appointment->patient_display_name }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</small>
                        </div>
                        <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : ($appointment->status === 'pending' ? 'warning' : 'secondary') }} bg-opacity-25 small">
                            {{ __('translation.clinic_chat.status_' . $appointment->status) }}
                        </span>
                    </div>
                    @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
                    <div class="d-flex gap-1 flex-wrap">
                        @if($appointment->status === 'pending')
                            <button class="btn btn-xs btn-outline-success py-0 px-2" onclick="confirmAppointment({{ $appointment->id }})" style="font-size: 11px;">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                        @if($appointment->status === 'confirmed')
                            <button class="btn btn-xs btn-outline-info py-0 px-2" onclick="completeAppointment({{ $appointment->id }})" style="font-size: 11px;">
                                <i class="fas fa-check-double"></i>
                            </button>
                        @endif
                        <button class="btn btn-xs btn-outline-danger py-0 px-2" onclick="cancelAppointmentModal({{ $appointment->id }})" style="font-size: 11px;">
                            <i class="fas fa-times"></i>
                        </button>
                        @if(!$appointment->patient_id)
                            <button class="btn btn-xs btn-outline-primary py-0 px-2" onclick="createPatientFromAppointment({{ $appointment->id }}, '{{ addslashes($appointment->patient_name ?? '') }}', '{{ $appointment->patient_phone ?? '' }}', '{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('Y-m-d H:i') }}')" style="font-size: 11px;">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        @endif
                    </div>
                    @endif
                </div>
            @empty
                <p class="text-muted small">{{ __('translation.clinic_chat.no_appointments_today') }}</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Cancel Appointment Modal --}}
<div class="modal fade" id="cancelAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-calendar-times me-2"></i>{{ __('translation.clinic_chat.cancel_appointment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <p class="text-muted mb-3">{{ __('translation.clinic_chat.cancel_appointment_confirm') }}</p>
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.clinic_chat.cancellation_reason') }}</label>
                    <textarea id="cancellationReason" class="form-control" rows="3" placeholder="{{ __('translation.clinic_chat.cancellation_reason_placeholder') }}"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-times me-1"></i>{{ __('translation.clinic_chat.confirm_cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Complete Appointment Modal --}}
<div class="modal fade" id="completeAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-success">
                    <i class="fas fa-check-circle me-2"></i>{{ __('translation.clinic_chat.complete_appointment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <p class="text-muted mb-3">{{ __('translation.clinic_chat.complete_appointment_confirm') }}</p>
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.clinic_chat.completion_notes') }}</label>
                    <textarea id="completionNotes" class="form-control" rows="3" placeholder="{{ __('translation.clinic_chat.completion_notes_placeholder') }}"></textarea>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="createExamination">
                    <label class="form-check-label" for="createExamination">
                        {{ __('translation.clinic_chat.create_examination_after') }}
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
                <button type="button" class="btn btn-success" id="confirmCompleteBtn">
                    <i class="fas fa-check me-1"></i>{{ __('translation.clinic_chat.mark_completed') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Register Patient Modal --}}
<div class="modal fade" id="registerPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-user-plus me-2"></i>{{ __('translation.clinic_chat.register_patient') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <p class="text-muted mb-3">{{ __('translation.clinic_chat.register_patient_desc') }}</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.patient.full_name') }} <span class="text-danger">*</span></label>
                        <input type="text" id="patientName" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.patient.phone') }}</label>
                        <input type="text" id="patientPhone" class="form-control" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('translation.patient.birth_year') }}</label>
                        <select id="patientBirthYear" class="form-select choices-select">
                            <option value="">{{ __('translation.patient.select_year') }}</option>
                            @for($year = date('Y'); $year >= 1920; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('translation.patient.birth_month') }}</label>
                        <select id="patientBirthMonth" class="form-select choices-select">
                            <option value="">{{ __('translation.common.select') }}</option>
                            @foreach([
                                1 => __('translation.months_list.january'),
                                2 => __('translation.months_list.february'),
                                3 => __('translation.months_list.march'),
                                4 => __('translation.months_list.april'),
                                5 => __('translation.months_list.may'),
                                6 => __('translation.months_list.june'),
                                7 => __('translation.months_list.july'),
                                8 => __('translation.months_list.august'),
                                9 => __('translation.months_list.september'),
                                10 => __('translation.months_list.october'),
                                11 => __('translation.months_list.november'),
                                12 => __('translation.months_list.december'),
                            ] as $num => $name)
                                <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('translation.patient.gender') }}</label>
                        <select id="patientGender" class="form-select choices-select">
                            <option value="">{{ __('translation.common.select') }}</option>
                            <option value="male">{{ __('translation.patient.male') }}</option>
                            <option value="female">{{ __('translation.patient.female') }}</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('translation.patient.notes') }}</label>
                        <textarea id="patientNotes" class="form-control" rows="2" placeholder="{{ __('translation.patient.notes_placeholder') }}"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
                <button type="button" class="btn btn-primary" id="confirmRegisterBtn">
                    <i class="fas fa-user-plus me-1"></i>{{ __('translation.clinic_chat.register_and_link') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Confirm Appointment Modal --}}
<div class="modal fade" id="confirmAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-success">
                    <i class="fas fa-calendar-check me-2"></i>{{ __('translation.clinic_chat.confirm_appointment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <div class="text-center py-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-calendar-check text-success fa-2x"></i>
                    </div>
                    <h6 id="confirmPatientName" class="mb-1"></h6>
                    <p class="text-muted mb-0" id="confirmAppointmentTime"></p>
                </div>
                <p class="text-center text-muted">{{ __('translation.clinic_chat.confirm_appointment_question') }}</p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ __('translation.common.no') }}</button>
                <button type="button" class="btn btn-success px-4" id="confirmConfirmBtn">
                    <i class="fas fa-check me-1"></i>{{ __('translation.common.yes') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Request Access Confirmation Modal --}}
<div class="modal fade" id="requestAccessModal" tabindex="-1" aria-labelledby="requestAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="requestAccessModalLabel">{{ __('translation.patient.request_access_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user-lock text-primary fa-2x"></i>
                    </div>
                    <h6 id="requestAccessPatientName" class="mb-1"></h6>
                    <p class="text-muted mb-0 small" id="requestAccessFileNumber"></p>
                </div>
                <p class="text-center text-muted" id="requestAccessMessage"></p>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">{{ __('translation.modal.confirm_delete.cancel') }}</button>
                <button type="button" class="btn btn-primary px-4" id="confirmRequestAccessBtn">
                    <i class="fas fa-check me-1"></i>{{ __('translation.patient.request_access_confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Hover effects */
    .sidebar-item:hover,
    .list-item-hover:hover,
    .quick-action-card:hover {
        background-color: var(--bs-light) !important;
        transition: background-color 0.2s ease;
    }
    .quick-action-card:hover {
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }
</style>
@endpush

@push('scripts')
<script>
// Workspace unified search (patients + appointments)
(function() {
    const searchInput = document.getElementById('workspaceSearch');
    const patientsContainer = document.getElementById('workspacePatientsContainer');
    const appointmentsContainer = document.getElementById('workspaceAppointmentsContainer');
    if (!searchInput || !patientsContainer || !appointmentsContainer) return;

    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(loadResults, 400);
    });

    async function loadResults() {
        const params = new URLSearchParams();
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        const qs = params.toString() ? `?${params}` : '';

        patientsContainer.style.opacity = '0.5';
        appointmentsContainer.style.opacity = '0.5';

        try {
            const [patientsHtml, appointmentsHtml] = await Promise.all([
                ApiClient.getHtml(`{{ route('clinic.workspace.patients') }}${qs}`),
                ApiClient.getHtml(`{{ route('clinic.workspace.appointments') }}${qs}`)
            ]);
            patientsContainer.innerHTML = patientsHtml;
            appointmentsContainer.innerHTML = appointmentsHtml;
        } catch (error) {
            console.error('Workspace search error:', error);
        } finally {
            patientsContainer.style.opacity = '1';
            appointmentsContainer.style.opacity = '1';
        }
    }
})();

// Current appointment data for modals
let currentAppointmentId = null;
let currentAppointmentData = null;

// Modal instances
let cancelModal, completeModal, registerModal, confirmModal, requestAccessModal;

document.addEventListener('DOMContentLoaded', function() {
    cancelModal = new bootstrap.Modal(document.getElementById('cancelAppointmentModal'));
    completeModal = new bootstrap.Modal(document.getElementById('completeAppointmentModal'));
    registerModal = new bootstrap.Modal(document.getElementById('registerPatientModal'));
    confirmModal = new bootstrap.Modal(document.getElementById('confirmAppointmentModal'));
    requestAccessModal = new bootstrap.Modal(document.getElementById('requestAccessModal'));
    
    // Cancel appointment button
    document.getElementById('confirmCancelBtn').addEventListener('click', function() {
        const reason = document.getElementById('cancellationReason').value;
        submitCancelAppointment(currentAppointmentId, reason);
    });
    
    // Complete appointment button
    document.getElementById('confirmCompleteBtn').addEventListener('click', function() {
        const notes = document.getElementById('completionNotes').value;
        const createExam = document.getElementById('createExamination').checked;
        submitCompleteAppointment(currentAppointmentId, notes, createExam);
    });
    
    // Register patient button
    document.getElementById('confirmRegisterBtn').addEventListener('click', function() {
        submitRegisterPatient(currentAppointmentId);
    });
    
    // Confirm appointment button
    document.getElementById('confirmConfirmBtn').addEventListener('click', function() {
        submitConfirmAppointment(currentAppointmentId);
    });

    // Request access button delegation (buttons are loaded via AJAX)
    document.getElementById('workspacePatientsContainer').addEventListener('click', function(e) {
        const btn = e.target.closest('.request-access-btn');
        if (!btn) return;
        e.preventDefault();
        const patientId = btn.dataset.patientId;
        const patientName = btn.dataset.patientName;
        const fileNumber = btn.dataset.patientFileNumber;
        openRequestAccessModal(patientId, patientName, fileNumber);
    });

    // Confirm request access button
    document.getElementById('confirmRequestAccessBtn').addEventListener('click', function() {
        submitRequestAccess();
    });
});

// Open confirm appointment modal
function confirmAppointment(id, patientName = '', appointmentTime = '') {
    currentAppointmentId = id;
    document.getElementById('confirmPatientName').textContent = patientName || '{{ __("translation.clinic_chat.patient") }}';
    document.getElementById('confirmAppointmentTime').textContent = appointmentTime || '';
    confirmModal.show();
}

// Submit confirm appointment
async function submitConfirmAppointment(id) {
    const btn = document.getElementById('confirmConfirmBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("translation.common.loading") }}';
    
    try {
        const url = `{{ route('clinic.appointments.confirm', ['appointment' => '__ID__']) }}`.replace('__ID__', id);
        const data = await ApiClient.post(url);
        
        if (data.success) {
            confirmModal.hide();
            SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.appointment_confirmed") }}', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            SwalUtil.toast(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Open complete appointment modal
function completeAppointment(id) {
    currentAppointmentId = id;
    document.getElementById('completionNotes').value = '';
    document.getElementById('createExamination').checked = false;
    completeModal.show();
}

// Submit complete appointment
async function submitCompleteAppointment(id, notes, createExam) {
    const btn = document.getElementById('confirmCompleteBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("translation.common.loading") }}';
    
    try {
        const url = `{{ route('clinic.appointments.complete', ['appointment' => '__ID__']) }}`.replace('__ID__', id);
        const data = await ApiClient.post(url, { notes: notes, create_examination: createExam });
        
        if (data.success) {
            completeModal.hide();
            SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.appointment_completed") }}', 'success');
            if (createExam && data.examination_url) {
                setTimeout(() => window.location.href = data.examination_url, 1000);
            } else {
                setTimeout(() => window.location.reload(), 1000);
            }
        } else {
            SwalUtil.toast(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Open cancel appointment modal
function cancelAppointmentModal(id) {
    currentAppointmentId = id;
    document.getElementById('cancellationReason').value = '';
    cancelModal.show();
}

// Submit cancel appointment
async function submitCancelAppointment(id, reason) {
    const btn = document.getElementById('confirmCancelBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("translation.common.loading") }}';
    
    try {
        const url = `{{ route('clinic.appointments.cancel', ['appointment' => '__ID__']) }}`.replace('__ID__', id);
        const data = await ApiClient.post(url, { cancellation_reason: reason });
        
        if (data.success) {
            cancelModal.hide();
            SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.appointment_cancelled") }}', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            SwalUtil.toast(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Open register patient modal
function createPatientFromAppointment(id, patientName = '', patientPhone = '') {
    currentAppointmentId = id;
    document.getElementById('patientName').value = patientName;
    document.getElementById('patientPhone').value = patientPhone;
    document.getElementById('patientBirthYear').value = '';
    document.getElementById('patientBirthMonth').value = '';
    document.getElementById('patientGender').value = '';
    document.getElementById('patientNotes').value = '';
    registerModal.show();
}

// Submit register patient
async function submitRegisterPatient(id) {
    const btn = document.getElementById('confirmRegisterBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("translation.common.loading") }}';
    
    const postData = {
        birth_year: document.getElementById('patientBirthYear').value,
        birth_month: document.getElementById('patientBirthMonth').value,
        gender: document.getElementById('patientGender').value,
        notes: document.getElementById('patientNotes').value
    };
    
    try {
        const url = `{{ route('clinic.appointments.register-patient', ['appointment' => '__ID__']) }}`.replace('__ID__', id);
        const data = await ApiClient.post(url, postData);
        
        if (data.success) {
            registerModal.hide();
            SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.patient_registered") }}', 'success');
            if (data.patient_url) {
                setTimeout(() => window.location.href = data.patient_url, 1000);
            } else {
                setTimeout(() => window.location.reload(), 1000);
            }
        } else {
            SwalUtil.toast(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}

// Request access to patient from another clinic
let currentRequestPatientId = null;

function openRequestAccessModal(patientId, patientName, fileNumber) {
    currentRequestPatientId = patientId;
    document.getElementById('requestAccessPatientName').textContent = patientName;
    document.getElementById('requestAccessFileNumber').textContent = fileNumber;

    const message = '{{ __("translation.patient.request_access_message") }}'
        .replace(':name', patientName)
        .replace(':file_number', fileNumber);
    document.getElementById('requestAccessMessage').textContent = message;

    requestAccessModal.show();
}

async function submitRequestAccess() {
    const btn = document.getElementById('confirmRequestAccessBtn');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("translation.common.loading") }}';

    try {
        const data = await ApiClient.post('{{ route("clinic.patients.request-access") }}', {
            patient_id: currentRequestPatientId
        });

        if (data.success) {
            requestAccessModal.hide();
            SwalUtil.toast(data.message || '{{ __("translation.patient.request_access_success") }}', 'success');
            if (data.redirect) {
                setTimeout(() => window.location.href = data.redirect, 1000);
            } else {
                setTimeout(() => window.location.reload(), 1000);
            }
        } else {
            SwalUtil.toast(data.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    } catch (error) {
        SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
    }
}
</script>
@endpush

