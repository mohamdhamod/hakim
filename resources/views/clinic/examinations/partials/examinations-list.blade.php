{{-- Examinations List --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 px-4 py-3">{{ __('translation.examination.number') }}</th>
                        <th class="border-0 py-3">{{ __('translation.patient.name') }}</th>
                        <th class="border-0 py-3">{{ __('translation.examination.date') }}</th>
                        <th class="border-0 py-3">{{ __('translation.examination.diagnosis') }}</th>
                        <th class="border-0 py-3">{{ __('translation.examination.status_label') }}</th>
                        <th class="border-0 py-3 text-end pe-4">{{ __('translation.common.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examinations as $examination)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="badge bg-secondary">{{ $examination->examination_number }}</span>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-info-subtle text-info me-2" style="width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $examination->patient->name }}</div>
                                        <small class="text-muted">{{ $examination->patient->file_number }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div>{{ $examination->examination_date->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $examination->examination_date->format('H:i') }}</small>
                            </td>
                            <td class="py-3">
                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $examination->diagnosis }}">
                                    {{ $examination->diagnosis ?: '-' }}
                                </span>
                            </td>
                            <td class="py-3">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'info',
                                        'in_progress' => 'warning',
                                        'completed' => 'success',
                                        'cancelled' => 'danger'
                                    ];
                                    $color = $statusColors[$examination->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}-subtle text-{{ $color }}">
                                    {{ __('translation.examination.status.' . $examination->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('clinic.patients.show', ['patient' => $examination->patient->file_number, 'tab' => 'examinations', 'examination' => $examination->id]) }}" class="btn btn-outline-primary" title="{{ __('translation.common.view') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($examination->status !== 'completed')
                                    <a href="{{ route('clinic.examinations.edit', $examination->id) }}" class="btn btn-outline-secondary" title="{{ __('translation.common.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    <a href="{{ route('clinic.examinations.print', $examination->id) }}" class="btn btn-outline-info" title="{{ __('translation.common.print') }}" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-clipboard-list text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                <h5 class="mt-3 text-muted">{{ __('translation.examination.no_examinations') }}</h5>
                                <p class="text-muted">{{ __('translation.examination.add_first_examination') }}</p>
                                <a href="{{ route('clinic.patients.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i>{{ __('translation.examination.new') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($examinations->hasPages())
    <div class="mt-4">
        {{ $examinations->links() }}
    </div>
@endif
