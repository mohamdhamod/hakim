{{-- Social History Sidebar Card --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">
            <i class="fas fa-users text-success me-2"></i>
            {{ __('translation.social_history.title') }}
        </h6>
        <button type="button" class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="modal" data-bs-target="#socialHistoryModal">
            <i class="fas fa-edit"></i>
        </button>
    </div>
    <div class="card-body">
        @if($patient->smoking_status || $patient->alcohol_status || $patient->occupation || $patient->marital_status)
            @if($patient->smoking_status)
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-smoking text-muted me-2" style="width: 18px;"></i>
                        <small class="text-muted">{{ __('translation.social_history.smoking_status') }}</small>
                    </div>
                    <span class="badge {{ $patient->smoking_status === 'current' ? 'bg-danger' : ($patient->smoking_status === 'former' ? 'bg-warning text-dark' : 'bg-success') }} ms-4">
                        {{ __('translation.social_history.smoking_' . $patient->smoking_status) }}
                    </span>
                </div>
            @endif

            @if($patient->alcohol_status)
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-wine-glass text-muted me-2" style="width: 18px;"></i>
                        <small class="text-muted">{{ __('translation.social_history.alcohol_status') }}</small>
                    </div>
                    <span class="badge {{ $patient->alcohol_status === 'regular' ? 'bg-danger' : ($patient->alcohol_status === 'occasional' ? 'bg-warning text-dark' : 'bg-success') }} ms-4">
                        {{ __('translation.social_history.alcohol_' . $patient->alcohol_status) }}
                    </span>
                </div>
            @endif

            @if($patient->occupation)
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-briefcase text-muted me-2" style="width: 18px;"></i>
                        <small class="text-muted">{{ __('translation.social_history.occupation') }}</small>
                    </div>
                    <p class="mb-0 small ms-4">{{ $patient->occupation }}</p>
                </div>
            @endif

            @if($patient->marital_status)
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-ring text-muted me-2" style="width: 18px;"></i>
                        <small class="text-muted">{{ __('translation.social_history.marital_status') }}</small>
                    </div>
                    <p class="mb-0 small ms-4">{{ __('translation.social_history.marital_' . $patient->marital_status) }}</p>
                </div>
            @endif

            @if($patient->lifestyle_notes)
                <div class="mb-0">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-comment-medical text-muted me-2" style="width: 18px;"></i>
                        <small class="text-muted">{{ __('translation.social_history.lifestyle_notes') }}</small>
                    </div>
                    <p class="mb-0 small ms-4">{{ $patient->lifestyle_notes }}</p>
                </div>
            @endif
        @else
            <div class="text-center py-3 text-muted">
                <i class="fas fa-users mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                <p class="small mb-2">{{ __('translation.social_history.no_data') }}</p>
                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#socialHistoryModal">
                    <i class="fas fa-plus me-1"></i>{{ __('translation.common.add') }}
                </button>
            </div>
        @endif
    </div>
</div>
