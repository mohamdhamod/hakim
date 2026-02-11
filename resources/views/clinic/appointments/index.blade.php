@extends('layout.home.main')

@section('title', __('translation.clinic_chat.appointments'))

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.clinic_chat.appointments') }}</h1>
            <p class="text-muted mb-0">{{ __('translation.clinic_chat.manage_appointments') }}</p>
        </div>
        <a href="{{ route('clinic.workspace') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-2"></i>{{ __('translation.common.back') }}
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" data-filter="all">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                        <i class="fas fa-calendar-check text-primary"></i>
                    </div>
                    <h4 class="mb-0 fw-bold">{{ $totalAppointments }}</h4>
                    <small class="text-muted">{{ __('translation.clinic_chat.total') }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" data-filter="pending">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <h4 class="mb-0 fw-bold text-warning">{{ $pendingCount }}</h4>
                    <small class="text-muted">{{ __('translation.clinic_chat.pending') }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" data-filter="confirmed">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                        <i class="fas fa-check text-success"></i>
                    </div>
                    <h4 class="mb-0 fw-bold text-success">{{ $confirmedCount }}</h4>
                    <small class="text-muted">{{ __('translation.clinic_chat.confirmed') }}</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 stat-card" data-filter="completed">
                <div class="card-body text-center py-3">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                        <i class="fas fa-check-double text-info"></i>
                    </div>
                    <h4 class="mb-0 fw-bold text-info">{{ $completedCount }}</h4>
                    <small class="text-muted">{{ __('translation.clinic_chat.status_completed') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('clinic.appointments.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small text-muted">{{ __('translation.common.search') }}</label>
                        <input type="text" name="search" id="searchInput" class="form-control form-control-sm" placeholder="{{ __('translation.clinic_chat.search_patient') }}" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">{{ __('translation.clinic_chat.status') }}</label>
                        <select name="status" id="filterStatus" class="form-select form-select-sm">
                            <option value="all">{{ __('translation.common.all') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('translation.clinic_chat.pending') }}</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('translation.clinic_chat.confirmed') }}</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('translation.clinic_chat.status_completed') }}</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('translation.clinic_chat.status_cancelled') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">{{ __('translation.clinic_chat.date_from') }}</label>
                        <input type="date" name="date_from" id="dateFrom" class="form-control form-control-sm" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted">{{ __('translation.clinic_chat.date_to') }}</label>
                        <input type="date" name="date_to" id="dateTo" class="form-control form-control-sm" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="fas fa-search me-1"></i>{{ __('translation.common.search') }}
                        </button>
                        <a href="{{ route('clinic.appointments.index') }}" class="btn btn-sm btn-outline-secondary" id="resetFilters">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Appointments Grid -->
    <div id="appointmentsContainer">
        @include('clinic.appointments.partials.appointments-grid', ['appointments' => $appointments])
    </div>
</div>

{{-- Cancel Appointment Modal --}}
<div class="modal fade" id="cancelAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-calendar-times me-2"></i>{{ __('translation.clinic_chat.cancel_appointment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">{{ __('translation.clinic_chat.cancel_appointment_confirm') }}</p>
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.clinic_chat.cancellation_reason') }}</label>
                    <textarea id="cancellationReason" class="form-control" rows="3" placeholder="{{ __('translation.clinic_chat.cancellation_reason_placeholder') }}"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="fas fa-times me-2"></i>{{ __('translation.clinic_chat.cancel_appointment') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Complete Appointment Modal --}}
<div class="modal fade" id="completeAppointmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-info">
                    <i class="fas fa-check-double me-2"></i>{{ __('translation.clinic_chat.complete_appointment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">{{ __('translation.clinic_chat.complete_appointment_confirm') }}</p>
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.clinic_chat.completion_notes') }}</label>
                    <textarea id="completionNotes" class="form-control" rows="3" placeholder="{{ __('translation.clinic_chat.completion_notes_placeholder') }}"></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="createExamination">
                    <label class="form-check-label" for="createExamination">
                        {{ __('translation.clinic_chat.create_examination_after') }}
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
                <button type="button" class="btn btn-info" id="confirmCompleteBtn">
                    <i class="fas fa-check-double me-2"></i>{{ __('translation.clinic_chat.mark_completed') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Register Patient Modal --}}
<div class="modal fade" id="registerPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-user-plus me-2"></i>{{ __('translation.clinic_chat.register_as_patient') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">{{ __('translation.clinic_chat.register_patient_desc') }}</p>
                <input type="hidden" id="registerAppointmentId">
                
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.clinic_chat.patient') }}</label>
                    <input type="text" id="registerPatientName" class="form-control" readonly>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('translation.patient.gender') }}</label>
                        <select id="registerPatientGender" class="form-select">
                            <option value="">{{ __('translation.common.select') }}</option>
                            <option value="male">{{ __('translation.patient.male') }}</option>
                            <option value="female">{{ __('translation.patient.female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('translation.patient.birth_year') }}</label>
                        <select id="registerPatientBirthYear" class="form-select">
                            <option value="">{{ __('translation.common.select') }}</option>
                            @for($year = date('Y'); $year >= date('Y') - 100; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('translation.patient.birth_month') }}</label>
                        <select id="registerPatientBirthMonth" class="form-select">
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
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.patient.notes') }} <span class="text-muted small">({{ __('translation.common.optional') }})</span></label>
                    <textarea id="registerPatientNotes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('translation.common.close') }}</button>
                <button type="button" class="btn btn-primary" id="confirmRegisterBtn">
                    <i class="fas fa-user-plus me-2"></i>{{ __('translation.clinic_chat.register_and_link') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentAppointmentId = null;
    
    // Stat cards click to filter
    document.querySelectorAll('.stat-card').forEach(card => {
        card.style.cursor = 'pointer';
        card.addEventListener('click', function() {
            const filter = this.dataset.filter;
            document.getElementById('filterStatus').value = filter;
            document.getElementById('filterForm').submit();
        });
    });
    
    // Cancel Appointment
    function cancelAppointmentModal(appointmentId) {
        currentAppointmentId = appointmentId;
        document.getElementById('cancellationReason').value = '';
        new bootstrap.Modal(document.getElementById('cancelAppointmentModal')).show();
    }
    
    document.getElementById('confirmCancelBtn').addEventListener('click', async function() {
        const reason = document.getElementById('cancellationReason').value;
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.loading") }}';
        
        try {
            const url = `{{ url('/') }}/${document.documentElement.lang}/clinic/appointments/${currentAppointmentId}/cancel`;
            const data = await ApiClient.post(url, { reason: reason });
            
            bootstrap.Modal.getInstance(document.getElementById('cancelAppointmentModal')).hide();
            if (data.success) {
                SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.appointment_cancelled") }}', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                SwalUtil.toast(data.message || 'Error', 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            SwalUtil.toast('{{ __("translation.common.error") }}', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
    
    // Complete Appointment
    function completeAppointment(appointmentId) {
        currentAppointmentId = appointmentId;
        document.getElementById('completionNotes').value = '';
        document.getElementById('createExamination').checked = false;
        new bootstrap.Modal(document.getElementById('completeAppointmentModal')).show();
    }
    
    document.getElementById('confirmCompleteBtn').addEventListener('click', async function() {
        const notes = document.getElementById('completionNotes').value;
        const createExam = document.getElementById('createExamination').checked;
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.loading") }}';
        
        try {
            const url = `{{ url('/') }}/${document.documentElement.lang}/clinic/appointments/${currentAppointmentId}/complete`;
            const data = await ApiClient.post(url, { notes: notes, create_examination: createExam });
            
            bootstrap.Modal.getInstance(document.getElementById('completeAppointmentModal')).hide();
            if (data.success) {
                SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.appointment_completed") }}', 'success');
                if (data.examination_url) {
                    setTimeout(() => window.location.href = data.examination_url, 1000);
                } else {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                SwalUtil.toast(data.message || 'Error', 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            SwalUtil.toast('{{ __("translation.common.error") }}', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
    
    // Confirm Appointment
    async function confirmAppointment(appointmentId) {
        const confirmed = await SwalUtil.confirm(
            '{{ __("translation.clinic_chat.confirm_appointment_question") }}',
            '',
            { confirmButtonText: '{{ __("translation.common.yes") }}', cancelButtonText: '{{ __("translation.common.no") }}' }
        );
        
        if (!confirmed.isConfirmed) return;
        
        try {
            const url = `{{ url('/') }}/${document.documentElement.lang}/clinic/appointments/${appointmentId}/confirm`;
            const data = await ApiClient.post(url);
            
            if (data.success) {
                SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.appointment_confirmed") }}', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                SwalUtil.toast(data.message || 'Error', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            SwalUtil.toast('{{ __("translation.common.error") }}', 'error');
        }
    }
    
    // Register Patient from Appointment
    function createPatientFromAppointment(appointmentId, patientName, patientPhone, appointmentTime) {
        currentAppointmentId = appointmentId;
        document.getElementById('registerAppointmentId').value = appointmentId;
        document.getElementById('registerPatientName').value = patientName || patientPhone;
        document.getElementById('registerPatientGender').value = '';
        document.getElementById('registerPatientBirthYear').value = '';
        document.getElementById('registerPatientBirthMonth').value = '';
        document.getElementById('registerPatientNotes').value = '';
        new bootstrap.Modal(document.getElementById('registerPatientModal')).show();
    }
    
    document.getElementById('confirmRegisterBtn').addEventListener('click', async function() {
        const gender = document.getElementById('registerPatientGender').value;
        const birthYear = document.getElementById('registerPatientBirthYear').value;
        const birthMonth = document.getElementById('registerPatientBirthMonth').value;
        const notes = document.getElementById('registerPatientNotes').value;
        const btn = this;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __("translation.common.loading") }}';
        
        try {
            const url = `{{ url('/') }}/${document.documentElement.lang}/clinic/appointments/${currentAppointmentId}/register-patient`;
            const data = await ApiClient.post(url, {
                gender: gender,
                birth_year: birthYear,
                birth_month: birthMonth,
                notes: notes
            });
            
            bootstrap.Modal.getInstance(document.getElementById('registerPatientModal')).hide();
            if (data.success) {
                SwalUtil.toast(data.message || '{{ __("translation.clinic_chat.patient_registered") }}', 'success');
                if (data.patient_url) {
                    setTimeout(() => window.location.href = data.patient_url, 1000);
                } else {
                    setTimeout(() => location.reload(), 1000);
                }
            } else {
                SwalUtil.toast(data.message || 'Error', 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error:', error);
            SwalUtil.toast('{{ __("translation.common.error") }}', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
</script>
@endpush
