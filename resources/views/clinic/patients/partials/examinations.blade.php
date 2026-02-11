{{-- Examinations History Section --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-clipboard-list text-success me-2"></i>
            {{ __('translation.examination.history') }}
            @if($patient->examinations && $patient->examinations->count() > 0)
                <span class="badge bg-success ms-2">{{ $patient->examinations->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#newExaminationModal">
            <i class="fas fa-plus me-2"></i>{{ __('translation.examination.new') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->examinations && $patient->examinations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('translation.examination.number') }}</th>
                            <th>{{ __('translation.examination.date') }}</th>
                            <th>{{ __('translation.examination.chief_complaint') }}</th>
                            <th>{{ __('translation.examination.diagnosis') }}</th>
                            <th width="140">{{ __('translation.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->examinations->take(10) as $examination)
                            <tr>
                                <td class="small">
                                    <span class="badge bg-secondary">{{ $examination->examination_number }}</span>
                                </td>
                                <td class="small">
                                    <i class="fas fa-calendar text-primary me-1"></i>
                                    {{ $examination->examination_date->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $examination->examination_date->format('H:i') }}
                                    </small>
                                </td>
                                <td class="small">
                                    {{ Str::limit($examination->chief_complaint, 50) ?: '-' }}
                                </td>
                                <td class="small">
                                    @if($examination->diagnosis)
                                        <span class="text-dark">{{ Str::limit($examination->diagnosis, 50) }}</span>
                                        @if($examination->icd_code)
                                            <br><small class="text-muted">ICD: {{ $examination->icd_code }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-success" onclick="viewExamination({{ $examination->id }})" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('clinic.examinations.show', $examination->id) }}" class="btn btn-outline-primary" title="{{ __('translation.examination.full_details') }}">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <a href="{{ route('clinic.examinations.print', $examination->id) }}" class="btn btn-outline-secondary" target="_blank" title="{{ __('translation.common.print') }}">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($patient->examinations->count() > 10)
                <div class="card-footer bg-white border-0 text-center py-2">
                    <small class="text-muted">
                        {{ __('translation.showing_latest_of_total', ['count' => 10, 'total' => $patient->examinations->count()]) }}
                    </small>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-clipboard text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                <h6 class="mt-3 text-muted">{{ __('translation.examination.no_examinations') }}</h6>
                <p class="small text-muted mb-3">{{ __('translation.examination.add_first_examination') }}</p>
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#newExaminationModal">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.examination.create_first') }}
                </button>
            </div>
        @endif
    </div>
</div>
