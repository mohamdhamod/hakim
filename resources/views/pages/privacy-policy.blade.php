@extends('layout.home.main')

@section('page_title', __('translation.pages.privacy_policy'))

@section('content')
    <main class="py-4 py-md-5">
        <div class="container">
            @php
            
                $title = $content?->title ?? __('translation.pages.privacy_policy');
                $description = $content?->description ?? '';
            @endphp

            <!-- Hero Section -->
            <section class="border rounded-4 p-4 p-md-5 text-center mb-4 mb-md-5 bg-body-tertiary">
                <div class="d-flex justify-content-center mb-3">
                    <div class="about-icon bg-primary-subtle text-primary border" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="bi bi-shield-lock fs-3"></i>
                    </div>
                </div>
                <h1 class="fw-bold mb-2">{{ $title }}</h1>
                <p class="text-muted mb-0">{{ __('translation.pages.last_updated') }}: {{ now()->format('F d, Y') }}</p>
            </section>

            <!-- Content Section -->
            <section class="bg-body border rounded-4 p-4 p-md-5 shadow-sm">
                <div class="policy-content">
                    @if($description)
                        {!! $description !!}
                    @else
                        <p class="text-muted text-center">{{ __('translation.pages.content_not_available') }}</p>
                    @endif
                </div>
            </section>

            <!-- Back to Home -->
            <section class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('translation.pages.back_to_home') }}
                </a>
            </section>
        </div>
    </main>
@endsection

@push('styles')
<style>
    .policy-content {
        line-height: 1.8;
        color: var(--bs-body-color);
    }
    .policy-content p {
        margin-bottom: 1rem;
    }
    .policy-content h5 {
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: var(--bs-heading-color);
    }
    .policy-content ul {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }
    .policy-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush
