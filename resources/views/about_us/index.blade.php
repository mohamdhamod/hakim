@extends('layout.home.main')
@include('layout.extra_meta')


@section('content')
    <main class="py-4 py-md-5">
        <div class="container">
            @php
                $locale = app()->getLocale();
                $aboutText = function (string $key, string $field = 'title') use ($about, $locale) {
                    $item = $about[$key] ?? null;
                    if (!$item) {
                        return '';
                    }

                    $t = $item->translateOrNew($locale);

                    return $field === 'description'
                        ? (string) ($t->description ?? '')
                        : (string) ($t->title ?? '');
                };
            @endphp

            <!-- 1) Hero Section -->
            <section class="about-hero border rounded-4 p-4 p-md-5 text-center mb-4 mb-md-5">
                <h1 class="fw-bold mb-2">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_HERO, 'title') }}</h1>
                <p class="text-muted mb-0 mx-auto" style="max-width: 56rem;">
                    {{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_HERO, 'description') }}
                </p>
            </section>

            <!-- 2) About Tayeb -->
            <section class="mb-4 mb-md-5">
                <div class="row align-items-center g-3 g-md-4">
                    <div class="col-12 col-lg-7">
                        <div class="d-flex align-items-start gap-3">
                            <div class="about-icon bg-primary-subtle text-primary border">
                                <i class="bi bi-shield-check fs-4"></i>
                            </div>
                            <div>
                                <h2 class="h4 fw-bold mb-2">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_ABOUT_TITLE, 'title') }}</h2>
                                <p class="text-muted mb-2">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_ABOUT_BODY_1, 'description') }}</p>
                                <p class="text-muted mb-0">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_ABOUT_BODY_2, 'description') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-5">
                        <div class="bg-body border rounded-4 p-3 p-md-4 shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div class="about-icon bg-success-subtle text-success border">
                                    <i class="bi bi-lightning-charge fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_HIGHLIGHT, 'title') }}</div>
                                    <div class="text-muted small">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_HIGHLIGHT, 'description') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3) What we offer -->
            <section class="mb-4 mb-md-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h2 class="h4 fw-bold mb-0">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_OFFER_TITLE, 'title') }}</h2>
                </div>

                <!-- Specialties cards -->
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-2 g-md-3">
        @foreach($specialties as $specialty)
        <div class="col">
            <div class="card h-100 text-decoration-none text-body card-hovered border-0 store-category-card">
                <div class="card-body p-2 p-md-3 text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <span class="d-flex align-items-center justify-content-center rounded-circle" 
                              style="width: 50px; height: 50px; background-color: {{ $specialty->color ?? '#4A90D9' }}20;">
                            <i class="fas {{ $specialty->icon ?? 'fa-stethoscope' }} fs-4" style="color: {{ $specialty->color ?? '#4A90D9' }};"></i>
                        </span>
                    </div>
                    <h6 class="card-title mb-1">{{ $specialty->name }}</h6>
                    <p class="card-text text-muted small mb-0">{{ \Illuminate\Support\Str::limit($specialty->description ?? '', 60) }}</p>
                </div>
            </div>
        </div>
        @endforeach

    </div>
            </section>

            <!-- 4) Vision & Mission -->
            <section class="mb-4 mb-md-5">
                <div class="row g-3 g-md-4">
                    <div class="col-12 col-lg-6">
                        <div class="h-100 bg-body border rounded-4 p-3 p-md-4 shadow-sm">
                            <div class="d-flex align-items-start gap-3">
                                <div class="about-icon bg-primary-subtle text-primary border"><i class="bi bi-bullseye fs-4"></i></div>
                                <div>
                                    <h2 class="h5 fw-bold mb-2">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_VISION, 'title') }}</h2>
                                    <p class="text-muted mb-0">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_VISION, 'description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="h-100 bg-body border rounded-4 p-3 p-md-4 shadow-sm">
                            <div class="d-flex align-items-start gap-3">
                                <div class="about-icon bg-success-subtle text-success border"><i class="bi bi-compass fs-4"></i></div>
                                <div>
                                    <h2 class="h5 fw-bold mb-2">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_MISSION, 'title') }}</h2>
                                    <p class="text-muted mb-0">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_MISSION, 'description') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 5) Why Tayeb -->
            <section class="mb-4 mb-md-5">
                <h2 class="h4 fw-bold mb-3">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_TITLE, 'title') }}</h2>
                <div class="row g-3">
                    <div class="col-12 col-lg-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="about-icon bg-primary-subtle text-primary border"><i class="bi bi-ui-checks fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_SIMPLE_UI, 'title') }}</div>
                                <div class="text-muted small">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_SIMPLE_UI, 'description') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="about-icon bg-info-subtle text-info border"><i class="bi bi-megaphone fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_SMART_TOOLS, 'title') }}</div>
                                <div class="text-muted small">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_SMART_TOOLS, 'description') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="about-icon bg-success-subtle text-success border"><i class="bi bi-patch-check fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_PRO_WITHOUT_COMPLEXITY, 'title') }}</div>
                                <div class="text-muted small">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_PRO_WITHOUT_COMPLEXITY, 'description') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="about-icon bg-warning-subtle text-warning border"><i class="bi bi-people fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_FOR_INDIVIDUALS_COMPANIES, 'title') }}</div>
                                <div class="text-muted small">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_FOR_INDIVIDUALS_COMPANIES, 'description') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="d-flex align-items-start gap-3">
                            <div class="about-icon bg-secondary-subtle text-secondary border"><i class="bi bi-headset fs-4"></i></div>
                            <div>
                                <div class="fw-semibold">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_CONTINUOUS_SUPPORT, 'title') }}</div>
                                <div class="text-muted small">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_WHY_CONTINUOUS_SUPPORT, 'description') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 6) CTA -->
            <section class="border rounded-4 p-4 p-md-5 bg-body-tertiary">
                <div class="row align-items-center g-3">
                    <div class="col-12 col-lg-8">
                        <h2 class="h4 fw-bold mb-1">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_CTA, 'title') }}</h2>
                        <p class="text-muted mb-0">{{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_CTA, 'description') }}</p>
                    </div>
                    <div class="col-12 col-lg-4 text-center text-lg-end">
                        <a href="{{ route('home') }}" class="btn btn-primary px-4">
                            {{ $aboutText(\App\Enums\ConfigEnum::ABOUT_US_CTA_BUTTON, 'title') }}
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection



