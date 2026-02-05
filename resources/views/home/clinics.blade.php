@extends('layout.home.main')

@section('content')
<!-- Search Bar - Compact Card -->
<div class="py-3">
    <div class="container">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <h1 class="h5 fw-semibold mb-0 text-dark">
                        <i class="bi bi-hospital text-primary me-2"></i>{{ __('translation.clinic_home.available_clinics') }}
                    </h1>
                    <span class="badge bg-light text-dark border small">
                        {{ $clinics->total() }} {{ __('translation.clinic_home.clinics_subtitle') }}
                    </span>
                </div>
                
                <form id="searchForm" method="GET">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" id="search_query" name="q" class="form-control border-start-0 ps-0" 
                                       placeholder="{{ __('translation.clinic_home.search_placeholder') }}"
                                       value="{{ request('q') }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-3">
                            <select id="specialty_filter" name="specialty" class="form-select form-select-sm">
                                <option value="">{{ __('translation.clinic_home.all_specialties') }}</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-search me-1"></i>{{ __('translation.common.search') }}
                            </button>
                        </div>
                        <div class="col-12 col-md-2 d-grid d-md-flex justify-content-md-end">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-person-plus me-1"></i>{{ __('translation.auth.sign_up') }}
                                </a>
                            @endguest
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Clinics Grid with Sidebar Filters -->
<section class="py-4">
    <div class="container">
        <div class="row g-4 align-items-start justify-content-center">
            
        
            <!-- Main Content -->
            <div class="col-lg-10">
                <div class="row g-3" id="clinicsContainer">
            @forelse($clinics as $clinic)
                <div class="col-12">
                    <div class="card border-0 shadow-sm h-100 rounded-3">
                        <div class="card-body ">
                            <div class="row g-3">
                                <!-- Clinic Icon/Image -->
                                <div class="col-auto">
                                    <div class="rounded-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center p-3 flex-shrink-0">
                                        <i class="bi bi-hospital text-primary fs-2"></i>
                                    </div>
                                </div>
                                
                                <!-- Clinic Info -->
                                <div class="col">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <h5 class="fw-semibold mb-1 h6">{{ $clinic->name }}</h5>
                                            <p class="text-muted small mb-2 d-flex align-items-center gap-1">
                                                <i class="bi bi-person-badge"></i>
                                                <span>{{ $clinic->doctor->name ?? __('translation.common.unknown') }}</span>
                                            </p>
                                            
                                            @if($clinic->specialty)
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-2 small me-2">
                                                    @if($clinic->specialty->icon)
                                                        <i class="fas {{ $clinic->specialty->icon }} me-1"></i>
                                                    @endif
                                                    {{ $clinic->specialty->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Details Row -->
                                    <div class="row g-2 mb-2">
                                        @if($clinic->address)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-start gap-2 text-muted small">
                                                    <i class="bi bi-geo-alt mt-1 text-primary"></i>
                                                    <span>{{ Str::limit($clinic->address, 50) }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($clinic->phone)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center gap-2 text-muted small">
                                                    <i class="bi bi-telephone text-success"></i>
                                                    <span>{{ $clinic->phone }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Stats & Action -->
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div class="d-flex gap-3">
                                            
                                            <span class="text-muted small">
                                                <i class="bi bi-clock text-warning me-1"></i>
                                                {{ __('translation.common.available') }}
                                            </span>
                                        </div>
                                        
                                        <button type="button" 
                                                class="btn btn-primary btn-sm px-4 book-appointment-btn"
                                                data-clinic-id="{{ $clinic->id }}"
                                                data-clinic-name="{{ $clinic->name }}"
                                                data-doctor-name="{{ $clinic->doctor->name ?? '' }}">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            {{ __('translation.clinic_home.book_appointment') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-hospital display-1 text-muted opacity-25"></i>
                        <h5 class="mt-3 text-muted">{{ __('translation.clinic_home.no_clinics') }}</h5>
                        <p class="text-muted small">{{ __('translation.clinic_home.no_clinics_hint') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($clinics->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $clinics->links() }}
            </div>
        @endif
            </div>
        </div>
    </div>
</section>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
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
                    
                    @guest
                        <!-- Guest Info -->
                        <div class="mb-3">
                            <label class="form-label small fw-medium">{{ __('translation.auth.name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" class="form-control form-control-sm" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">{{ __('translation.auth.phone') }} <span class="text-danger">*</span></label>
                                <input type="tel" name="patient_phone" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">{{ __('translation.auth.email_address') }}</label>
                                <input type="email" name="patient_email" class="form-control form-control-sm">
                            </div>
                        </div>
                    @endguest
                    
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
    
    // Handle book appointment button clicks
    document.querySelectorAll('.book-appointment-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const clinicId = this.dataset.clinicId;
            const clinicName = this.dataset.clinicName;
            const doctorName = this.dataset.doctorName;
            
            document.getElementById('booking_clinic_id').value = clinicId;
            document.getElementById('booking_clinic_name').textContent = clinicName;
            document.getElementById('booking_doctor_name').textContent = doctorName;
            
            bookingModal.show();
        });
    });
    
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
