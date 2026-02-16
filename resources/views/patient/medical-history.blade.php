@extends('layout.home.main')

@section('title', __('translation.patient.medical_history_title'))

@section('content')
<div class="bg-light min-vh-100 py-4">
    <div class="container">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-journal-medical text-info me-2"></i>
                    {{ __('translation.patient.medical_history') }}
                </h2>
                <p class="text-muted mb-0">{{ __('translation.patient.history_subtitle') }}</p>
            </div>
            <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i>
                {{ __('translation.patient.back_dashboard') }}
            </a>
        </div>

        {{-- Examinations List --}}
        <div class="row g-4">
            @forelse($examinations as $exam)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1 fw-semibold">
                                        {{ $exam->clinic->user->name ?? $exam->clinic->name ?? '-' }}
                                    </h5>
                                    <small class="text-muted">
                                        <i class="bi bi-tag me-1"></i>
                                        {{ $exam->clinic->specialty->name ?? '-' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">{{ __('translation.patient.exam_date') }}</small>
                                <span class="fw-semibold">
                                    <i class="bi bi-calendar3 text-primary me-1"></i>
                                    {{ \Carbon\Carbon::parse($exam->examination_date)->format('d/m/Y') }}
                                </span>
                            </div>

                            @if($exam->chief_complaint)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">{{ __('translation.patient.chief_complaint') }}</small>
                                    <p class="mb-0">{{ Str::limit($exam->chief_complaint, 100) }}</p>
                                </div>
                            @endif

                            @if($exam->diagnosis)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">{{ __('translation.patient.diagnosis') }}</small>
                                    <p class="mb-0">{{ Str::limit($exam->diagnosis, 100) }}</p>
                                </div>
                            @endif

                            @if($exam->attachments && $exam->attachments->count() > 0)
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <i class="bi bi-paperclip me-1"></i>
                                        {{ $exam->attachments->count() }} {{ __('translation.patient.attachments') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-file-earmark-x text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">{{ __('translation.patient.no_history') }}</h4>
                            <p class="text-muted">{{ __('translation.patient.no_history_desc') }}</p>
                            <a href="{{ route('home.clinics') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-hospital me-1"></i>
                                {{ __('translation.patient.visit_clinic') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($examinations->hasPages())
            <div class="mt-4">
                {{ $examinations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
