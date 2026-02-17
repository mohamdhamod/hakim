{{-- Problem List Section --}}
<div class="card border-0 shadow-sm mb-4" id="problem-list-section">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-list-check text-danger me-2"></i>
            {{ __('translation.problem_list.title') }}
            @if($patient->problems && $patient->problems->count() > 0)
                <span class="badge bg-danger ms-2">{{ $patient->activeProblems->count() }} {{ __('translation.problem_list.active') }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addProblemModal">
            <i class="fas fa-plus me-2"></i>{{ __('translation.problem_list.add') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->problems && $patient->problems->count() > 0)
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('translation.problem_list.problem') }}</th>
                            <th>{{ __('translation.problem_list.icd_code') }}</th>
                            <th>{{ __('translation.problem_list.onset_date') }}</th>
                            <th>{{ __('translation.problem_list.status') }}</th>
                            <th>{{ __('translation.problem_list.severity') }}</th>
                            <th width="120">{{ __('translation.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->problems as $problem)
                            <tr>
                                <td class="small fw-semibold">{{ $problem->title }}</td>
                                <td class="small">
                                    @if($problem->icd_code)
                                        <span class="badge bg-info text-dark">{{ $problem->icd_code }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small">
                                    @if($problem->onset_date)
                                        {{ $problem->onset_date->format('M d, Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="small">
                                    <span class="badge {{ $problem->status_badge_class }}">{{ $problem->status_label }}</span>
                                </td>
                                <td class="small">
                                    @if($problem->severity)
                                        <span class="badge {{ $problem->severity_badge_class }}">{{ $problem->severity_label }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-nowrap">
                                        <button type="button" class="btn btn-sm btn-info view-btn" data-type="problem" data-model='@json($problem)' data-bs-toggle="tooltip" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($problem->clinic_id === $clinic->id)
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="problem" data-model='@json($problem)' data-bs-toggle="tooltip" title="{{ __('translation.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="problem" data-model='@json($problem)' data-bs-toggle="tooltip" title="{{ __('translation.common.delete') }}">
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
                @foreach($patient->problems as $problem)
                    <div class="card mb-3 border rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 fw-semibold">{{ $problem->title }}</h6>
                                    @if($problem->icd_code)
                                        <small class="badge bg-info text-dark">{{ $problem->icd_code }}</small>
                                    @endif
                                </div>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-info view-btn" data-type="problem" data-model='@json($problem)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($problem->clinic_id === $clinic->id)
                                    <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="problem" data-model='@json($problem)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="problem" data-model='@json($problem)'>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge {{ $problem->status_badge_class }}">{{ $problem->status_label }}</span>
                                @if($problem->severity)
                                    <span class="badge {{ $problem->severity_badge_class }}">{{ $problem->severity_label }}</span>
                                @endif
                                @if($problem->onset_date)
                                    <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $problem->onset_date->format('M d, Y') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($patient->problems->count() > 5)
                <div class="p-3 text-center border-top">
                    <a href="{{ route('clinic.patients.all-problems', $patient->file_number) }}" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-list me-2"></i>
                        {{ __('translation.view_all') }} ({{ $patient->problems->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-clipboard-list mb-3" style="font-size: 3rem; opacity: 0.2;"></i>
                <p class="mb-2">{{ __('translation.problem_list.no_records') }}</p>
                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addProblemModal">
                    <i class="fas fa-plus me-1"></i>{{ __('translation.problem_list.add') }}
                </button>
            </div>
        @endif
    </div>
</div>
