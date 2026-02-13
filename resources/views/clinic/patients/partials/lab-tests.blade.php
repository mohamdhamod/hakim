{{-- Lab Tests Section --}}
<div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea10 0%, #764ba210 100%);">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="fas fa-flask text-primary me-2"></i>
            {{ __('translation.lab_tests') }}
            @if($patient->labTestResults && $patient->labTestResults->count() > 0)
                <span class="badge bg-primary ms-2">{{ $patient->labTestResults->count() }}</span>
            @endif
        </h5>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newLabTestModal">
            <i class="fas fa-plus me-2"></i>{{ __('translation.add_lab_test') }}
        </button>
    </div>
    <div class="card-body p-0">
        @if($patient->labTestResults && $patient->labTestResults->count() > 0)
            <div class="table-responsive">
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
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewLabTest({{ $labTest->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newLabTestModal">
                    <i class="fas fa-plus me-2"></i>{{ __('translation.add_lab_test') }}
                </button>
            </div>
        @endif
    </div>
</div>
