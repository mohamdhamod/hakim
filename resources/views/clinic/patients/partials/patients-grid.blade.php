{{-- Patients Grid --}}
<div class="row g-4">
    @forelse($patients as $patient)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="avatar-circle bg-primary-subtle text-primary me-3" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-injured fs-5"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $patient->name }}</h6>
                            <small class="text-muted">
                                <i class="fas fa-hashtag"></i> {{ $patient->file_number }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="patient-info small mb-3">
                        @if($patient->phone)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                            <span>{{ $patient->phone }}</span>
                        </div>
                        @endif
                        
                        @if($patient->date_of_birth)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-birthday-cake text-muted me-2" style="width: 16px;"></i>
                            <span>{{ $patient->age }} {{ __('translation.patient.years') }}</span>
                        </div>
                        @endif
                        
                        @if($patient->gender)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-{{ $patient->gender === 'male' ? 'mars' : 'venus' }} text-muted me-2" style="width: 16px;"></i>
                            <span>{{ __('translation.patient.' . $patient->gender) }}</span>
                        </div>
                        @endif
                        
                        <div class="d-flex align-items-center">
                            <i class="fas fa-notes-medical text-muted me-2" style="width: 16px;"></i>
                            <span>{{ $patient->examinations_count ?? 0 }} {{ __('translation.examination.examinations') }}</span>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                        </a>
                        <a href="{{ route('clinic.patients.edit', $patient) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-users text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                <h5 class="mt-3 text-muted">{{ __('translation.patient.no_patients') }}</h5>
                <p class="text-muted">{{ __('translation.patient.add_first_patient') }}</p>
                <a href="{{ route('clinic.patients.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.patient.add_new') }}
                </a>
            </div>
        </div>
    @endforelse
</div>

@if($patients->hasPages())
    <div class="mt-4">
        {{ $patients->links() }}
    </div>
@endif

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
.avatar-circle {
    flex-shrink: 0;
}
</style>
