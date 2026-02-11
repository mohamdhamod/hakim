@extends('layout.home.main')

@section('title', __('translation.patient.my_appointments_title'))

@section('content')
<div class="bg-light min-vh-100 py-4">
    <div class="container">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-calendar-check text-primary me-2"></i>
                    {{ __('translation.patient.my_appointments') }}
                </h2>
                <p class="text-muted mb-0">{{ __('translation.patient.appointments_subtitle') }}</p>
            </div>
            <a href="{{ route('patient.clinics') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>
                {{ __('translation.patient.book_new') }}
            </a>
        </div>

        {{-- Appointments List --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @forelse($appointments as $appointment)
                    <div class="border-bottom p-4 {{ $loop->even ? 'bg-light' : '' }}">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    @if($appointment->clinic->logo)
                                        <div class="rounded-circle overflow-hidden me-3" style="width: 60px; height: 60px; min-width: 60px;">
                                            <img src="{{ $appointment->clinic->logo_path }}" alt="{{ $appointment->clinic->display_name }}" 
                                                 class="w-100 h-100 object-fit-cover">
                                        </div>
                                    @else
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; min-width: 60px;">
                                            <i class="bi bi-hospital text-primary fs-4"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1 fw-semibold">
                                            {{ $appointment->clinic->display_name }}
                                        </h5>
                                        <p class="text-muted mb-1">
                                            <i class="bi bi-tag me-1"></i>
                                            {{ $appointment->clinic->specialty->name ?? '-' }}
                                        </p>
                                        @if($appointment->reason)
                                            <p class="text-muted small mb-0">
                                                <i class="bi bi-chat-text me-1"></i>
                                                {{ Str::limit($appointment->reason, 50) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-md-center mt-3 mt-md-0">
                                    <div class="fw-semibold">
                                        <i class="bi bi-calendar3 text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-md-end mt-3 mt-md-0">
                                    @switch($appointment->status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark fs-6">
                                                <i class="bi bi-hourglass-split me-1"></i>
                                                {{ __('translation.patient.status_pending') }}
                                            </span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge bg-success fs-6">
                                                <i class="bi bi-check-circle me-1"></i>
                                                {{ __('translation.patient.status_confirmed') }}
                                            </span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-info fs-6">
                                                <i class="bi bi-check2-all me-1"></i>
                                                {{ __('translation.patient.status_completed') }}
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger fs-6">
                                                <i class="bi bi-x-circle me-1"></i>
                                                {{ __('translation.patient.status_cancelled') }}
                                            </span>
                                            @break
                                        @case('no_show')
                                            <span class="badge bg-secondary fs-6">
                                                <i class="bi bi-person-x me-1"></i>
                                                {{ __('translation.patient.status_no_show') }}
                                            </span>
                                            @break
                                    @endswitch

                                    @if(in_array($appointment->status, ['pending', 'confirmed']) && $appointment->appointment_date >= today())
                                        <div class="mt-2">
                                            <form action="{{ route('patient.appointments.cancel', ['locale' => app()->getLocale(), 'appointment' => $appointment->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('translation.patient.confirm_cancel') }}')">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    {{ __('translation.patient.cancel') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">{{ __('translation.patient.no_appointments') }}</h4>
                        <p class="text-muted">{{ __('translation.patient.no_appointments_desc') }}</p>
                        <a href="{{ route('patient.clinics') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle me-1"></i>
                            {{ __('translation.patient.book_first') }}
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if($appointments->hasPages())
            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
