@extends('layout.home.main')

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">{{ __('translation.examination.details') }}: {{ $examination->examination_number }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinic.examinations.index') }}">{{ __('translation.examination.examinations') }}</a></li>
                        <li class="breadcrumb-item active">{{ $examination->examination_number }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('clinic.examinations.edit', $examination->id) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil me-2"></i>{{ __('translation.common.edit') }}
                </a>
                <a href="{{ route('clinic.examinations.print', $examination->id) }}" class="btn btn-secondary" target="_blank">
                    <i class="bi bi-printer me-2"></i>{{ __('translation.common.print') }}
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Main Content --}}
            <div class="col-lg-8">
                {{-- Patient Info --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-primary text-white border-0 py-3 d-flex justify-content-between">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person me-2"></i>
                            {{ __('translation.examination.patient_info') }}
                        </h5>
                        <a href="{{ route('clinic.patients.show', $examination->patient->file_number) }}" class="btn btn-sm btn-light">
                            {{ __('translation.patient.view_profile') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>{{ __('translation.patient.file_number') }}:</strong>
                                <p class="text-primary">{{ $examination->patient->file_number }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __('translation.patient.name') }}:</strong>
                                <p>{{ $examination->patient->full_name }}</p>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __('translation.patient.age') }}:</strong>
                                <p>{{ $examination->patient->age ?? '-' }} {{ $examination->patient->age ? __('translation.common.years') : '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chief Complaint --}}
                @if($examination->chief_complaint || $examination->present_illness_history)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-chat-left-text text-info me-2"></i>
                            {{ __('translation.examination.chief_complaint') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($examination->chief_complaint)
                            <div class="mb-3">
                                <strong>{{ __('translation.examination.chief_complaint') }}:</strong>
                                <p class="mb-0">{{ $examination->chief_complaint }}</p>
                            </div>
                        @endif
                        @if($examination->present_illness_history)
                            <div>
                                <strong>{{ __('translation.examination.present_illness_history') }}:</strong>
                                <p class="mb-0">{{ $examination->present_illness_history }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Vital Signs --}}
                @if($examination->temperature || $examination->blood_pressure || $examination->pulse_rate)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-heart-pulse text-danger me-2"></i>
                            {{ __('translation.examination.vital_signs') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if($examination->temperature)
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-thermometer-half text-danger fs-4"></i>
                                    <h4 class="mb-0 mt-2">{{ $examination->temperature }}°C</h4>
                                    <small class="text-muted">{{ __('translation.examination.temperature') }}</small>
                                </div>
                            </div>
                            @endif
                            @if($examination->blood_pressure)
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-activity text-primary fs-4"></i>
                                    <h4 class="mb-0 mt-2">{{ $examination->blood_pressure }}</h4>
                                    <small class="text-muted">{{ __('translation.examination.blood_pressure') }}</small>
                                </div>
                            </div>
                            @endif
                            @if($examination->pulse_rate)
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-heart text-danger fs-4"></i>
                                    <h4 class="mb-0 mt-2">{{ $examination->pulse_rate }}</h4>
                                    <small class="text-muted">{{ __('translation.examination.pulse_rate') }}</small>
                                </div>
                            </div>
                            @endif
                            @if($examination->oxygen_saturation)
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-lungs text-info fs-4"></i>
                                    <h4 class="mb-0 mt-2">{{ $examination->oxygen_saturation }}%</h4>
                                    <small class="text-muted">{{ __('translation.examination.oxygen_saturation') }}</small>
                                </div>
                            </div>
                            @endif
                            @if($examination->weight)
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-speedometer fs-4"></i>
                                    <h4 class="mb-0 mt-2">{{ $examination->weight }} kg</h4>
                                    <small class="text-muted">{{ __('translation.examination.weight') }}</small>
                                </div>
                            </div>
                            @endif
                            @if($examination->height)
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-rulers fs-4"></i>
                                    <h4 class="mb-0 mt-2">{{ $examination->height }} cm</h4>
                                    <small class="text-muted">{{ __('translation.examination.height') }}</small>
                                </div>
                            </div>
                            @endif
                            @if($examination->bmi)
                            <div class="col-md-3">
                                <div class="border rounded p-3 text-center">
                                    <i class="bi bi-calculator fs-4"></i>
                                    <h4 class="mb-0 mt-2">{{ $examination->bmi }}</h4>
                                    <small class="text-muted">{{ __('translation.examination.bmi') }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Physical Examination --}}
                @if($examination->physical_examination)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-search text-primary me-2"></i>
                            {{ __('translation.examination.physical_examination') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $examination->physical_examination }}</p>
                    </div>
                </div>
                @endif

                {{-- Diagnosis & Treatment --}}
                @if($examination->diagnosis || $examination->icd_code || $examination->treatment_plan || $examination->prescriptions)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-clipboard-check text-success me-2"></i>
                            {{ __('translation.examination.diagnosis_treatment') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($examination->diagnosis)
                            <div class="mb-4">
                                <strong>{{ __('translation.examination.diagnosis') }}:</strong>
                                <p class="mb-0 mt-2" style="white-space: pre-wrap;">{{ $examination->diagnosis }}</p>
                            </div>
                        @endif
                        @if($examination->icd_code)
                            <div class="mb-4">
                                <strong>{{ __('translation.examination.icd_code') }}:</strong>
                                <p class="mb-0 mt-2">
                                    <span class="badge bg-primary">{{ $examination->icd_code }}</span>
                                    <a href="https://icd.who.int/browse/2025-01/mms/en#{{ $examination->icd_code }}" target="_blank" class="text-info ms-2 small">
                                        <i class="bi bi-box-arrow-up-right"></i> {{ __('translation.examination.view_icd_details') }}
                                    </a>
                                </p>
                            </div>
                        @endif
                        @if($examination->treatment_plan)
                            <div class="mb-4">
                                <strong>{{ __('translation.examination.treatment_plan') }}:</strong>
                                <p class="mb-0 mt-2" style="white-space: pre-wrap;">{{ $examination->treatment_plan }}</p>
                            </div>
                        @endif
                        @if($examination->prescriptions)
                            <div class="alert alert-info">
                                <strong><i class="bi bi-capsule me-2"></i>{{ __('translation.examination.prescriptions') }}:</strong>
                                <p class="mb-0 mt-2" style="white-space: pre-wrap;">{{ $examination->prescriptions }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Lab & Imaging --}}
                @if($examination->lab_tests_ordered || $examination->lab_tests_results || $examination->imaging_ordered || $examination->imaging_results)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-file-medical text-warning me-2"></i>
                            {{ __('translation.examination.lab_imaging') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                @if($examination->lab_tests_ordered)
                                    <div class="mb-3">
                                        <strong>{{ __('translation.examination.lab_tests_ordered') }}:</strong>
                                        <p class="mb-0 mt-1" style="white-space: pre-wrap;">{{ $examination->lab_tests_ordered }}</p>
                                    </div>
                                @endif
                                @if($examination->lab_tests_results)
                                    <div class="mb-3">
                                        <strong>{{ __('translation.examination.lab_tests_results') }}:</strong>
                                        <p class="mb-0 mt-1" style="white-space: pre-wrap;">{{ $examination->lab_tests_results }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if($examination->imaging_ordered)
                                    <div class="mb-3">
                                        <strong>{{ __('translation.examination.imaging_ordered') }}:</strong>
                                        <p class="mb-0 mt-1" style="white-space: pre-wrap;">{{ $examination->imaging_ordered }}</p>
                                    </div>
                                @endif
                                @if($examination->imaging_results)
                                    <div class="mb-3">
                                        <strong>{{ __('translation.examination.imaging_results') }}:</strong>
                                        <p class="mb-0 mt-1" style="white-space: pre-wrap;">{{ $examination->imaging_results }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Examination Info --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle text-primary me-2"></i>
                            {{ __('translation.examination.info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th>{{ __('translation.examination.number') }}:</th>
                                <td>{{ $examination->examination_number }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('translation.examination.date') }}:</th>
                                <td>{{ $examination->examination_date->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('translation.examination.status_label') }}:</th>
                                <td><span class="badge {{ $examination->status_badge_class }}">{{ $examination->status_label }}</span></td>
                            </tr>
                            <tr>
                                <th>{{ __('translation.examination.doctor') }}:</th>
                                <td>{{ $examination->doctor->name }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Follow-up --}}
                @if($examination->follow_up_date || $examination->follow_up_notes)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-warning border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-calendar-event me-2"></i>
                            {{ __('translation.examination.follow_up') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($examination->follow_up_date)
                            <div class="mb-2">
                                <strong>{{ __('translation.examination.follow_up_date') }}:</strong>
                                <p class="mb-0 text-primary">{{ $examination->follow_up_date->format('Y-m-d') }}</p>
                            </div>
                        @endif
                        @if($examination->follow_up_notes)
                            <div>
                                <strong>{{ __('translation.examination.follow_up_notes') }}:</strong>
                                <p class="mb-0">{{ $examination->follow_up_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Doctor's Notes --}}
                @if($examination->doctor_notes)
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-info text-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-journal-text me-2"></i>
                            {{ __('translation.examination.doctor_notes') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $examination->doctor_notes }}</p>
                    </div>
                </div>
                @endif

                {{-- Quick Actions --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('clinic.patients.show', $examination->patient->file_number) }}" class="btn btn-success">
                                <i class="bi bi-plus-lg me-2"></i>{{ __('translation.examination.new_for_patient') }}
                            </a>
                            @if($examination->status !== 'completed')
                                <button type="button" class="btn btn-primary complete-btn" data-id="{{ $examination->id }}">
                                    <i class="bi bi-check-circle me-2"></i>{{ __('translation.examination.mark_completed') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.complete-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const confirmed = await SwalUtil.confirm(
                '{{ __("translation.examination.confirm_complete") }}',
                '',
                { confirmButtonText: '{{ __("translation.common.yes") }}', cancelButtonText: '{{ __("translation.common.no") }}' }
            );
            
            if (!confirmed.isConfirmed) return;
            
            try {
                const data = await ApiClient.post('{{ route('clinic.examinations.complete', $examination) }}');
                if (data.success) {
                    SwalUtil.toast(data.message || '{{ __("translation.examination.completed_successfully") }}', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    SwalUtil.toast(data.message || '{{ __("translation.common.error_occurred") }}', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                SwalUtil.toast('{{ __("translation.common.error_occurred") }}', 'error');
            }
        });
    });
});
</script>
@endpush
