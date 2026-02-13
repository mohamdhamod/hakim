@extends('layout.home.main')
@include('layout.extra_meta')

@section('content')
{{-- Hero Section - Compact & Modern --}}
<section class="hero-section">
    <div class="container py-4 py-md-5">
        <div class="row g-3 g-md-4 align-items-center">
            {{-- Left Content --}}
            <div class="col-lg-6">
                <div class="hero-content">
                    @auth
                        <span class="badge bg-success mb-2">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            {{ __('translation.home.welcome_back') }}, {{ auth()->user()->name }}
                        </span>
                    @else
                        <span class="badge bg-primary mb-2">
                            <i class="bi bi-heart-pulse-fill me-1"></i>
                            {{ __('translation.home.your_health_partner') }}
                        </span>
                    @endauth

                    <h1 class="hero-title mb-2">
                        {{ __('translation.home.welcome_title') }}
                        <span class="text-primary">{{ __('translation.home.welcome_title_highlight') }}</span>
                    </h1>

                    <p class="hero-subtitle text-muted mb-3">
                        {{ __('translation.home.welcome_subtitle') }}
                    </p>

                    {{-- Quick Stats --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="mini-badge">
                            <i class="bi bi-shield-check text-success"></i>
                            <span class="d-none d-sm-inline">{{ __('translation.home.medical_accuracy') }}</span>
                        </span>
                        <span class="mini-badge">
                            <i class="bi bi-lightning-charge text-warning"></i>
                            <span class="d-none d-sm-inline">{{ __('translation.home.fast_booking') }}</span>
                        </span>
                        <span class="mini-badge">
                            <i class="bi bi-clock text-info"></i>
                            <span class="d-none d-sm-inline">{{ __('translation.home.available_24_7') }}</span>
                        </span>
                    </div>

                    {{-- CTA Buttons - Compact --}}
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <a href="{{ route('home.clinics') }}" class="btn btn-primary btn-compact">
                            <i class="bi bi-search me-1"></i>
                            <span class="d-none d-sm-inline">{{ __('translation.home.find_clinic') }}</span>
                            <span class="d-inline d-sm-none">{{ __('translation.home.search') }}</span>
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-compact">
                                <i class="bi bi-person-plus me-1"></i>
                                <span class="d-none d-sm-inline">{{ __('translation.home.join_as_doctor') }}</span>
                                <span class="d-inline d-sm-none">{{ __('translation.home.register') }}</span>
                            </a>
                        @else
                            @if(auth()->user()->isDoctor())
                                <a href="{{ route('clinic.workspace') }}" class="btn btn-success btn-compact">
                                    <i class="bi bi-hospital me-1"></i>
                                    {{ __('translation.home.my_clinic') }}
                                </a>
                            @endif
                        @endguest
                    </div>

                    {{-- Mini Stats --}}
                    <div class="row g-2">
                        <div class="col-4">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-primary-soft text-primary">
                                    <i class="bi bi-hospital"></i>
                                </div>
                                <div class="mini-stat-value">{{ $stats['clinics_count'] ?? 10 }}+</div>
                                <div class="mini-stat-label">{{ __('translation.home.clinics') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-success-soft text-success">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="mini-stat-value">{{ $stats['doctors_count'] ?? 20 }}+</div>
                                <div class="mini-stat-label">{{ __('translation.home.doctors') }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mini-stat">
                                <div class="mini-stat-icon bg-info-soft text-info">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="mini-stat-value">{{ $stats['patients_count'] ?? 100 }}+</div>
                                <div class="mini-stat-label">{{ __('translation.home.patients') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Search Card --}}
            <div class="col-lg-6">
                <div class="search-card">
                    <div class="search-card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-search text-primary me-2"></i>
                            {{ __('translation.home.quick_search') }}
                        </h5>
                        <span class="badge bg-success-soft text-success">
                            <i class="bi bi-lightning-charge-fill me-1"></i>{{ __('translation.home.instant') }}
                        </span>
                    </div>

                    <form action="{{ route('home.clinics') }}" method="GET" class="search-form">
                        <div class="form-group-compact mb-2">
                            <label class="form-label-compact">
                                <i class="bi bi-heart-pulse me-1"></i>{{ __('translation.home.specialty') }}
                            </label>
                            <select id="specialty-select" name="specialty" class="form-select form-select-compact choices-select">
                                <option value="">{{ __('translation.home.all_specialties') }}</option>
                                @if(isset($featuredSpecialties))
                                    @foreach($featuredSpecialties as $specialty)
                                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        

                        <button type="submit" class="btn btn-primary w-100 btn-compact">
                            <i class="bi bi-search me-2"></i>{{ __('translation.home.search_now') }}
                        </button>
                    </form>

                    {{-- Popular Specialties --}}
                    <div class="popular-tags">
                        <small class="text-muted d-block mb-2">{{ __('translation.home.popular_specialties') }}:</small>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('home.clinics') }}" class="tag-link">
                                🦷 {{ __('translation.specialties.dentistry') }}
                            </a>
                            <a href="{{ route('home.clinics') }}" class="tag-link">
                                🩺 {{ __('translation.specialties.dermatology') }}
                            </a>
                            <a href="{{ route('home.clinics') }}" class="tag-link">
                                👶 {{ __('translation.specialties.pediatrics') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Section - Compact Grid --}}
<section class="services-section">
    <div class="container py-4">
        <div class="section-header text-center mb-3">
            <span class="section-badge bg-primary-soft text-primary">
                <i class="bi bi-grid me-1"></i>{{ __('translation.home.our_services') }}
            </span>
            <h2 class="section-title">{{ __('translation.home.how_can_we_help') }}</h2>
        </div>

        <div class="row g-2 g-md-3">
            {{-- Book Appointment --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('home.clinics') }}" class="service-card-compact">
                    <div class="service-icon-compact bg-primary-soft">
                        <i class="bi bi-calendar-check text-primary"></i>
                    </div>
                    <h6 class="service-title-compact">{{ __('translation.home.book_appointment') }}</h6>
                    <p class="service-desc-compact">{{ __('translation.home.book_appointment_desc') }}</p>
                </a>
            </div>

            {{-- Find Doctor --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('home.clinics') }}" class="service-card-compact">
                    <div class="service-icon-compact bg-success-soft">
                        <i class="bi bi-person-badge text-success"></i>
                    </div>
                    <h6 class="service-title-compact">{{ __('translation.home.find_doctor') }}</h6>
                    <p class="service-desc-compact">{{ __('translation.home.find_doctor_desc') }}</p>
                </a>
            </div>

            {{-- Register Clinic --}}
            <div class="col-6 col-md-4">
                @guest
                    <a href="{{ route('register') }}" class="service-card-compact">
                @else
                    <a href="{{ route('clinic.workspace') }}" class="service-card-compact">
                @endguest
                    <div class="service-icon-compact bg-warning-soft">
                        <i class="bi bi-hospital text-warning"></i>
                    </div>
                    <h6 class="service-title-compact">{{ __('translation.home.register_clinic') }}</h6>
                    <p class="service-desc-compact">{{ __('translation.home.register_clinic_desc') }}</p>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Trust Section - Compact --}}
<section class="trust-section bg-light">
    <div class="container py-4">
        <div class="row g-2 g-md-3 text-center">
            <div class="col-6 col-md-3">
                <div class="trust-item-compact">
                    <i class="bi bi-shield-check text-primary"></i>
                    <h6 class="trust-title-compact">{{ __('translation.home.secure_data') }}</h6>
                    <small class="trust-desc-compact">{{ __('translation.home.secure_data_desc') }}</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item-compact">
                    <i class="bi bi-clock-history text-success"></i>
                    <h6 class="trust-title-compact">{{ __('translation.home.always_available') }}</h6>
                    <small class="trust-desc-compact">{{ __('translation.home.always_available_desc') }}</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item-compact">
                    <i class="bi bi-headset text-warning"></i>
                    <h6 class="trust-title-compact">{{ __('translation.home.support') }}</h6>
                    <small class="trust-desc-compact">{{ __('translation.home.support_desc') }}</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item-compact">
                    <i class="bi bi-phone text-info"></i>
                    <h6 class="trust-title-compact">{{ __('translation.home.mobile_friendly') }}</h6>
                    <small class="trust-desc-compact">{{ __('translation.home.mobile_friendly_desc') }}</small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* === Global Compact Styles === */
:root {
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --info-color: #06b6d4;
    --danger-color: #ef4444;
    --text-muted: #64748b;
    --border-radius-sm: 8px;
    --border-radius-md: 12px;
    --spacing-xs: 0.25rem;
    --spacing-sm: 0.5rem;
    --spacing-md: 1rem;
}

/* === Compact Utility Classes === */
.bg-primary-soft { background-color: rgba(59, 130, 246, 0.1) !important; }
.bg-success-soft { background-color: rgba(16, 185, 129, 0.1) !important; }
.bg-warning-soft { background-color: rgba(245, 158, 11, 0.1) !important; }
.bg-info-soft { background-color: rgba(6, 182, 212, 0.1) !important; }

/* === Hero Section === */
.hero-section {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: auto;
}

.hero-title {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: var(--spacing-md);
}

.hero-subtitle {
    font-size: 0.95rem;
    line-height: 1.5;
}

.mini-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    background: white;
    border-radius: 50px;
    font-size: 0.8rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.mini-badge i {
    font-size: 1rem;
}

/* === Compact Buttons === */
.btn-compact {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 50px;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-compact:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* === Mini Stats === */
.mini-stat {
    background: white;
    border-radius: var(--border-radius-sm);
    padding: 0.75rem 0.5rem;
    text-align: center;
    transition: transform 0.2s;
}

.mini-stat:hover {
    transform: translateY(-2px);
}

.mini-stat-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.35rem;
    font-size: 0.95rem;
}

.mini-stat-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.15rem;
}

