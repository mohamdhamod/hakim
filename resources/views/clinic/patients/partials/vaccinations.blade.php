{{-- Vaccinations Section --}}
<div class="card border-0 shadow-sm mb-4" id="vaccinations-section" style="background: linear-gradient(135deg, #f093fb10 0%, #f5576c10 100%);">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-syringe text-danger me-2"></i>
            {{ __('translation.vaccinations') }}
            @if($patient->vaccinationRecords && $patient->vaccinationRecords->count() > 0)
                <span class="badge bg-danger ms-2">{{ $patient->vaccinationRecords->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-danger" onclick="openVaccinationCreate()">
            <i class="fas fa-plus me-2"></i>{{ __('translation.add_vaccination') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->vaccinationRecords && $patient->vaccinationRecords->count() > 0)
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('translation.vaccination_type') }}</th>
                            <th>{{ __('translation.disease_prevented') }}</th>
                            <th>{{ __('translation.vaccination_date') }}</th>
                            <th>{{ __('translation.dose_number') }}</th>
                            <th>{{ __('translation.next_dose_date') }}</th>
                            <th>{{ __('translation.status') }}</th>
                            <th width="100">{{ __('translation.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->vaccinationRecords->take(5) as $vaccination)
                            <tr>
                                <td>
                                    <strong>{{ $vaccination->vaccinationType->name }}</strong>
                                    @if($vaccination->batch_number)
                                        <br><small class="text-muted">{{ __('translation.batch') }}: {{ $vaccination->batch_number }}</small>
                                    @endif
                                </td>
                                <td class="small">
                                    <i class="fas fa-shield-virus text-danger me-1"></i>
                                    {{ $vaccination->vaccinationType->disease_prevented }}
                                </td>
                                <td class="small">{{ $vaccination->vaccination_date->format('M d, Y') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info-subtle text-info">
                                        {{ __('translation.dose') }} {{ $vaccination->dose_number }}
                                    </span>
                                </td>
                                <td class="small">
                                    @if($vaccination->next_dose_due_date)
                                        <span class="text-warning">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $vaccination->next_dose_due_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $vaccination->status === 'completed' ? 'success' : ($vaccination->status === 'missed' ? 'danger' : 'warning') }}-subtle text-{{ $vaccination->status === 'completed' ? 'success' : ($vaccination->status === 'missed' ? 'danger' : 'warning') }}">
                                        @if($vaccination->status === 'completed')
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($vaccination->status === 'missed')
                                            <i class="fas fa-times-circle"></i>
                                        @else
                                            <i class="fas fa-clock"></i>
                                        @endif
                                        {{ __('translation.' . $vaccination->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-nowrap">
                                        <button type="button" class="btn btn-sm btn-info view-btn" data-type="vaccination" data-model='@json($vaccination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($vaccination->clinic_id === $clinic->id)
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="vaccination" data-model='@json($vaccination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="vaccination" data-model='@json($vaccination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                @foreach($patient->vaccinationRecords->take(5) as $vaccination)
                    <div class="card mb-3 border rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $vaccination->vaccinationType->name }}</h6>
                                    @if($vaccination->batch_number)
                                        <small class="text-muted">{{ __('translation.batch') }}: {{ $vaccination->batch_number }}</small>
                                    @endif
                                </div>
                                <span class="badge bg-{{ $vaccination->status === 'completed' ? 'success' : ($vaccination->status === 'missed' ? 'danger' : 'warning') }}-subtle text-{{ $vaccination->status === 'completed' ? 'success' : ($vaccination->status === 'missed' ? 'danger' : 'warning') }}">
                                    @if($vaccination->status === 'completed')
                                        <i class="fas fa-check-circle"></i>
                                    @elseif($vaccination->status === 'missed')
                                        <i class="fas fa-times-circle"></i>
                                    @else
                                        <i class="fas fa-clock"></i>
                                    @endif
                                    {{ __('translation.' . $vaccination->status) }}
                                </span>
                            </div>
                            <div class="row g-2 small mt-2">
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.disease_prevented') }}</span>
                                    <i class="fas fa-shield-virus text-danger me-1"></i>{{ $vaccination->vaccinationType->disease_prevented }}
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.vaccination_date') }}</span>
                                    <i class="fas fa-calendar text-primary me-1"></i>{{ $vaccination->vaccination_date->format('M d, Y') }}
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.dose_number') }}</span>
                                    <span class="badge bg-info-subtle text-info">{{ __('translation.dose') }} {{ $vaccination->dose_number }}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.next_dose_date') }}</span>
                                    @if($vaccination->next_dose_due_date)
                                        <span class="text-warning">
                                            <i class="fas fa-clock me-1"></i>{{ $vaccination->next_dose_due_date->format('M d, Y') }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="row g-1 mt-3 pt-2 border-top">
                                <div class="col-6">
                                    <button class="btn btn-sm btn-info w-100 view-btn" data-type="vaccination" data-model='@json($vaccination)'>
                                        <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                    </button>
                                </div>
                                @if($vaccination->clinic_id === $clinic->id)
                                <div class="col-6">
                                    <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="vaccination" data-model='@json($vaccination)'>
                                        <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                    </button>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="vaccination" data-model='@json($vaccination)'>
                                        <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($patient->vaccinationRecords->count() > 5)
                <div class="p-3 text-center border-top">
                    <a href="{{ route('clinic.patients.all-vaccinations', $patient->file_number) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-list me-2"></i>
                        {{ __('translation.view_all') }} ({{ $patient->vaccinationRecords->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">💉</div>
                <h6 class="text-muted mb-2">{{ __('translation.no_vaccinations') }}</h6>
                <p class="small text-muted mb-3">{{ __('translation.track_immunization_history') }}</p>
                <button type="button" class="btn btn-sm btn-danger" onclick="openVaccinationCreate()">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.add_vaccination') }}
                </button>
            </div>
        @endif
    </div>
</div>
