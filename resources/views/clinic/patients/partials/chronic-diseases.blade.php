{{-- Chronic Diseases Section --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #fa709a10 0%, #fee14010 100%);" id="chronic-diseases-section">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-heartbeat text-warning me-2"></i>
            {{ __('translation.chronic_diseases') }}
            @if($patient->chronicDiseases && $patient->chronicDiseases->count() > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $patient->chronicDiseases->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-warning" onclick="openChronicCreate()">
            <i class="fas fa-plus me-2"></i>{{ __('translation.add_chronic_disease') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->chronicDiseases && $patient->chronicDiseases->count() > 0)
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('translation.disease_type') }}</th>
                            <th>{{ __('translation.diagnosis_date') }}</th>
                            <th>{{ __('translation.severity') }}</th>
                            <th>{{ __('translation.disease_status') }}</th>
                            <th>{{ __('translation.next_followup') }}</th>
                            <th width="150">{{ __('translation.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->chronicDiseases->take(5) as $disease)
                            <tr>
                                <td>
                                    <strong>{{ $disease->chronicDiseaseType->name }}</strong>
                                    <br><small class="text-muted">
                                        <i class="fas fa-tag"></i> {{ $disease->chronicDiseaseType->category }}
                                        @if($disease->chronicDiseaseType->icd11_code)
                                            | ICD-11: {{ $disease->chronicDiseaseType->icd11_code }}
                                        @endif
                                    </small>
                                </td>
                                <td class="small">
                                    <i class="fas fa-calendar-check text-primary me-1"></i>
                                    {{ $disease->diagnosis_date->format('M d, Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $disease->severity === 'severe' ? 'danger' : ($disease->severity === 'moderate' ? 'warning' : 'success') }}">
                                        {{ __('translation.' . $disease->severity) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}-subtle text-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}">
                                        @if($disease->status === 'active')
                                            <i class="fas fa-exclamation-circle"></i>
                                        @elseif($disease->status === 'in_remission')
                                            <i class="fas fa-pause-circle"></i>
                                        @else
                                            <i class="fas fa-check-circle"></i>
                                        @endif
                                        {{ __('translation.' . $disease->status) }}
                                    </span>
                                </td>
                                <td class="small">
                                    @if($disease->next_followup_date)
                                        <span class="{{ $disease->next_followup_date->isPast() ? 'text-danger' : 'text-warning' }}">
                                            <i class="fas fa-bell me-1"></i>
                                            {{ $disease->next_followup_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-nowrap">
                                        <button type="button" class="btn btn-sm btn-info view-btn" data-type="chronicDisease" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($disease->clinic_id === $clinic->id)
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="chronicDisease" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-success monitoring-btn" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.add_monitoring') }}">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="chronicDisease" data-model='@json($disease)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="d-md-none p-3">
                @foreach($patient->chronicDiseases->take(5) as $disease)
                    <div class="card mb-3 border rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $disease->chronicDiseaseType->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-tag"></i> {{ $disease->chronicDiseaseType->category }}
                                        @if($disease->chronicDiseaseType->icd11_code)
                                            | ICD-11: {{ $disease->chronicDiseaseType->icd11_code }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}-subtle text-{{ $disease->status === 'active' ? 'danger' : ($disease->status === 'in_remission' ? 'warning' : 'success') }}">
                                    @if($disease->status === 'active')
                                        <i class="fas fa-exclamation-circle"></i>
                                    @elseif($disease->status === 'in_remission')
                                        <i class="fas fa-pause-circle"></i>
                                    @else
                                        <i class="fas fa-check-circle"></i>
                                    @endif
                                    {{ __('translation.' . $disease->status) }}
                                </span>
                            </div>
                            <div class="row g-2 small mt-2">
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.diagnosis_date') }}</span>
                                    <i class="fas fa-calendar-check text-primary me-1"></i>{{ $disease->diagnosis_date->format('M d, Y') }}
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.severity') }}</span>
                                    <span class="badge bg-{{ $disease->severity === 'severe' ? 'danger' : ($disease->severity === 'moderate' ? 'warning' : 'success') }}">
                                        {{ __('translation.' . $disease->severity) }}
                                    </span>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted d-block">{{ __('translation.next_followup') }}</span>
                                    @if($disease->next_followup_date)
                                        <span class="{{ $disease->next_followup_date->isPast() ? 'text-danger' : 'text-warning' }}">
                                            <i class="fas fa-bell me-1"></i>{{ $disease->next_followup_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="row g-1 mt-3 pt-2 border-top">
                                <div class="col-6">
                                    <button class="btn btn-sm btn-info w-100 view-btn" data-type="chronicDisease" data-model='@json($disease)'>
                                        <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                    </button>
                                </div>
                                @if($disease->clinic_id === $clinic->id)
                                <div class="col-6">
                                    <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="chronicDisease" data-model='@json($disease)'>
                                        <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-sm btn-success w-100 monitoring-btn" data-model='@json($disease)'>
                                        <i class="fas fa-chart-line me-1"></i>{{ __('translation.add_monitoring') }}
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="chronicDisease" data-model='@json($disease)'>
                                        <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($patient->chronicDiseases->count() > 5)
                <div class="p-3 text-center border-top">
                    <a href="{{ route('clinic.patients.all-chronic-diseases', $patient->file_number) }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-list me-2"></i>
                        {{ __('translation.view_all') }} ({{ $patient->chronicDiseases->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">🏥</div>
                <h6 class="text-muted mb-2">{{ __('translation.no_chronic_diseases') }}</h6>
                <p class="small text-muted mb-3">{{ __('translation.manage_chronic_conditions') }}</p>
                <button type="button" class="btn btn-sm btn-warning" onclick="openChronicCreate()">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.add_first_disease') }}
                </button>
            </div>
        @endif
    </div>
</div>
