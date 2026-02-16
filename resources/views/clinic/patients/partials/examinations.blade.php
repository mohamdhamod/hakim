{{-- Examinations History Section --}}
<div class="card border-0 shadow-sm mb-4" id="examinations-section">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-clipboard-list text-success me-2"></i>
            {{ __('translation.examination.history') }}
            @if($patient->examinations && $patient->examinations->count() > 0)
                <span class="badge bg-success ms-2">{{ $patient->examinations->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-success" onclick="openExaminationCreate()">
            <i class="fas fa-plus me-2"></i>{{ __('translation.examination.new') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->examinations && $patient->examinations->count() > 0)
            <div class="table-responsive d-none d-md-block">
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
                                    <div class="d-flex gap-1 flex-nowrap">
                                        <button type="button" class="btn btn-sm btn-info view-btn" data-type="examination" data-model='@json($examination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($examination->clinic_id === $clinic->id)
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="examination" data-model='@json($examination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="examination" data-model='@json($examination)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                @foreach($patient->examinations->take(10) as $examination)
                    <div class="card mb-3 border rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-secondary me-1">{{ $examination->examination_number }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar text-primary me-1"></i>{{ $examination->examination_date->format('M d, Y') }}
                                        <i class="fas fa-clock ms-1"></i> {{ $examination->examination_date->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                            <div class="small mt-2">
                                <div class="mb-2">
                                    <span class="text-muted d-block fw-bold">{{ __('translation.examination.chief_complaint') }}</span>
                                    {{ Str::limit($examination->chief_complaint, 80) ?: '-' }}
                                </div>
                                <div class="mb-1">
                                    <span class="text-muted d-block fw-bold">{{ __('translation.examination.diagnosis') }}</span>
                                    @if($examination->diagnosis)
                                        {{ Str::limit($examination->diagnosis, 80) }}
                                        @if($examination->icd_code)
                                            <br><small class="text-muted">ICD: {{ $examination->icd_code }}</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="row g-1 mt-3 pt-2 border-top">
                                <div class="col-6">
                                    <button class="btn btn-sm btn-info w-100 view-btn" data-type="examination" data-model='@json($examination)'>
                                        <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                    </button>
                                </div>
                                @if($examination->clinic_id === $clinic->id)
                                <div class="col-6">
                                    <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="examination" data-model='@json($examination)'>
                                        <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                    </button>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('clinic.examinations.print', $examination->id) }}" class="btn btn-sm btn-secondary w-100" target="_blank">
                                        <i class="fas fa-print me-1"></i>{{ __('translation.common.print') }}
                                    </a>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="examination" data-model='@json($examination)'>
                                        <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($patient->examinations->count() > 10)
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="{{ route('clinic.patients.all-examinations', $patient->file_number) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-list me-2"></i>
                        {{ __('translation.view_all') }} ({{ $patient->examinations->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-clipboard text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                <h6 class="mt-3 text-muted">{{ __('translation.examination.no_examinations') }}</h6>
                <p class="small text-muted mb-3">{{ __('translation.examination.add_first_examination') }}</p>
                <button type="button" class="btn btn-sm btn-success" onclick="openExaminationCreate()">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.examination.create_first') }}
                </button>
            </div>
        @endif
    </div>
</div>
