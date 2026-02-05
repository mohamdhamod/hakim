@extends('layout.home.main')

@section('title', __('translation.examination.examinations'))

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">{{ __('translation.examination.examinations') }}</h1>
            <p class="text-muted mb-0">{{ __('translation.examination.manage_examinations') }}</p>
        </div>
        <a href="{{ route('clinic.patients.index') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>{{ __('translation.examination.new') }}
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('translation.common.search') }}</label>
                    <input type="text" id="searchExaminations" class="form-control form-control-sm" placeholder="{{ __('translation.examination.search_placeholder') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('translation.examination.status_label') }}</label>
                    <select id="filterStatus" class="form-select form-select-sm">
                        <option value="">{{ __('translation.common.all') }}</option>
                        <option value="scheduled">{{ __('translation.examination.status.scheduled') }}</option>
                        <option value="in_progress">{{ __('translation.examination.status.in_progress') }}</option>
                        <option value="completed">{{ __('translation.examination.status.completed') }}</option>
                        <option value="cancelled">{{ __('translation.examination.status.cancelled') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">{{ __('translation.common.date_range') }}</label>
                    <select id="filterDate" class="form-select form-select-sm">
                        <option value="">{{ __('translation.common.all_time') }}</option>
                        <option value="today">{{ __('translation.common.today') }}</option>
                        <option value="week">{{ __('translation.common.this_week') }}</option>
                        <option value="month">{{ __('translation.common.this_month') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-sm btn-outline-secondary w-100" id="resetFilters">
                        <i class="fas fa-redo me-1"></i>{{ __('translation.common.reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Container -->
    <div id="examinationsContainer">
        @include('clinic.examinations.partials.examinations-list', ['examinations' => $examinations])
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchExaminations');
    const filterStatus = document.getElementById('filterStatus');
    const filterDate = document.getElementById('filterDate');
    const resetBtn = document.getElementById('resetFilters');
    const container = document.getElementById('examinationsContainer');
    let debounceTimer;
    
    [searchInput, filterStatus, filterDate].forEach(el => {
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
        filterStatus.value = '';
        filterDate.value = '';
        applyFilters();
    });
    
    async function applyFilters() {
        const params = new URLSearchParams({
            search: searchInput.value,
            status: filterStatus.value,
            date: filterDate.value
        });
        
        container.style.opacity = '0.5';
        container.style.pointerEvents = 'none';
        
        try {
            const html = await ApiClient.getHtml(`{{ route('clinic.examinations.index') }}?${params}`);
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('examinationsContainer');
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
