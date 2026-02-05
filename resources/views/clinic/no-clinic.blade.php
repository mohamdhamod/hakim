@extends('layout.home.main')

@section('page_title', __('translation.clinic.no_clinic_title') . ' - ' . config('app.name'))

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-hospital text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="mb-3">{{ __('translation.clinic.no_clinic_title') }}</h3>
                        <p class="text-muted mb-4">
                            {{ __('translation.clinic.no_clinic_message') }}
                        </p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-house me-2"></i>
                                {{ __('translation.layout.home.nav_home') }}
                            </a>
                            <a href="{{ route('logout') }}" class="btn btn-outline-secondary"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                {{ __('translation.auth.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
