@extends('layout.home.main')

@section('title', __('translation.ai_assistant.title'))

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.workspace') }}</a></li>
            <li class="breadcrumb-item active">{{ __('translation.ai_assistant.title') }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    {{-- AI Icon --}}
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" style="width: 120px; height: 120px;">
                            <i class="fas fa-robot text-info" style="font-size: 4rem; opacity: 0.5;"></i>
                        </div>
                    </div>

                    {{-- Title --}}
                    <h2 class="fw-bold text-dark mb-3">
                        <i class="fas fa-comments text-info me-2"></i>{{ __('translation.ai_assistant.title') }}
                    </h2>

                    {{-- Description --}}
                    <p class="text-muted fs-5 mb-4 px-lg-5">
                        {{ __('translation.ai_assistant.description') }}
                    </p>

                    {{-- Features Preview --}}
                    <div class="row g-3 mb-4 px-lg-4">
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body py-4">
                                    <i class="fas fa-stethoscope text-success mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="fw-semibold">{{ __('translation.ai_assistant.feature_diagnosis') }}</h6>
                                    <small class="text-muted">{{ __('translation.ai_assistant.feature_diagnosis_desc') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body py-4">
                                    <i class="fas fa-pills text-primary mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="fw-semibold">{{ __('translation.ai_assistant.feature_treatment') }}</h6>
                                    <small class="text-muted">{{ __('translation.ai_assistant.feature_treatment_desc') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border h-100">
                                <div class="card-body py-4">
                                    <i class="fas fa-microphone text-danger mb-3" style="font-size: 2rem;"></i>
                                    <h6 class="fw-semibold">{{ __('translation.ai_assistant.feature_voice') }}</h6>
                                    <small class="text-muted">{{ __('translation.ai_assistant.feature_voice_desc') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Coming Soon Badge --}}
                    <span class="badge bg-info fs-5 px-4 py-2">
                        <i class="fas fa-clock me-2"></i>{{ __('translation.examination.coming_soon') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
