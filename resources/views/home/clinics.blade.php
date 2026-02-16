@extends('layout.home.main')

@section('content')
<!-- Clinics Grid -->
<section class="py-4">
    <div class="container">
        <div class="row g-4 align-items-start justify-content-center">
            
            <!-- Main Content -->
            <div class="col-lg-10">
                <!-- Search Card -->
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-body p-2">
                        <div class="input-group">
                            <span class="input-group-text bg-primary bg-opacity-10 border-0 rounded-start-3 px-3">
                                <i class="fa fa-search text-primary small"></i>
                            </span>
                            <input type="text" id="clinicSearch" class="form-control border-0 shadow-none"
                                   placeholder="{{ __('translation.clinic_home.search_placeholder') }}"
                                   value="{{ request('q') }}">
                        </div>
                    </div>
                </div>

                <!-- Clinics Container -->
                <div class="row g-3" id="clinicsContainer">
                    @include('home.partials.clinics-grid')
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-0 pb-2">
                <h6 class="modal-title fw-semibold">
                    <i class="bi bi-calendar-plus text-primary me-1"></i>{{ __('translation.clinic_home.book_appointment') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bookingForm" action="{{ route('appointments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="clinic_id" id="booking_clinic_id">
                
                <div class="modal-body pt-0">
                    <!-- Clinic Info -->
                    <div class="bg-light border rounded-2 p-3 mb-3 d-flex align-items-center gap-2">
                        <div class="rounded-2 bg-primary bg-opacity-10 p-2 d-flex align-items-center justify-content-center flex-shrink-0">
                            <i class="bi bi-hospital text-primary"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="fw-semibold small mb-0 text-truncate" id="booking_clinic_name"></h6>
                            <small class="text-muted text-truncate d-block" id="booking_doctor_name"></small>
                        </div>
                    </div>
                    
                    <!-- Date & Time -->
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">{{ __('translation.clinic_home.appointment_date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="appointment_date" class="form-control form-control-sm" 
                                   min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">{{ __('translation.clinic_home.appointment_time') }} <span class="text-danger">*</span></label>
                            <input type="time" name="appointment_time" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    
                    <!-- Reason -->
                    <div class="mb-3">
                        <label class="form-label small fw-medium">{{ __('translation.clinic_home.visit_reason') }}</label>
                        <textarea name="reason" class="form-control form-control-sm" rows="2" 
                                  placeholder="{{ __('translation.clinic_home.visit_reason_placeholder') }}"></textarea>
                    </div>
                </div>
                
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        {{ __('translation.common.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-check-lg me-1"></i>{{ __('translation.clinic_home.confirm_booking') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
    const searchInput = document.getElementById('clinicSearch');
    const container = document.getElementById('clinicsContainer');
    const baseUrl = "{{ route('home.clinics') }}";
    let debounceTimer;

    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
    const loginUrl = "{{ route('login') }}";

    function bindBookingButtons() {
        container.querySelectorAll('.book-appointment-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!isAuthenticated) {
                    window.location.href = loginUrl;
                    return;
                }
                document.getElementById('booking_clinic_id').value = this.dataset.clinicId;
                document.getElementById('booking_clinic_name').textContent = this.dataset.clinicName;
                document.getElementById('booking_doctor_name').textContent = this.dataset.doctorName;
                bookingModal.show();
            });
        });
    }

    function loadClinics() {
        const q = searchInput.value.trim();
        const url = q ? baseUrl + '?q=' + encodeURIComponent(q) : baseUrl;

        container.innerHTML = '<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            container.innerHTML = html;
            bindBookingButtons();
        })
        .catch(() => {
            container.innerHTML = '<div class="col-12 text-center py-4 text-muted">{{ __("translation.common.error") }}</div>';
        });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(loadClinics, 400);
    });

    // Bind initial booking buttons
    bindBookingButtons();

    // Handle form submission
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm && window.handleSubmit) {
        handleSubmit('#bookingForm', function(response) {
            bookingModal.hide();
            bookingForm.reset();
            if (window.showToast) {
                showToast('success', response.message || '{{ __("translation.clinic_home.booking_success") }}');
            }
        });
    }
});
</script>
@endpush
