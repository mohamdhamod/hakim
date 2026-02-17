{{-- Surgical History Section --}}
<div class="card border-0 shadow-sm mb-4" id="surgical-history-section">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-procedures text-purple me-2" style="color: #6f42c1;"></i>
            {{ __('translation.surgical_history.title') }}
            @if($patient->surgicalHistories && $patient->surgicalHistories->count() > 0)
                <span class="badge bg-secondary ms-2">{{ $patient->surgicalHistories->count() }}</span>
            @endif
        </h5>
        @if($clinic->id === $patient->clinic_id || $patient->surgicalHistories->where('clinic_id', $clinic->id)->count() >= 0)
        <button type="button" class="btn btn-sm btn-outline-secondary" style="border-color: #6f42c1; color: #6f42c1;" data-bs-toggle="modal" data-bs-target="#addSurgicalHistoryModal">
            <i class="fas fa-plus me-2"></i>{{ __('translation.surgical_history.add') }}
        </button>
        @endif
    </div>
    <div class="card-body p-0">
        @if($patient->surgicalHistories && $patient->surgicalHistories->count() > 0)
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('translation.surgical_history.procedure_name') }}</th>
                            <th>{{ __('translation.surgical_history.procedure_date') }}</th>
                            <th>{{ __('translation.surgical_history.hospital') }}</th>
                            <th>{{ __('translation.surgical_history.complications') }}</th>
                            <th width="120">{{ __('translation.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->surgicalHistories as $surgery)
                            <tr>
                                <td class="small fw-semibold">{{ $surgery->procedure_name }}</td>
                                <td class="small">
                                    @if($surgery->procedure_date)
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        {{ $surgery->procedure_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small">{{ $surgery->hospital ?: '-' }}</td>
                                <td class="small">
                                    @if($surgery->complications)
                                        <span class="badge bg-warning text-dark">{{ Str::limit($surgery->complications, 30) }}</span>
                                    @else
                                        <span class="text-muted">{{ __('translation.surgical_history.none') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-nowrap">
                                        <button type="button" class="btn btn-sm btn-info view-btn" data-type="surgery" data-model='@json($surgery)' data-bs-toggle="tooltip" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($surgery->clinic_id === $clinic->id)
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="surgery" data-model='@json($surgery)' data-bs-toggle="tooltip" title="{{ __('translation.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="surgery" data-model='@json($surgery)' data-bs-toggle="tooltip" title="{{ __('translation.common.delete') }}">
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
                @foreach($patient->surgicalHistories as $surgery)
                    <div class="card mb-3 border rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0 fw-semibold">{{ $surgery->procedure_name }}</h6>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-info view-btn" data-type="surgery" data-model='@json($surgery)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($surgery->clinic_id === $clinic->id)
                                    <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="surgery" data-model='@json($surgery)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="surgery" data-model='@json($surgery)'>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @if($surgery->procedure_date)
                                <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $surgery->procedure_date->format('M d, Y') }}</small>
                            @endif
                            @if($surgery->hospital)
                                <small class="text-muted d-block"><i class="fas fa-hospital me-1"></i>{{ $surgery->hospital }}</small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-procedures mb-3" style="font-size: 3rem; opacity: 0.2;"></i>
                <p class="mb-2">{{ __('translation.surgical_history.no_records') }}</p>
                <button type="button" class="btn btn-sm btn-outline-secondary" style="border-color: #6f42c1; color: #6f42c1;" data-bs-toggle="modal" data-bs-target="#addSurgicalHistoryModal">
                    <i class="fas fa-plus me-1"></i>{{ __('translation.surgical_history.add') }}
                </button>
            </div>
        @endif
    </div>
</div>
