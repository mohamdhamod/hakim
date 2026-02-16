@extends('layout.home.main')

@section('title', __('translation.patient.patients'))

@section('content')
<div class="container py-4">
    {{-- Search Card --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-2">
            <div class="input-group">
                <span class="input-group-text bg-primary bg-opacity-10 border-0 rounded-start-3 px-3">
                    <i class="fas fa-search text-primary small"></i>
                </span>
                <input type="text" id="searchPatients" class="form-control border-0 shadow-none" placeholder="{{ __('translation.patient.search_placeholder') }}">
            </div>
        </div>
    </div>

    {{-- Patients Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="fas fa-users text-primary me-2"></i>
                    {{ __('translation.patient.patients') }}
                </h5>
                <a href="{{ route('clinic.patients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle me-1"></i>{{ __('translation.patient.add_new') }}
                </a>
            </div>
        </div>

        {{-- Results Container --}}
        <div class="card-body p-0" id="patientsContainer">
            @include('clinic.patients.partials.patients-grid', ['patients' => $patients])
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const searchInput = document.getElementById('searchPatients');
    const container = document.getElementById('patientsContainer');
    if (!searchInput || !container) return;

    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(loadPatients, 400);
    });

    async function loadPatients() {
        const params = new URLSearchParams();
        if (searchInput.value.trim()) params.set('search', searchInput.value.trim());
        const qs = params.toString() ? `?${params}` : '';

        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';

        try {
            const html = await ApiClient.getHtml(`{{ route('clinic.patients.index') }}${qs}`);
            container.innerHTML = html;
        } catch (error) {
            console.error('Patient search error:', error);
        } finally {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
    }
})();
</script>
@endpush
@endsection
