{{-- Patients List --}}
@if($patients->count() > 0)
    {{-- Desktop Table --}}
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>{{ __('translation.patient.file_number') }}</th>
                    <th>{{ __('translation.patient.name') }}</th>
                    <th>{{ __('translation.patient.phone') }}</th>
                    <th>{{ __('translation.patient.age') }}</th>
                    <th>{{ __('translation.patient.gender') }}</th>
                    <th>{{ __('translation.examination.examinations') }}</th>
                    <th width="100">{{ __('translation.common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                    <tr>
                        <td class="small">
                            <span class="badge bg-secondary">{{ $patient->file_number }}</span>
                        </td>
                        <td class="small fw-medium">{{ $patient->full_name }}</td>
                        <td class="small">
                            @if($patient->phone)
                                <i class="fas fa-phone text-muted me-1"></i>{{ $patient->phone }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="small">
                            @if($patient->date_of_birth)
                                {{ $patient->age }} {{ __('translation.patient.years') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="small">
                            @if($patient->gender)
                                <i class="fas fa-{{ $patient->gender === 'male' ? 'mars' : 'venus' }} text-muted me-1"></i>{{ __('translation.patient.' . $patient->gender) }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="small">
                            <span class="badge bg-info bg-opacity-10 text-info">{{ $patient->examinations_count ?? 0 }}</span>
                        </td>
                        <td>
                            <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="d-md-none p-3">
        @foreach($patients as $patient)
            <div class="card mb-3 border rounded-3">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start mb-2">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                            <i class="fas fa-user-injured text-primary"></i>
                        </div>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <h6 class="mb-1 fw-bold text-truncate">{{ $patient->full_name }}</h6>
                            <small class="text-muted">
                                <span class="badge bg-secondary">{{ $patient->file_number }}</span>
                            </small>
                        </div>
                    </div>
                    <div class="small mt-2">
                        @if($patient->phone)
                        <div class="mb-1">
                            <i class="fas fa-phone text-muted me-1"></i>{{ $patient->phone }}
                        </div>
                        @endif
                        <div class="d-flex flex-wrap gap-3">
                            @if($patient->date_of_birth)
                            <span><i class="fas fa-birthday-cake text-muted me-1"></i>{{ $patient->age }} {{ __('translation.patient.years') }}</span>
                            @endif
                            @if($patient->gender)
                            <span><i class="fas fa-{{ $patient->gender === 'male' ? 'mars' : 'venus' }} text-muted me-1"></i>{{ __('translation.patient.' . $patient->gender) }}</span>
                            @endif
                            <span><i class="fas fa-notes-medical text-muted me-1"></i>{{ $patient->examinations_count ?? 0 }} {{ __('translation.examination.examinations') }}</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top">
                        <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-sm btn-info w-100">
                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="fas fa-users text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
        <h5 class="mt-3 text-muted">{{ __('translation.patient.no_patients') }}</h5>
        <p class="text-muted">{{ __('translation.patient.add_first_patient') }}</p>
        <a href="{{ route('clinic.patients.create') }}" class="btn btn-primary mt-2">
            <i class="fas fa-plus me-2"></i>{{ __('translation.patient.add_new') }}
        </a>
    </div>
@endif

@if($patients->hasPages())
    <div class="mt-4 px-3">
        {{ $patients->links() }}
    </div>
@endif
