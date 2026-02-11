@extends('layout.home.main')

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        
        {{-- Welcome Banner --}}
        <div class="card bg-primary text-white border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">{{ __('translation.clinic.welcome') }}, {{ $clinic->doctor->name ?? auth()->user()->name }} 👋</h2>
                        <p class="mb-0 opacity-75">{{ $clinic->display_name }}</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ route('clinic.patients.create') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-person-plus me-2"></i>{{ __('translation.patient.add_new') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Statistics Cards --}}
        <div class="row g-4 mb-4">
            {{-- Total Patients --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-people fs-4 text-primary"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-primary mb-1">{{ number_format($stats['total_patients'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.clinic.stats.total_patients') }}</small>
                    </div>
                </div>
            </div>

            {{-- Today's Examinations --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-success bg-opacity-10 p-3">
                                <i class="bi bi-calendar-check fs-4 text-success"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-success mb-1">{{ number_format($stats['today_examinations'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.clinic.stats.today_examinations') }}</small>
                    </div>
                </div>
            </div>

            {{-- Pending Examinations --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-clock-history fs-4 text-warning"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">{{ number_format($stats['pending_examinations'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.clinic.stats.pending_examinations') }}</small>
                    </div>
                </div>
            </div>

            {{-- This Month --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-info bg-opacity-10 p-3">
                                <i class="bi bi-graph-up fs-4 text-info"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-info mb-1">{{ number_format($stats['this_month_examinations'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.clinic.stats.this_month') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Today's Appointments --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-day text-primary me-2"></i>
                            {{ __('translation.clinic.today_appointments') }}
                        </h5>
                        <a href="{{ route('clinic.examinations.today') }}" class="btn btn-sm btn-outline-primary">
                            {{ __('translation.common.view_all') }}
                        </a>
                    </div>
                    <div class="card-body">
                        @if($todayExaminations->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">{{ __('translation.clinic.no_appointments_today') }}</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($todayExaminations as $exam)
                                    <a href="{{ route('clinic.examinations.show', $exam->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $exam->patient->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $exam->patient->file_number }} - {{ $exam->examination_date->format('H:i') }}</small>
                                        </div>
                                        <span class="badge {{ $exam->status_badge_class }}">{{ $exam->status_label }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Patients --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-plus text-success me-2"></i>
                            {{ __('translation.clinic.recent_patients') }}
                        </h5>
                        <a href="{{ route('clinic.patients.index') }}" class="btn btn-sm btn-outline-success">
                            {{ __('translation.common.view_all') }}
                        </a>
                    </div>
                    <div class="card-body">
                        @if($recentPatients->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">{{ __('translation.clinic.no_patients_yet') }}</p>
                            </div>
                        @else
                            <div class="list-group list-group-flush">
                                @foreach($recentPatients as $patient)
                                    <a href="{{ route('clinic.patients.show', $patient->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $patient->full_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $patient->file_number }} - {{ $patient->phone ?? '-' }}</small>
                                        </div>
                                        <small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Upcoming Follow-ups --}}
        @if($upcomingFollowUps->isNotEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-bell text-warning me-2"></i>
                            {{ __('translation.clinic.upcoming_follow_ups') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('translation.patient.name') }}</th>
                                        <th>{{ __('translation.patient.file_number') }}</th>
                                        <th>{{ __('translation.examination.follow_up_date') }}</th>
                                        <th>{{ __('translation.examination.follow_up_notes') }}</th>
                                        <th>{{ __('translation.common.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingFollowUps as $exam)
                                        <tr>
                                            <td>{{ $exam->patient->full_name }}</td>
                                            <td>{{ $exam->patient->file_number }}</td>
                                            <td>{{ $exam->follow_up_date->format('Y-m-d') }}</td>
                                            <td>{{ Str::limit($exam->follow_up_notes, 50) }}</td>
                                            <td>
                                                <a href="{{ route('clinic.patients.show', $exam->patient_id) }}" class="btn btn-sm btn-success" title="{{ __('translation.examination.new') }}">
                                                    <i class="bi bi-plus"></i> {{ __('translation.examination.new') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
