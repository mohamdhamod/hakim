{{-- Vaccinations Section --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #f093fb10 0%, #f5576c10 100%);">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-syringe text-danger me-2"></i>
            {{ __('translation.vaccinations') }}
            @if($patient->vaccinationRecords && $patient->vaccinationRecords->count() > 0)
                <span class="badge bg-danger ms-2">{{ $patient->vaccinationRecords->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#newVaccinationModal">
            <i class="fas fa-plus me-2"></i>{{ __('translation.add_vaccination') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->vaccinationRecords && $patient->vaccinationRecords->count() > 0)
            <div class="table-responsive">
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
                                    <button class="btn btn-sm btn-outline-danger" onclick="viewVaccination({{ $vaccination->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($patient->vaccinationRecords->count() > 5)
                <div class="p-3 text-center border-top">
                    <span class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ __('translation.showing_latest_of_total', ['shown' => 5, 'total' => $patient->vaccinationRecords->count()]) }}
                    </span>
                </div>
            @endif
        @else
            <div class="text-center py-4">
                <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">💉</div>
                <h6 class="text-muted mb-2">{{ __('translation.no_vaccinations') }}</h6>
                <p class="small text-muted mb-3">{{ __('translation.track_immunization_history') }}</p>
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#newVaccinationModal">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.add_vaccination') }}
                </button>
            </div>
        @endif
    </div>
</div>
