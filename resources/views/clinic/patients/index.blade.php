@extends('layout.home.main')

@section('title', __('translation.patient.patients'))

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.patient.management') }}</h1>
            <p class="text-muted mb-0">{{ __('translation.patient.manage_clinic_patients') }}</p>
        </div>
        <a href="{{ route('clinic.patients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>{{ __('translation.patient.add_new') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted">{{ __('translation.common.search') }}</label>
                    <input type="text" id="searchPatients" class="form-control form-control-sm" placeholder="{{ __('translation.patient.search_placeholder') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('translation.patient.gender') }}</label>
                    <select id="filterGender" class="form-select form-select-sm choices-select">
                        <option value="">{{ __('translation.common.all') }}</option>
                        <option value="male">{{ __('translation.patient.male') }}</option>
                        <option value="female">{{ __('translation.patient.female') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('translation.common.sort_by') }}</label>
                    <select id="sortBy" class="form-select form-select-sm choices-select">
                        <option value="recent">{{ __('translation.common.recent') }}</option>
                        <option value="name">{{ __('translation.patient.name') }}</option>
                        <option value="visits">{{ __('translation.patient.most_visits') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-outline-secondary w-100" id="resetFilters">
                        <i class="fas fa-redo me-1"></i>{{ __('translation.common.reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div id="patientsContainer">
        @include('clinic.patients.partials.patients-grid', ['patients' => $patients])
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPatients');
    const filterGender = document.getElementById('filterGender');
    const sortBy = document.getElementById('sortBy');
    const resetBtn = document.getElementById('resetFilters');
    const container = document.getElementById('patientsContainer');
    let debounceTimer;
    
    // Apply filters on change
    [searchInput, filterGender, sortBy].forEach(el => {
        el.addEventListener('change', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applyFilters, 300);
        });
    });
    
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 500);
    });
    
    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        filterGender.value = '';
        sortBy.value = 'recent';
        applyFilters();
    });
    
    async function applyFilters() {
        const params = new URLSearchParams({
            search: searchInput.value,
            gender: filterGender.value,
            sort: sortBy.value
        });
        
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
        
        try {
            const html = await ApiClient.getHtml(`{{ route('clinic.patients.index') }}?${params}`);
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('patientsContainer');
            if (newContent) container.innerHTML = newContent.innerHTML;
        } catch (error) {
            console.error('Filter error:', error);
        } finally {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
    }
});
</script>
@endpush
@endsection
