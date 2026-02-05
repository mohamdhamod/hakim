@extends('layout.main')
@include('layout.extra_meta')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        
        {{-- Welcome Banner --}}
        <div class="card bg-primary text-white border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="fw-bold mb-2">{{ __('translation.dashboard.welcome') }}, {{ auth()->user()->name ?? 'Admin' }} 👋</h2>
                        <p class="mb-0 opacity-75">{{ __('translation.dashboard.welcome_subtitle') }}</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="{{ route('clinics.pending') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-hospital me-2"></i>{{ __('translation.dashboard.pending_clinics') ?? 'Pending Clinics' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Statistics Cards --}}
        <div class="row g-4 mb-4">
            {{-- Total Users --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                                <i class="bi bi-people fs-4 text-primary"></i>
                            </div>
                            @if(isset($growthStats['users']))
                                <span class="badge {{ $growthStats['users']['growth'] >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $growthStats['users']['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="bi bi-arrow-{{ $growthStats['users']['growth'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($growthStats['users']['growth']) }}%
                                </span>
                            @endif
                        </div>
                        <h3 class="fw-bold text-primary mb-1">{{ number_format($statistics['users'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.dashboard.stats.total_users') }}</small>
                    </div>
                </div>
            </div>

            {{-- Active Clinics --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-success bg-opacity-10 p-3">
                                <i class="bi bi-hospital fs-4 text-success"></i>
                            </div>
                            @if(isset($growthStats['clinics']))
                                <span class="badge {{ $growthStats['clinics']['growth'] >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $growthStats['clinics']['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="bi bi-arrow-{{ $growthStats['clinics']['growth'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($growthStats['clinics']['growth']) }}%
                                </span>
                            @endif
                        </div>
                        <h3 class="fw-bold text-success mb-1">{{ number_format($statistics['clinics'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.dashboard.stats.active_clinics') ?? 'Active Clinics' }}</small>
                    </div>
                </div>
            </div>

            {{-- Total Appointments --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-info bg-opacity-10 p-3">
                                <i class="bi bi-calendar-check fs-4 text-info"></i>
                            </div>
                            @if(isset($growthStats['appointments']))
                                <span class="badge {{ $growthStats['appointments']['growth'] >= 0 ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $growthStats['appointments']['growth'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="bi bi-arrow-{{ $growthStats['appointments']['growth'] >= 0 ? 'up' : 'down' }}"></i>
                                    {{ abs($growthStats['appointments']['growth']) }}%
                                </span>
                            @endif
                        </div>
                        <h3 class="fw-bold text-info mb-1">{{ number_format($statistics['appointments'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.dashboard.stats.total_appointments') ?? 'Total Appointments' }}</small>
                    </div>
                </div>
            </div>

            {{-- Specialties --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                                <i class="bi bi-hospital fs-4 text-warning"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">{{ number_format($statistics['specialties'] ?? 0) }}</h3>
                        <small class="text-muted">{{ __('translation.dashboard.stats.specialties') }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Analytics Chart --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="card-title fw-semibold mb-1">{{ __('translation.dashboard.content_analytics') }}</h5>
                                <small class="text-muted">{{ __('translation.dashboard.last_30_days') }}</small>
                            </div>
                            <div class="col-md-6">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="text-center bg-primary bg-opacity-10 rounded-3 p-2">
                                            <div class="fs-5 fw-bold text-primary">{{ number_format($statistics['today_contents'] ?? 0) }}</div>
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">{{ __('translation.dashboard.today') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center bg-success bg-opacity-10 rounded-3 p-2">
                                            <div class="fs-5 fw-bold text-success">{{ number_format($statistics['week_contents'] ?? 0) }}</div>
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">{{ __('translation.dashboard.this_week') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center bg-info bg-opacity-10 rounded-3 p-2">
                                            <div class="fs-5 fw-bold text-info">{{ number_format($statistics['month_contents'] ?? 0) }}</div>
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">{{ __('translation.dashboard.this_month') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 280px; position: relative;">
                            <canvas id="contentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- System Health & Performance --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-success"></i>
                            <h5 class="card-title fw-semibold mb-0">{{ __('translation.dashboard.system_health') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="text-center p-3 rounded-3 bg-success bg-opacity-10">
                                    <div class="fs-3 fw-bold text-success">{{ $systemHealth['success_rate'] ?? 98.5 }}%</div>
                                    <small class="text-muted">{{ __('translation.dashboard.success_rate') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded-3 bg-info bg-opacity-10">
                                    <div class="fs-3 fw-bold text-info">{{ $systemHealth['avg_response_time'] ?? 0.8 }}s</div>
                                    <small class="text-muted">{{ __('translation.dashboard.avg_response') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                                    <span class="fw-medium small">{{ __('translation.dashboard.api_service') }}</span>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">{{ __('translation.dashboard.operational') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                                    <span class="fw-medium small">{{ __('translation.dashboard.database') }}</span>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">{{ __('translation.dashboard.operational') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                                    <span class="fw-medium small">{{ __('translation.dashboard.queue_system') }}</span>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">{{ __('translation.dashboard.operational') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                {{-- Activity Timeline --}}
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>
                            <h5 class="card-title fw-semibold mb-0">{{ __('translation.dashboard.recent_activity') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($activityTimeline) && $activityTimeline->count() > 0)
                            <div class="activity-list">
                                @foreach($activityTimeline->take(5) as $activity)
                                    <div class="d-flex align-items-start gap-3 {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-circle bg-{{ $activity['color'] }} bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                <i class="bi {{ $activity['icon'] }} text-{{ $activity['color'] }}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <p class="mb-1 small">{{ $activity['message'] }}</p>
                                            <small class="text-muted">{{ $activity['time']->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-clock-history display-6 text-muted opacity-50"></i>
                                <p class="text-muted mt-3 mb-0">{{ __('translation.dashboard.no_activity') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Specialties & Pending Clinics --}}
        <div class="row g-4 mb-4">
            {{-- Top Specialties --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-hospital text-success"></i>
                                <h5 class="card-title fw-semibold mb-0">{{ __('translation.dashboard.top_specialties') }}</h5>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success">{{ __('translation.dashboard.top_badge') }} 5</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($topSpecialties) && $topSpecialties->count() > 0)
                            <div class="d-flex flex-column gap-3">
                                @foreach($topSpecialties as $index => $specialty)
                                    <div class="d-flex align-items-center gap-3 p-3 rounded-3 {{ $index < 3 ? 'bg-light' : '' }}">
                                        <div class="flex-shrink-0">
                                            <div class="rounded-2 d-flex align-items-center justify-content-center fw-bold 
                                                {{ $index === 0 ? 'bg-warning text-white' : ($index === 1 ? 'bg-secondary text-white' : ($index === 2 ? 'bg-danger text-white' : 'bg-light text-muted')) }}" 
                                                style="width: 32px; height: 32px; font-size: 0.85rem;">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="fw-semibold">{{ $specialty->name }}</div>
                                                    <small class="text-muted">{{ number_format($specialty->clinics_count ?? 0) }} {{ __('translation.dashboard.clinics') ?? 'clinics' }}</small>
                                                </div>
                                                @if($index === 0)
                                                    <i class="bi bi-trophy-fill text-warning fs-5"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-6 text-muted opacity-50"></i>
                                <p class="text-muted mt-3 mb-0">{{ __('translation.dashboard.no_data') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Platform Stats --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-bar-chart-fill text-primary"></i>
                                <h5 class="card-title fw-semibold mb-0">{{ __('translation.dashboard.platform_stats') ?? 'Platform Stats' }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar-check text-info"></i>
                                    <span>{{ __('translation.dashboard.today_appointments') ?? 'Today\'s Appointments' }}</span>
                                </div>
                                <span class="badge bg-info">{{ $statistics['today_appointments'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-calendar-week text-primary"></i>
                                    <span>{{ __('translation.dashboard.week_appointments') ?? 'This Week' }}</span>
                                </div>
                                <span class="badge bg-primary">{{ $statistics['week_appointments'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-people text-success"></i>
                                    <span>{{ __('translation.dashboard.total_patients') ?? 'Total Patients' }}</span>
                                </div>
                                <span class="badge bg-success">{{ $statistics['patients'] ?? 0 }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-hourglass-split text-warning"></i>
                                    <span>{{ __('translation.dashboard.pending_clinics') ?? 'Pending Clinics' }}</span>
                                </div>
                                <span class="badge bg-warning">{{ $statistics['pending_clinics'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Contents & Quick Actions --}}
        <div class="row g-4 mb-4">
            {{-- Recent Contents Table --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-check text-info"></i>
                            <h5 class="card-title fw-semibold mb-0">{{ __('translation.dashboard.recent_appointments') ?? 'Recent Appointments' }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(isset($recentAppointments) && $recentAppointments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 text-muted small fw-semibold px-4 py-3">{{ __('translation.dashboard.table.patient') ?? 'Patient' }}</th>
                                            <th class="border-0 text-muted small fw-semibold">{{ __('translation.dashboard.table.clinic') ?? 'Clinic' }}</th>
                                            <th class="border-0 text-muted small fw-semibold">{{ __('translation.dashboard.table.status') ?? 'Status' }}</th>
                                            <th class="border-0 text-muted small fw-semibold">{{ __('translation.dashboard.table.date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentAppointments as $appointment)
                                            <tr>
                                                <td class="px-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 36px; height: 36px;">
                                                            {{ strtoupper(substr($appointment->patient->name ?? 'P', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium small">{{ $appointment->patient->name ?? 'N/A' }}</div>
                                                            <small class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($appointment->patient->phone ?? '', 15) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="small">{{ $appointment->clinic->name ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'confirmed' => 'info',
                                                            'completed' => 'success',
                                                            'cancelled' => 'danger',
                                                        ];
                                                        $color = $statusColors[$appointment->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">{{ ucfirst($appointment->status ?? 'pending') }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-1 text-muted">
                                                        <i class="bi bi-clock" style="font-size: 0.75rem;"></i>
                                                        <small>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') : '-' }}</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted opacity-50"></i>
                                <p class="text-muted mt-3">{{ __('translation.dashboard.no_recent_appointments') ?? 'No recent appointments' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Actions & Recent Users --}}
            <div class="col-lg-4">
                {{-- Quick Actions --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge text-warning"></i>
                            <h5 class="card-title fw-semibold mb-0">{{ __('translation.dashboard.quick_actions') }}</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <a href="{{ route('clinics.pending') }}" class="text-decoration-none">
                                    <div class="text-center p-3 rounded-3 bg-primary bg-opacity-10 hover-lift" style="transition: all 0.3s;">
                                        <div class="rounded-3 bg-primary bg-opacity-25 d-inline-flex p-3 mb-2">
                                            <i class="bi bi-hospital fs-4 text-primary"></i>
                                        </div>
                                        <div class="fw-semibold small text-primary">{{ __('translation.dashboard.pending_clinics') ?? 'Pending Clinics' }}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('users.index') }}" class="text-decoration-none">
                                    <div class="text-center p-3 rounded-3 bg-success bg-opacity-10 hover-lift" style="transition: all 0.3s;">
                                        <div class="rounded-3 bg-success bg-opacity-25 d-inline-flex p-3 mb-2">
                                            <i class="bi bi-people fs-4 text-success"></i>
                                        </div>
                                        <div class="fw-semibold small text-success">{{ __('translation.dashboard.manage_users') }}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('specialties.index') }}" class="text-decoration-none">
                                    <div class="text-center p-3 rounded-3 bg-warning bg-opacity-10 hover-lift" style="transition: all 0.3s;">
                                        <div class="rounded-3 bg-warning bg-opacity-25 d-inline-flex p-3 mb-2">
                                            <i class="bi bi-hospital fs-4 text-warning"></i>
                                        </div>
                                        <div class="fw-semibold small text-warning">{{ __('translation.dashboard.specialties') }}</div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('configurations.index') }}" class="text-decoration-none">
                                    <div class="text-center p-3 rounded-3 bg-secondary bg-opacity-10 hover-lift" style="transition: all 0.3s;">
                                        <div class="rounded-3 bg-secondary bg-opacity-25 d-inline-flex p-3 mb-2">
                                            <i class="bi bi-gear fs-4 text-secondary"></i>
                                        </div>
                                        <div class="fw-semibold small text-secondary">{{ __('translation.dashboard.settings') }}</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Users --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-person-plus text-success"></i>
                            <h5 class="card-title fw-semibold mb-0">{{ __('translation.dashboard.new_users') }}</h5>
                        </div>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-link text-decoration-none p-0">
                            {{ __('translation.dashboard.view_all') }}
                        </a>
                    </div>
                    <div class="card-body">
                        @if(isset($recentUsers) && $recentUsers->count() > 0)
                            <div class="d-flex flex-column gap-2">
                                @foreach($recentUsers as $index => $user)
                                    <div class="d-flex align-items-center p-2 rounded-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="rounded-circle bg-{{ ['primary', 'success', 'info', 'warning', 'danger'][$index % 5] }} d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="fw-semibold small text-truncate">{{ $user->name }}</div>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($user->email, 25) }}</small>
                                        </div>
                                        <small class="text-muted text-nowrap ms-2">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-person-x display-6 text-muted opacity-50"></i>
                                <p class="text-muted mt-2 mb-0 small">{{ __('translation.dashboard.no_users') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<style>
.hover-lift:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(var(--ins-black-rgb), 0.15) !important;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    // Get CSS variables for colors
    const rootStyles = getComputedStyle(document.documentElement);
    const primaryColor = rootStyles.getPropertyValue('--ins-primary').trim() || '#0d6efd';
    
    function hexToRgba(hex, alpha) {
        // Handle both #RGB and #RRGGBB formats
        let r, g, b;
        if (hex.length === 4) {
            r = parseInt(hex[1] + hex[1], 16);
            g = parseInt(hex[2] + hex[2], 16);
            b = parseInt(hex[3] + hex[3], 16);
        } else {
            r = parseInt(hex.slice(1, 3), 16);
            g = parseInt(hex.slice(3, 5), 16);
            b = parseInt(hex.slice(5, 7), 16);
        }
        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }
    
    const primaryRgba = hexToRgba(primaryColor, 0.1);
    
    const contentCtx = document.getElementById('contentChart');
    if (contentCtx) {
        // Wait for Chart.js to be loaded via lazy loading
        let Chart = window.Chart;
        if (!Chart) {
            // Manually initialize charts if not already done
            if (typeof window.initializeCharts === 'function') {
                await window.initializeCharts();
            }
            Chart = window.Chart;
        }
        
        // If still not available, dynamically import
        if (!Chart) {
            const ChartModule = await import('chart.js/auto');
            Chart = ChartModule.default || ChartModule;
            window.Chart = Chart;
        }
        
        const contentData = @json($contentChartData ?? ['labels' => [], 'values' => []]);
        
        new Chart(contentCtx, {
            type: 'line',
            data: {
                labels: contentData.labels,
                datasets: [{
                    label: '{{ __('translation.dashboard.content_generated') }}',
                    data: contentData.values,
                    borderColor: primaryColor,
                    backgroundColor: primaryRgba,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: primaryColor,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: primaryColor,
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 3,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { 
                        display: false 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: { 
                            display: false 
                        },
                        ticks: { 
                            maxTicksLimit: 8,
                            font: { size: 11 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { 
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: { 
                            stepSize: 1,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
