@extends('layout.home.main')

@section('title', __('translation.patient.dashboard_title'))

@section('content')
<div class="bg-light min-vh-100 py-4">
    <div class="container">
        
        {{-- Welcome Banner --}}
        <div class="card bg-primary text-white border-0 rounded-4 mb-4 shadow">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">
                            <i class="bi bi-person-heart me-2"></i>
                            {{ __('translation.patient.welcome') }}, {{ auth()->user()->name }} 👋
                        </h2>
                        <p class="mb-0 opacity-75">{{ __('translation.patient.welcome_subtitle') }}</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ route('patient.clinics') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-hospital me-2"></i>{{ __('translation.patient.browse_clinics') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-0">{{ $stats['upcoming_appointments'] }}</h3>
                        <small class="text-muted">{{ __('translation.patient.upcoming') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar2-week text-success fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-0">{{ $stats['total_appointments'] }}</h3>
                        <small class="text-muted">{{ __('translation.patient.total_appointments') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-file-earmark-medical text-info fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-0">{{ $stats['total_examinations'] }}</h3>
                        <small class="text-muted">{{ __('translation.patient.examinations') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="bi bi-hospital text-warning fs-4"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-0">{{ $stats['clinics_visited'] }}</h3>
                        <small class="text-muted">{{ __('translation.patient.clinics_visited') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Upcoming Appointments --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-calendar-event text-primary me-2"></i>
                                {{ __('translation.patient.upcoming_appointments') }}
                            </h5>
                            <a href="{{ route('patient.appointments') }}" class="btn btn-sm btn-outline-primary">
                                {{ __('translation.patient.view_all') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @forelse($upcomingAppointments as $appointment)
                            <div class="border-bottom p-3 hover-bg-light">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; min-width: 50px;">
                                            <i class="bi bi-person-badge text-primary fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">
                                                {{ $appointment->clinic->user->name ?? $appointment->clinic->name }}
                                            </h6>
                                            <p class="text-muted mb-1 small">
                                                <i class="bi bi-tag me-1"></i>
                                                {{ $appointment->clinic->specialty->name ?? '-' }}
                                            </p>
                                            <div class="d-flex gap-3 text-muted small">
                                                <span>
                                                    <i class="bi bi-calendar3 me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                                </span>
                                                <span>
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @if($appointment->status === 'pending')
                                            <span class="badge bg-warning text-dark">{{ __('translation.patient.status_pending') }}</span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="badge bg-success">{{ __('translation.patient.status_confirmed') }}</span>
                                        @endif
                                        <div class="mt-2">
                                            <form action="{{ route('patient.appointments.cancel', ['locale' => app()->getLocale(), 'appointment' => $appointment->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('translation.patient.confirm_cancel') }}')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-calendar-x text-muted fs-1"></i>
                                <p class="text-muted mt-3 mb-0">{{ __('translation.patient.no_upcoming') }}</p>
                                <a href="{{ route('patient.clinics') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-1"></i>
                                    {{ __('translation.patient.book_now') }}
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Medical History --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-file-earmark-medical text-info me-2"></i>
                                {{ __('translation.patient.medical_history') }}
                            </h5>
                            <a href="{{ route('patient.medical-history') }}" class="btn btn-sm btn-outline-info">
                                {{ __('translation.patient.view_all') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @forelse($examinations as $exam)
                            <div class="border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1 fw-semibold">
                                            {{ $exam->clinic->user->name ?? $exam->clinic->name ?? '-' }}
                                        </h6>
                                        <p class="text-muted mb-1 small">
                                            @if($exam->chief_complaint)
                                                {{ Str::limit($exam->chief_complaint, 60) }}
                                            @else
                                                {{ __('translation.patient.general_examination') }}
                                            @endif
                                        </p>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ \Carbon\Carbon::parse($exam->examination_date)->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <span class="badge {{ $exam->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $exam->status === 'completed' ? __('translation.patient.completed') : __('translation.patient.in_progress') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-file-earmark-x text-muted fs-1"></i>
                                <p class="text-muted mt-3 mb-0">{{ __('translation.patient.no_history') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Quick Actions --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-lightning-charge text-warning me-2"></i>
                            {{ __('translation.patient.quick_actions') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('patient.clinics') }}" class="btn btn-primary">
                                <i class="bi bi-calendar-plus me-2"></i>
                                {{ __('translation.patient.book_appointment') }}
                            </a>
                            <a href="{{ route('patient.appointments') }}" class="btn btn-outline-primary">
                                <i class="bi bi-list-check me-2"></i>
                                {{ __('translation.patient.my_appointments') }}
                            </a>
                            <a href="{{ route('patient.medical-history') }}" class="btn btn-outline-info">
                                <i class="bi bi-journal-medical me-2"></i>
                                {{ __('translation.patient.view_records') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Available Clinics --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold">
                                <i class="bi bi-hospital text-success me-2"></i>
                                {{ __('translation.patient.available_clinics') }}
                            </h5>
                            <a href="{{ route('patient.clinics') }}" class="btn btn-sm btn-link p-0">
                                {{ __('translation.patient.view_all') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @forelse($availableClinics as $clinic)
                            <a href="{{ route('patient.clinic.show', ['locale' => app()->getLocale(), 'clinic' => $clinic->id]) }}" 
                               class="d-block border-bottom p-3 text-decoration-none text-dark hover-bg-light">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="bi bi-hospital text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $clinic->user->name ?? $clinic->name }}</h6>
                                        <small class="text-muted">{{ $clinic->specialty->name ?? '-' }}</small>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">{{ __('translation.patient.no_clinics') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-bg-light:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}
</style>
@endsection
