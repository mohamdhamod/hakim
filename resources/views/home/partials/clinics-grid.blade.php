@forelse($clinics as $clinic)
    <div class="col-12">
        <div class="card border-0 shadow-sm h-100 rounded-3">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Clinic Logo/Icon -->
                    <div class="col-auto">
                        @if($clinic->logo)
                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 64px; height: 64px;">
                                <img src="{{ $clinic->logo_path }}" alt="{{ $clinic->display_name }}" 
                                     class="w-100 h-100 object-fit-cover">
                            </div>
                        @else
                            <div class="rounded-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 64px; height: 64px;">
                                <i class="bi bi-hospital text-primary fs-2"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Clinic Info -->
                    <div class="col">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <h5 class="fw-semibold mb-1 h6">{{ $clinic->display_name }}</h5>
                                <p class="text-muted small mb-2 d-flex align-items-center gap-1">
                                    <i class="bi bi-tag"></i>
                                    <span>{{ $clinic->specialty->name ?? __('translation.common.unknown') }}</span>
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
                                    data-clinic-name="{{ $clinic->display_name }}"
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

<!-- Pagination -->
@if($clinics->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $clinics->links() }}
    </div>
@endif