.mini-stat-label {
    font-size: 0.7rem;
    color: var(--text-muted);
}

/* === Search Card === */
.search-card {
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 1rem;
}

.search-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
}

.search-card-header h5 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
}

.form-group-compact {
    margin-bottom: 0.75rem;
}

.form-label-compact {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 0.35rem;
    display: block;
}

.form-control-compact,
.form-select-compact {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 50px;
    border: 1px solid #e2e8f0;
}

.form-control-compact:focus,
.form-select-compact:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.popular-tags {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.tag-link {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    background: #f8fafc;
    border-radius: 50px;
    font-size: 0.8rem;
    text-decoration: none;
    color: #1e293b;
    transition: all 0.2s;
}

.tag-link:hover {
    background: #e2e8f0;
    transform: translateY(-1px);
}

/* === Section Styles === */
.section-header {
    margin-bottom: 1.5rem;
}

.section-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

/* === Service Cards Compact === */
.service-card-compact {
    display: block;
    background: white;
    border-radius: var(--border-radius-sm);
    padding: 1rem;
    text-align: center;
    text-decoration: none;
    transition: all 0.2s;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.service-card-compact:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.service-icon-compact {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-size: 1.25rem;
}

.service-title-compact {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.35rem;
}

.service-desc-compact {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin: 0;
}

/* === Step Cards Compact === */
.step-card-compact {
    background: white;
    border-radius: var(--border-radius-sm);
    padding: 1rem;
    text-align: center;
    position: relative;
}

.step-number {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, var(--primary-color), #06b6d4);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
}

.step-icon-compact {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-size: 1.5rem;
}

.step-title-compact {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.35rem;
}

.step-desc-compact {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin: 0;
}

/* === Specialty Cards Compact === */
.specialty-card-compact {
    display: block;
    background: white;
    border-radius: var(--border-radius-sm);
    padding: 0.75rem;
    text-align: center;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid #e2e8f0;
}

.specialty-card-compact:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.specialty-icon-compact {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color), #06b6d4);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    color: white;
    font-size: 1.1rem;
}

.specialty-name-compact {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.25rem;
}

.specialty-count-compact {
    font-size: 0.7rem;
    color: var(--text-muted);
}

/* === Doctor CTA Compact === */
.doctor-cta-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, #1d4ed8 100%);
}

