<div class="patient-details">
    {{-- Patient Info Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div class="avatar-circle bg-info-subtle text-info me-3" style="width: 60px; height: 60px;">
                    <i class="bi bi-person-badge fs-3"></i>
                </div>
                <div>
                    <h4 class="mb-1">{{ $patient->name }}</h4>
                    <span class="badge bg-secondary">
                        <i class="bi bi-hash me-1"></i>{{ $patient->file_number }}
                    </span>
                </div>
            </div>
            
            <div class="row g-3">
                @if($patient->phone)
                    <div class="col-md-4">
                        <div class="info-item">
                            <label class="text-muted small">{{ __('translation.patient.phone') }}</label>
                            <p class="mb-0">
                                <i class="bi bi-telephone me-1"></i>
                                <a href="tel:{{ $patient->phone }}">{{ $patient->phone }}</a>
                            </p>
                        </div>
                    </div>
                @endif
                
                @if($patient->date_of_birth)
                    <div class="col-md-4">
                        <div class="info-item">
                            <label class="text-muted small">{{ __('translation.patient.age') }}</label>
                            <p class="mb-0">
                                <i class="bi bi-calendar me-1"></i>
                                {{ $patient->age }} {{ __('translation.common.years') }}
                            </p>
                        </div>
                    </div>
                @endif
                
                @if($patient->gender)
                    <div class="col-md-4">
                        <div class="info-item">
                            <label class="text-muted small">{{ __('translation.patient.gender') }}</label>
                            <p class="mb-0">
                                <i class="bi bi-{{ $patient->gender === 'male' ? 'gender-male' : 'gender-female' }} me-1"></i>
                                {{ __('translation.patient.' . $patient->gender) }}
                            </p>
                        </div>
                    </div>
                @endif
                
                @if($patient->blood_type)
                    <div class="col-md-4">
                        <div class="info-item">
                            <label class="text-muted small">{{ __('translation.patient.blood_type') }}</label>
                            <p class="mb-0">
                                <i class="bi bi-droplet me-1 text-danger"></i>
                                {{ $patient->blood_type }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Medical History --}}
    @if($patient->medical_history || $patient->allergies || $patient->chronic_diseases)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-3">
                    <i class="bi bi-heart-pulse text-danger me-2"></i>
                    {{ __('translation.patient.medical_info') }}
                </h6>
                
                @if($patient->allergies)
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('translation.patient.allergies') }}</label>
                        <p class="mb-0">{{ $patient->allergies }}</p>
                    </div>
                @endif
                
                @if($patient->chronic_diseases)
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('translation.patient.chronic_diseases') }}</label>
                        <p class="mb-0">{{ $patient->chronic_diseases }}</p>
                    </div>
                @endif
                
                @if($patient->medical_history)
                    <div class="mb-0">
                        <label class="text-muted small">{{ __('translation.patient.medical_history') }}</label>
                        <p class="mb-0">{{ $patient->medical_history }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
    
    {{-- Quick Actions --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-3">
                <i class="bi bi-lightning text-primary me-2"></i>
                {{ __('translation.common.actions') }}
            </h6>
            
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-primary">
                    <i class="bi bi-clipboard-plus me-1"></i>
                    {{ __('translation.clinic_chat.new_examination') }}
                </a>
                <a href="{{ route('clinic.patients.edit', $patient) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-pencil me-1"></i>
                    {{ __('translation.common.edit') }}
                </a>
                <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-1"></i>
                    {{ __('translation.clinic_chat.full_profile') }}
                </a>
            </div>
        </div>
    </div>
    
    {{-- Recent Examinations --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="mb-0">
                    <i class="bi bi-clipboard-pulse text-primary me-2"></i>
                    {{ __('translation.clinic_chat.recent_examinations') }}
                </h6>
                <a href="{{ route('clinic.patients.show', $patient) }}#examinations" class="btn btn-sm btn-link">
                    {{ __('translation.common.view_all') }}
                </a>
            </div>
            
            @if($patient->examinations->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($patient->examinations as $examination)
                        <a href="{{ route('clinic.patients.show', ['patient' => $patient->file_number, 'tab' => 'examinations', 'examination' => $examination->id]) }}" 
                           class="list-group-item list-group-item-action px-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="mb-1 fw-semibold">{{ Str::limit($examination->diagnosis, 50) ?: __('translation.examination.no_diagnosis') }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        {{ $examination->examination_date->format('Y-m-d') }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $examination->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ __('translation.examination.status_' . $examination->status) }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-clipboard display-6 opacity-25"></i>
                    <p class="small mt-2 mb-0">{{ __('translation.clinic_chat.no_examinations') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
