@extends('layout.home.main')

@section('meta')
    @include('layout.extra_meta')
@endsection

@section('page_title', __('translation.working_hours.title') . ' - ' . config('app.name'))

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-1">{{ __('translation.working_hours.title') }}</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('clinic.workspace') }}">{{ __('translation.clinic.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('translation.working_hours.title') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-primary me-2"></i>
                        {{ __('translation.working_hours.schedule') }}
                    </h5>
                    <p class="text-muted small mt-1 mb-0">{{ __('translation.working_hours.description') }}</p>
                </div>
                <div class="card-body pt-4">
                    <form action="{{ route('clinic.working-hours.store') }}" method="POST" id="workingHoursForm">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-medium">{{ __('translation.working_hours.default_slot_duration') }}</label>
                            <div class="input-group" style="max-width: 200px;">
                                <input type="number" name="default_slot_duration" class="form-control" value="{{ $defaultSlotDuration ?? 30 }}" min="5" max="120" step="5">
                                <span class="input-group-text">{{ __('translation.working_hours.minutes') }}</span>
                            </div>
                        </div>

                        @php
                            $days = [
                                0 => __('translation.days.sunday'),
                                1 => __('translation.days.monday'),
                                2 => __('translation.days.tuesday'),
                                3 => __('translation.days.wednesday'),
                                4 => __('translation.days.thursday'),
                                5 => __('translation.days.friday'),
                                6 => __('translation.days.saturday'),
                            ];
                        @endphp

                        @foreach($days as $dayIndex => $dayName)
                            @php
                                $dayHours = $workingHours->where('day_of_week', $dayIndex)->first();
                            @endphp
                            <div class="card border rounded-3 mb-3">
                                <div class="card-body p-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input day-toggle" type="checkbox"
                                                    id="day_{{ $dayIndex }}_active"
                                                    name="days[{{ $dayIndex }}][is_active]"
                                                    value="1"
                                                    {{ $dayHours && $dayHours->is_active ? 'checked' : '' }}
                                                    onchange="toggleDayFields({{ $dayIndex }})">
                                                <label class="form-check-label fw-semibold" for="day_{{ $dayIndex }}_active">
                                                    {{ $dayName }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="day_{{ $dayIndex }}_start_wrap">
                                            <label class="form-label small text-muted mb-1">{{ __('translation.working_hours.start_time') }}</label>
                                            <input type="time" name="days[{{ $dayIndex }}][start_time]"
                                                id="day_{{ $dayIndex }}_start"
                                                class="form-control form-control-sm"
                                                value="{{ $dayHours ? \Carbon\Carbon::parse($dayHours->start_time)->format('H:i') : '09:00' }}"
                                                {{ !$dayHours || !$dayHours->is_active ? 'disabled' : '' }}>
                                        </div>
                                        <div class="col-md-3" id="day_{{ $dayIndex }}_end_wrap">
                                            <label class="form-label small text-muted mb-1">{{ __('translation.working_hours.end_time') }}</label>
                                            <input type="time" name="days[{{ $dayIndex }}][end_time]"
                                                id="day_{{ $dayIndex }}_end"
                                                class="form-control form-control-sm"
                                                value="{{ $dayHours ? \Carbon\Carbon::parse($dayHours->end_time)->format('H:i') : '17:00' }}"
                                                {{ !$dayHours || !$dayHours->is_active ? 'disabled' : '' }}>
                                        </div>
                                        <div class="col-md-2" id="day_{{ $dayIndex }}_slot_wrap">
                                            <label class="form-label small text-muted mb-1">{{ __('translation.working_hours.slot_duration') }}</label>
                                            <div class="input-group input-group-sm">
                                                <input type="number" name="days[{{ $dayIndex }}][slot_duration]"
                                                    id="day_{{ $dayIndex }}_slot"
                                                    class="form-control form-control-sm"
                                                    value="{{ $dayHours ? $dayHours->slot_duration : 30 }}"
                                                    min="5" max="120" step="5"
                                                    {{ !$dayHours || !$dayHours->is_active ? 'disabled' : '' }}>
                                                <span class="input-group-text">{{ __('translation.working_hours.min') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center" id="day_{{ $dayIndex }}_status">
                                            @if($dayHours && $dayHours->is_active)
                                                <span class="badge bg-success">{{ __('translation.working_hours.open') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('translation.working_hours.closed') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('translation.common.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDayFields(dayIndex) {
    const isActive = document.getElementById(`day_${dayIndex}_active`).checked;
    const fields = ['start', 'end', 'slot'];
    fields.forEach(field => {
        const input = document.getElementById(`day_${dayIndex}_${field}`);
        if (input) {
            input.disabled = !isActive;
        }
    });

    const statusEl = document.getElementById(`day_${dayIndex}_status`);
    if (statusEl) {
        statusEl.innerHTML = isActive
            ? '<span class="badge bg-success">{{ __("translation.working_hours.open") }}</span>'
            : '<span class="badge bg-secondary">{{ __("translation.working_hours.closed") }}</span>';
    }
}

// Handle form submit via AJAX
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('workingHoursForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            window.handleFormSubmit(e, this);
        });
    }
});
</script>
@endpush
