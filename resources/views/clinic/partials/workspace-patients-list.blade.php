{{-- Workspace patients list (AJAX partial) --}}
@forelse($patients as $patient)
    <div class="d-flex align-items-center p-3 border-bottom list-item-hover">
        <a href="{{ route('clinic.patients.show', $patient) }}" class="d-flex align-items-center flex-grow-1 text-decoration-none" style="min-width: 0;">
            <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3">
                <i class="fas fa-user-injured text-info"></i>
            </div>
            <div class="flex-grow-1" style="min-width: 0;">
                <div class="fw-medium text-dark text-truncate">{{ $patient->full_name }}</div>
                <small class="text-muted">
                    <i class="fas fa-hashtag me-1"></i>{{ $patient->file_number }}
                    @if($patient->phone)
                        <span class="mx-1">•</span><i class="fas fa-phone me-1"></i>{{ $patient->phone }}
                    @endif
                </small>
            </div>
        </a>
        <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-sm btn-outline-warning ms-2" title="{{ __('translation.common.edit') }}">
            <i class="fas fa-edit"></i>
        </a>
    </div>
@empty
    @if(!isset($crossClinicPatients) || $crossClinicPatients->isEmpty())
    <div class="text-center py-4">
        <i class="fas fa-search text-muted mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
        <p class="text-muted mb-0 small">{{ __('translation.patient.no_patients') }}</p>
    </div>
    @endif
@endforelse

{{-- Cross-clinic patients (found by file number in other clinics) --}}
@if(isset($crossClinicPatients) && $crossClinicPatients->isNotEmpty())
    @if($patients->isNotEmpty())
        <div class="border-top my-1"></div>
    @endif
    <div class="px-3 pt-2 pb-1">
        <small class="text-muted fw-semibold">
            <i class="fas fa-hospital-alt me-1"></i>{{ __('translation.patient.patient_from_other_clinic') }}
        </small>
    </div>
    @foreach($crossClinicPatients as $crossPatient)
        <div class="d-flex align-items-center p-3 border-bottom list-item-hover bg-light bg-opacity-50">
            <div class="d-flex align-items-center flex-grow-1" style="min-width: 0;">
                <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                    <i class="fas fa-user-injured text-warning"></i>
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="fw-medium text-dark text-truncate">{{ $crossPatient->full_name }}</div>
                    <small class="text-muted">
                        <i class="fas fa-hashtag me-1"></i>{{ $crossPatient->file_number }}
                        @if($crossPatient->phone)
                            <span class="mx-1">•</span><i class="fas fa-phone me-1"></i>{{ $crossPatient->phone }}
                        @endif
                    </small>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary ms-2 request-access-btn"
                data-patient-id="{{ $crossPatient->id }}"
                data-patient-name="{{ $crossPatient->full_name }}"
                data-patient-file-number="{{ $crossPatient->file_number }}"
                title="{{ __('translation.patient.request_access') }}">
                <i class="fas fa-hand-pointer me-1"></i><span class="d-none d-sm-inline">{{ __('translation.patient.request_access') }}</span>
            </button>
        </div>
    @endforeach
@endif