.doctor-cta-card-compact {
    background: rgba(255,255,255,0.1);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    backdrop-filter: blur(10px);
}

/* === Trust Items Compact === */
.trust-item-compact {
    padding: 1rem 0.5rem;
}

.trust-item-compact i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.trust-title-compact {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.trust-desc-compact {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* === Mobile Optimizations === */
@media (max-width: 768px) {
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 0.875rem;
    }
    
    .section-title {
        font-size: 1.25rem;
    }
    
    .mini-badge span {
        display: none;
    }
    
    .mini-badge {
        padding: 0.35rem;
        width: 32px;
        height: 32px;
        justify-content: center;
    }
    
    .search-card {
        padding: 0.75rem;
    }
    
    .search-card-header h5 {
        font-size: 0.9rem;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 1.35rem;
    }
    
    .mini-stat {
        padding: 0.5rem 0.25rem;
    }
    
    .mini-stat-icon {
        width: 28px;
        height: 28px;
        font-size: 0.85rem;
    }
    
    .mini-stat-value {
        font-size: 1rem;
    }
    
    .mini-stat-label {
        font-size: 0.65rem;
    }
    
    .service-card-compact,
    .step-card-compact,
    .specialty-card-compact {
        padding: 0.75rem 0.5rem;
    }
    
    .doctor-cta-card-compact {
        padding: 1rem;
    }
}

/* === RTL Support === */
[dir="rtl"] .mini-badge i,
[dir="rtl"] .search-card-header i,
[dir="rtl"] .form-label-compact i {
    margin-right: 0;
    margin-left: 0.35rem;
}

[dir="rtl"] .step-number {
    right: auto;
    left: 0.5rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const specialtySelect = document.getElementById('specialty-select');
    if (specialtySelect && window.loadChoices) {
        window.loadChoices().then(Choices => {
            new Choices(specialtySelect, {
                searchEnabled: true,
                itemSelectText: '',
                shouldSort: false,
                placeholder: true,
                placeholderValue: '{{ __('translation.home.all_specialties') }}',
                searchPlaceholderValue: '{{ __('translation.common.search') }}',
                noResultsText: '{{ __('translation.common.no_results') }}',
                noChoicesText: '{{ __('translation.common.no_results') }}',
            });
        });
    }
});
</script>
@endpush
