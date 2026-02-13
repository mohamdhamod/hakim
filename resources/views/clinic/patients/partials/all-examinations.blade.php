@extends('layouts.clinic')

@section('content')
<div class="container-fluid py-4">
    {{-- Header with Back Button --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-clipboard-list text-success me-2"></i>
                {{ __('translation.examination.all_examinations') }}
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clinic.patients.show', $patient->file_number) }}">{{ $patient->full_name }}</a></li>
                    <li class="breadcrumb-item active">{{ __('translation.examination.examinations') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'examinations']) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('translation.common.back') }}
            </a>
        </div>
    </div>

    {{-- Patient Info Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-lg bg-success-subtle text-success rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold">{{ $patient->full_name }}</h6>
                    <small class="text-muted">
                        {{ $patient->file_number }} | 
                        {{ $patient->age ? $patient->age . ' ' . __('translation.patient.years') : '-' }} |
                        {{ $patient->gender_label }}
                    </small>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-success fs-6">{{ $examinations->total() }} {{ __('translation.examination.examinations') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Examinations Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($examinations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('translation.examination.number') }}</th>
                                <th>{{ __('translation.examination.date') }}</th>
                                <th>{{ __('translation.examination.chief_complaint') }}</th>
                                <th>{{ __('translation.examination.diagnosis') }}</th>
                                <th>{{ __('translation.status') }}</th>
                                <th width="150">{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($examinations as $examination)
                                <tr>
                                    <td class="small">
                                        <span class="badge bg-secondary">{{ $examination->examination_number }}</span>
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $examination->examination_date->format('M d, Y') }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $examination->examination_date->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="small">
                                        {{ Str::limit($examination->chief_complaint, 50) ?: '-' }}
                                    </td>
                                    <td class="small">
                                        @if($examination->diagnosis)
                                            <span class="text-dark">{{ Str::limit($examination->diagnosis, 50) }}</span>
                                            @if($examination->icd_code)
                                                <br><small class="text-muted">ICD: {{ $examination->icd_code }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $examination->status === 'completed' ? 'success' : ($examination->status === 'cancelled' ? 'danger' : 'warning') }}-subtle text-{{ $examination->status === 'completed' ? 'success' : ($examination->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ __('translation.examination.status.' . $examination->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'examinations', 'examination' => $examination->id]) }}" class="btn btn-outline-success" title="{{ __('translation.common.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('clinic.examinations.edit', $examination->id) }}" class="btn btn-outline-primary" title="{{ __('translation.common.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('clinic.examinations.print', $examination->id) }}" class="btn btn-outline-secondary" target="_blank" title="{{ __('translation.common.print') }}">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($examinations->hasPages())
                    <div class="card-footer bg-white border-0 py-3">
                        {{ $examinations->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                    <h6 class="mt-3 text-muted">{{ __('translation.examination.no_examinations') }}</h6>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
