@extends('layout.home.main')

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('page_title', __('translation.clinic.settings') . ' - ' . config('app.name'))

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-1">{{ __('translation.clinic.settings') }}</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translation.clinic.settings') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0">
                        <i class="bi bi-building me-2"></i>
                        {{ __('translation.clinic.info') }}
                    </h5>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('clinic.settings.update') }}" method="POST" id="settings-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">
                                {{ __('translation.clinic.clinic_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $clinic->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label fw-medium">
                                {{ __('translation.clinic.address') }}
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3">{{ old('address', $clinic->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="phone" class="form-label fw-medium">
                                    {{ __('translation.clinic.phone') }}
                                </label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $clinic->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-medium">
                                    {{ __('translation.clinic.email') }}
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       value="{{ $clinic->doctor->email }}"
                                       disabled>
                                <small class="text-muted">{{ __('translation.common.linked_to_account') }}</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-medium">
                                {{ __('translation.clinic.description') }}
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4">{{ old('description', $clinic->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>
                                {{ __('translation.common.save_changes') }}
                            </button>
                            <a href="{{ route('clinic.workspace') }}" class="btn btn-outline-secondary">
                                {{ __('translation.common.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        {{ __('translation.clinic.statistics') }}
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">{{ __('translation.clinic.total_patients') }}</span>
                            <span class="fw-medium">{{ $clinic->patients_count ?? $clinic->patients()->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">{{ __('translation.clinic.total_examinations') }}</span>
                            <span class="fw-medium">{{ $clinic->examinations_count ?? $clinic->examinations()->count() }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">{{ __('translation.clinic.created_at') }}</span>
                            <span class="fw-medium">{{ $clinic->created_at->format('Y-m-d') }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">{{ __('translation.clinic.approved_at') }}</span>
                            <span class="fw-medium">{{ $clinic->approved_at ? $clinic->approved_at->format('Y-m-d') : '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0">
                        <i class="bi bi-person me-2"></i>
                        {{ __('translation.clinic.doctor_info') }}
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">{{ __('translation.user.name') }}</span>
                            <span class="fw-medium">{{ $clinic->doctor->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">{{ __('translation.user.email') }}</span>
                            <span class="fw-medium">{{ $clinic->doctor->email }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">{{ __('translation.user.registered_at') }}</span>
                            <span class="fw-medium">{{ $clinic->doctor->created_at->format('Y-m-d') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('settings-form').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('translation.common.saving') }}';
});
</script>
@endpush
