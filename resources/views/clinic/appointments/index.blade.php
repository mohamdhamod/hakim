@extends('layout.home.main')

@section('title', __('translation.clinic_chat.appointments'))

@section('content')
<div class="container py-4">
    {{-- Search Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-2">
            <div class="input-group">
                <span class="input-group-text bg-primary bg-opacity-10 border-0 rounded-start-3 px-3">
                    <i class="fas fa-search text-primary small"></i>
                </span>
                <input type="text" id="searchAppointments" class="form-control border-0 shadow-none" placeholder="{{ __('translation.clinic_chat.search_patient') }}">
            </div>
        </div>
    </div>

    {{-- Appointments Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-calendar-check text-primary me-2"></i>
                    {{ __('translation.clinic_chat.appointments') }}
                </h5>
                <a href="{{ route('clinic.workspace') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} me-1"></i>{{ __('translation.common.back') }}
                </a>
            </div>
        </div>

        {{-- Results Container --}}
        <div class="card-body p-0" id="appointmentsContainer">
            @include('clinic.appointments.partials.appointments-grid', ['appointments' => $appointments])
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
                    <i class="fas fa-times me-2"></i>{{ __('translation.clinic_chat.cancel_appointment') }}
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
                <h5 class="modal-title text-info">
                    <i class="fas fa-check-double me-2"></i>{{ __('translation.clinic_chat.complete_appointment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-user-plus me-2"></i>{{ __('translation.clinic_chat.register_patient') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <p class="text-muted mb-3">{{ __('translation.clinic_chat.register_patient_desc') }}</p>
                <input type="hidden" id="registerAppointmentId">
                
                <div class="mb-3">
                    <label class="form-label">{{ __('translation.clinic_chat.patient') }}</label>
                    <input type="text" id="registerPatientName" class="form-control" readonly>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('translation.patient.gender') }}</label>
                        <select id="registerPatientGender" class="form-select choices-select">
                            <option value="">{{ __('translation.common.select') }}</option>
                            <option value="male">{{ __('translation.patient.male') }}</option>
                            <option value="female">{{ __('translation.patient.female') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('translation.patient.birth_year') }}</label>
                        <select id="registerPatientBirthYear" class="form-select choices-select">
                            <option value="">{{ __('translation.common.select') }}</option>
                            @for($year = date('Y'); $year >= date('Y') - 100; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('translation.patient.birth_month') }}</label>
                        <select id="registerPatientBirthMonth" class="form-select choices-select">
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
    // AJAX search
    (function() {
        const searchInput = document.getElementById('searchAppointments');
        const container = document.getElementById('appointmentsContainer');
        if (!searchInput || !container) return;

        let debounceTimer;
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(loadAppointments, 400);
        });

        async function loadAppointments() {
            const params = new URLSearchParams();
            if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
            const qs = params.toString() ? `?${params}` : '';

            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';

            try {
                const html = await ApiClient.getHtml(`{{ route('clinic.appointments.index') }}${qs}`);
                container.innerHTML = html;
            } catch (error) {
                console.error('Appointments search error:', error);
            } finally {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }
    })();

    let currentAppointmentId = null;
    
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
