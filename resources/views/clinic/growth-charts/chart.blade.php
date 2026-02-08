@extends('layouts.dashboard')

@section('title', __('translation.growth_chart') . ' - ' . $patient->full_name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <i class="fas fa-chart-line text-info me-2"></i>{{ __('translation.growth_chart') }}
        </h3>
        <a href="{{ route('clinic.patients.show', $patient) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('translation.back_to_patient') }}
        </a>
    </div>

    {{-- Patient Info Card --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>{{ __('translation.patient_name') }}:</strong> {{ $patient->full_name }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('translation.age') }}:</strong> {{ $patient->age }} {{ __('translation.years') }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('translation.gender') }}:</strong> {{ __('translation.' . $patient->gender) }}
                </div>
                <div class="col-md-3">
                    <strong>{{ __('translation.measurements') }}:</strong> {{ $measurements->count() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row">
        {{-- Weight Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-weight me-2"></i>{{ __('translation.weight_chart') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="weightChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- Height Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-ruler-vertical me-2"></i>{{ __('translation.height_chart') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="heightChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- BMI Chart --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-chart-area me-2"></i>{{ __('translation.bmi_chart') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="bmiChart" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- Head Circumference Chart (for children) --}}
        @if($patient->age < 6)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-circle-notch me-2"></i>{{ __('translation.head_circumference_chart') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="headChart" height="300"></canvas>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Data Table --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('translation.measurements_history') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('translation.date') }}</th>
                            <th>{{ __('translation.age') }}</th>
                            <th>{{ __('translation.weight') }} (kg)</th>
                            <th>{{ __('translation.height') }} (cm)</th>
                            <th>{{ __('translation.bmi') }}</th>
                            @if($patient->age < 6)
                            <th>{{ __('translation.head_circumference') }} (cm)</th>
                            @endif
                            <th>{{ __('translation.interpretation') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($measurements as $m)
                        <tr>
                            <td>{{ $m->measurement_date->format('Y-m-d') }}</td>
                            <td>{{ floor($m->age_months / 12) }}y {{ $m->age_months % 12 }}m</td>
                            <td>{{ $m->weight_kg ?? '-' }}</td>
                            <td>{{ $m->height_cm ?? '-' }}</td>
                            <td>{{ $m->bmi ? number_format($m->bmi, 1) : '-' }}</td>
                            @if($patient->age < 6)
                            <td>{{ $m->head_circumference_cm ?? '-' }}</td>
                            @endif
                            <td>
                                @if($m->interpretation === 'normal')
                                    <span class="badge bg-success">{{ __('translation.normal') }}</span>
                                @elseif($m->interpretation === 'monitor')
                                    <span class="badge bg-warning">{{ __('translation.monitor') }}</span>
                                @elseif($m->interpretation === 'attention_needed')
                                    <span class="badge bg-danger">{{ __('translation.attention_needed') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
const measurements = @json($measurements->map(function($m) {
    return [
        'date' => $m->measurement_date->format('Y-m-d'),
        'age_months' => $m->age_months,
        'weight' => $m->weight_kg,
        'height' => $m->height_cm,
        'bmi' => $m->bmi,
        'head' => $m->head_circumference_cm,
        'weight_percentile' => $m->weight_percentile,
        'height_percentile' => $m->height_percentile,
        'bmi_percentile' => $m->bmi_percentile,
        'head_percentile' => $m->head_circumference_percentile,
    ];
}));

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
        tooltip: {
            callbacks: {
                label: function(context) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    label += context.parsed.y.toFixed(1);
                    return label;
                }
            }
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Weight Chart
new Chart(document.getElementById('weightChart'), {
    type: 'line',
    data: {
        labels: measurements.map(m => m.date),
        datasets: [{
            label: '{{ __("translation.weight") }} (kg)',
            data: measurements.map(m => m.weight),
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: chartOptions
});

// Height Chart
new Chart(document.getElementById('heightChart'), {
    type: 'line',
    data: {
        labels: measurements.map(m => m.date),
        datasets: [{
            label: '{{ __("translation.height") }} (cm)',
            data: measurements.map(m => m.height),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: chartOptions
});

// BMI Chart
new Chart(document.getElementById('bmiChart'), {
    type: 'line',
    data: {
        labels: measurements.map(m => m.date),
        datasets: [{
            label: '{{ __("translation.bmi") }}',
            data: measurements.map(m => m.bmi),
            borderColor: 'rgb(255, 159, 64)',
            backgroundColor: 'rgba(255, 159, 64, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: chartOptions
});

@if($patient->age < 6)
// Head Circumference Chart
new Chart(document.getElementById('headChart'), {
    type: 'line',
    data: {
        labels: measurements.map(m => m.date),
        datasets: [{
            label: '{{ __("translation.head_circumference") }} (cm)',
            data: measurements.map(m => m.head),
            borderColor: 'rgb(153, 102, 255)',
            backgroundColor: 'rgba(153, 102, 255, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: chartOptions
});
@endif
</script>
@endpush
