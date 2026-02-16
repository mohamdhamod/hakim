{{-- Lab Tests Section --}}
<div class="card border-0 shadow-sm mb-4" id="lab-tests-section" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
    <div class="card-header bg-white border-0 py-3 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-flask text-primary me-2"></i>
            {{ __('translation.lab_tests') }}
            @if($patient->labTestResults && $patient->labTestResults->count() > 0)
                <span class="badge bg-primary ms-2">{{ $patient->labTestResults->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-primary" onclick="openLabTestCreate()">
            <i class="fas fa-plus me-2"></i>{{ __('translation.add_lab_test') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->labTestResults && $patient->labTestResults->count() > 0)
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('translation.test_name') }}</th>
                            <th>{{ __('translation.result') }}</th>
                            <th>{{ __('translation.normal_range') }}</th>
                            <th>{{ __('translation.test_date') }}</th>
                            <th>{{ __('translation.status') }}</th>
                            <th width="100">{{ __('translation.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($patient->labTestResults->take(5) as $labTest)
                            <tr>
                                <td>
                                    <strong>{{ $labTest->labTestType->name }}</strong>
                                    @if($labTest->labTestType->category)
                                        <br><small class="text-muted">{{ $labTest->labTestType->category }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $labTest->is_abnormal ? 'text-danger' : 'text-success' }}">
                                        {{ $labTest->result_value }} 
                                        @if($labTest->labTestType->unit)
                                            {{ $labTest->labTestType->unit }}
                                        @endif
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $labTest->labTestType->normal_range_text ?? 
                                       ($labTest->labTestType->normal_range_min && $labTest->labTestType->normal_range_max 
                                        ? $labTest->labTestType->normal_range_min . ' - ' . $labTest->labTestType->normal_range_max 
                                        : '-') }}
                                </td>
                                <td class="small">{{ $labTest->test_date->format('M d, Y') }}</td>
                                <td>
                                    @if($labTest->is_abnormal)
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="fas fa-exclamation-circle"></i> {{ __('translation.abnormal') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="fas fa-check-circle"></i> {{ __('translation.normal') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-nowrap">
                                        <button type="button" class="btn btn-sm btn-info view-btn" data-type="labTest" data-model='@json($labTest)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($labTest->clinic_id === $clinic->id)
                                        <button type="button" class="btn btn-sm btn-primary edit-btn" data-type="labTest" data-model='@json($labTest)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-type="labTest" data-model='@json($labTest)' data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('translation.common.delete') }}">
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
                @foreach($patient->labTestResults->take(5) as $labTest)
                    <div class="card mb-3 border rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $labTest->labTestType->name }}</h6>
                                    @if($labTest->labTestType->category)
                                        <small class="text-muted">{{ $labTest->labTestType->category }}</small>
                                    @endif
                                </div>
                                @if($labTest->is_abnormal)
                                    <span class="badge bg-danger-subtle text-danger">
                                        <i class="fas fa-exclamation-circle"></i> {{ __('translation.abnormal') }}
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="fas fa-check-circle"></i> {{ __('translation.normal') }}
                                    </span>
                                @endif
                            </div>
                            <div class="row g-2 small mt-2">
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.result') }}</span>
                                    <span class="fw-bold {{ $labTest->is_abnormal ? 'text-danger' : 'text-success' }}">
                                        {{ $labTest->result_value }}
                                        @if($labTest->labTestType->unit) {{ $labTest->labTestType->unit }} @endif
                                    </span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.normal_range') }}</span>
                                    {{ $labTest->labTestType->normal_range_text ?? ($labTest->labTestType->normal_range_min && $labTest->labTestType->normal_range_max ? $labTest->labTestType->normal_range_min . ' - ' . $labTest->labTestType->normal_range_max : '-') }}
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">{{ __('translation.test_date') }}</span>
                                    <i class="fas fa-calendar text-primary me-1"></i>{{ $labTest->test_date->format('M d, Y') }}
                                </div>
                            </div>
                            <div class="row g-1 mt-3 pt-2 border-top">
                                <div class="col-6">
                                    <button class="btn btn-sm btn-info w-100 view-btn" data-type="labTest" data-model='@json($labTest)'>
                                        <i class="fas fa-eye me-1"></i>{{ __('translation.common.view') }}
                                    </button>
                                </div>
                                @if($labTest->clinic_id === $clinic->id)
                                <div class="col-6">
                                    <button class="btn btn-sm btn-primary w-100 edit-btn" data-type="labTest" data-model='@json($labTest)'>
                                        <i class="fas fa-edit me-1"></i>{{ __('translation.common.edit') }}
                                    </button>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-sm btn-danger w-100 delete-btn" data-type="labTest" data-model='@json($labTest)'>
                                        <i class="fas fa-trash me-1"></i>{{ __('translation.common.delete') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($patient->labTestResults->count() > 5)
                <div class="p-3 text-center border-top">
                    <a href="{{ route('clinic.patients.all-lab-tests', $patient->file_number) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-list me-2"></i>
                        {{ __('translation.view_all') }} ({{ $patient->labTestResults->count() }})
                    </a>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div style="font-size: 3rem; opacity: 0.2; margin-bottom: 1rem;">🧪</div>
                <h6 class="text-muted mb-2">{{ __('translation.no_lab_tests') }}</h6>
                <p class="small text-muted mb-3">{{ __('translation.track_patient_lab_results') }}</p>
                <button type="button" class="btn btn-sm btn-primary" onclick="openLabTestCreate()">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.add_lab_test') }}
                </button>
            </div>
        @endif
    </div>
</div>
