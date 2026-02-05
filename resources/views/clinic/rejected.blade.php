@extends('layout.home.main')

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('content')
<div class="bg-light min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-x-circle text-danger" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3">{{ __('translation.clinic.rejected_title') }}</h2>
                        <p class="text-muted mb-4">
                            {{ __('translation.clinic.rejected_message') }}
                        </p>
                        
                        <div class="alert alert-danger">
                            <strong>{{ __('translation.clinic.rejection_reason') }}:</strong><br>
                            {{ $clinic->rejection_reason }}
                        </div>

                        <p class="text-muted small">
                            {{ __('translation.clinic.contact_support') }}
                        </p>

                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-house me-2"></i>{{ __('translation.common.back_to_home') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
