{{-- Chronic Diseases Section --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #fa709a10 0%, #fee14010 100%);">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-heartbeat text-warning me-2"></i>
            {{ __('translation.chronic_diseases') }}
            @if($patient->chronicDiseases && $patient->chronicDiseases->count() > 0)
                <span class="badge bg-warning text-dark ms-2">{{ $patient->chronicDiseases->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#newChronicDiseaseModal">
            <i class="fas fa-plus me-2"></i>{{ __('translation.add_chronic_disease') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->chronicDiseases && $patient->chronicDiseases->count() > 0)
            <div class="table-responsive">
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
                                    <button class="btn btn-sm btn-outline-warning me-1" onclick="viewDiseaseDetails({{ $disease->id }})" title="{{ __('translation.common.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" onclick="addMonitoring({{ $disease->id }})" title="{{ __('translation.add_monitoring') }}">
                                        <i class="fas fa-chart-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#newChronicDiseaseModal">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.add_first_disease') }}
                </button>
            </div>
        @endif
    </div>
</div>
