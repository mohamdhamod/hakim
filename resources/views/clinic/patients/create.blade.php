@extends('layout.home.main')

@section('title', __('translation.patient.add_new'))

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.workspace') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('clinic.patients.index') }}">{{ __('translation.patient.patients') }}</a></li>
            <li class="breadcrumb-item active">{{ __('translation.patient.add_new') }}</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold mb-1">{{ __('translation.patient.add_new') }}</h1>
        <p class="text-muted mb-0">{{ __('translation.patient.fill_basic_info') }}</p>
    </div>

    <form id="patient-form" action="{{ route('clinic.patients.store') }}" method="POST" data-recaptcha-skip="1">
        @csrf
        
        <div class="row justify-content-center">
            {{-- Basic Information Only --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-user text-primary me-2"></i>
                            {{ __('translation.patient.basic_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.file_number') }}</label>
                                <input type="text" class="form-control bg-light" value="{{ $fileNumber }}" readonly>
                                <small class="text-muted">{{ __('translation.patient.file_number_auto') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.full_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">{{ __('translation.patient.birth_year') }}</label>
                                <select name="birth_year" id="birth_year" class="form-select choices-select @error('date_of_birth') is-invalid @enderror">
                                    <option value="">{{ __('translation.patient.select_year') }}</option>
                                    @for($year = date('Y'); $year >= 1920; $year--)
                                        <option value="{{ $year }}" {{ old('birth_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">{{ __('translation.patient.birth_month') }}</label>
                                <select name="birth_month" id="birth_month" class="form-select choices-select">
                                    <option value="">{{ __('translation.common.select') }}</option>
                                    @foreach([
                                        1 => __('translation.months_list.january'),
                                        2 => __('translation.months_list.february'),
                                        3 => __('translation.months_list.march'),
                                        4 => __('translation.months_list.april'),
                                        5 => __('translation.months_list.may'),
                                        6 => __('translation.months_list.june'),
                                        7 => __('translation.months_list.july'),
                                        8 => __('translation.months_list.august'),
                                        9 => __('translation.months_list.september'),
                                        10 => __('translation.months_list.october'),
                                        11 => __('translation.months_list.november'),
                                        12 => __('translation.months_list.december'),
                                    ] as $num => $name)
                                        <option value="{{ $num }}" {{ old('birth_month') == $num ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('translation.patient.gender') }}</label>
                                <select name="gender" class="form-select choices-select @error('gender') is-invalid @enderror">
                                    <option value="">{{ __('translation.common.select') }}</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('translation.patient.male') }}</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('translation.patient.female') }}</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('translation.patient.blood_type') }}</label>
                                <select name="blood_type" class="form-select choices-select @error('blood_type') is-invalid @enderror">
                                    <option value="">{{ __('translation.common.select') }}</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                        <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('blood_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.phone') }}</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('translation.patient.email') }}</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('translation.patient.address') }}</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('clinic.patients.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>{{ __('translation.common.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>{{ __('translation.patient.save') }}
                    </button>
                </div>

                {{-- Info Note --}}
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('translation.patient.additional_info_note') }}
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('patient-form');
    if (form && window.handleSubmit) {
        handleSubmit(form);
    }
});
</script>
@endpush
