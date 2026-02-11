@extends('layout.main')
@include('layout.extra_meta')

@section('content')

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-hospital me-2"></i> {{ __('translation.clinic.clinics') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.clinic.management') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.clinic.management_description') }}
                </p>
            </div>
        </div>

        <!-- Clinics Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('translation.clinic.clinics') }}</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('clinics.pending') }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-clock-history me-2"></i> {{ __('translation.clinic.pending_approvals') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($clinics->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">{{ __('translation.clinic.icon') }}</th>
                                            <th>{{ __('translation.clinic.name') }}</th>
                                            <th>{{ __('translation.clinic.doctor') }}</th>
                                            <th>{{ __('translation.clinic.specialty') }}</th>
                                            <th>{{ __('translation.clinic.patients') }}</th>
                                            <th>{{ __('translation.clinic.status_label') }}</th>
                                            <th>{{ __('translation.common.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($clinics as $clinic)
                                        <tr>
                                            <td>
                                                @if($clinic->logo)
                                                    <div class="rounded-circle overflow-hidden" style="width: 40px; height: 40px;">
                                                        <img src="{{ $clinic->logo_path }}" alt="{{ $clinic->display_name }}" 
                                                             class="w-100 h-100 object-fit-cover">
                                                    </div>
                                                @else
                                                    <span class="d-flex align-items-center justify-content-center rounded-circle" 
                                                          style="width: 40px; height: 40px; background-color: {{ $clinic->specialty->color ?? '#6c757d' }}20;">
                                                        <i class="fas {{ $clinic->specialty->icon ?? 'fa-hospital' }}" style="color: {{ $clinic->specialty->color ?? '#6c757d' }};"></i>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $clinic->display_name }}</strong>
                                                    @if($clinic->address)
                                                        <br><small class="text-muted">{{ Str::limit($clinic->address, 40) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $clinic->doctor->name ?? '-' }}</strong>
                                                    <br><small class="text-muted">{{ $clinic->doctor->email ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($clinic->specialty)
                                                    <span class="badge" style="background-color: {{ $clinic->specialty->color ?? '#6c757d' }};">
                                                        {{ $clinic->specialty->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $clinic->patients_count }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $clinic->status_badge_class }}">{{ $clinic->status_label }}</span>
                                            </td>
                                            <td class="actions">
                                                <!-- View button -->
                                                <a href="{{ route('clinics.show', $clinic->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-placement="top"
                                                   title="{{ __('translation.common.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if($clinic->status === 'pending')
                                                    <!-- Approve button -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-success approve-btn"
                                                            data-id="{{ $clinic->id }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ __('translation.common.approve') }}">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>

                                                    <!-- Reject button -->
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger reject-btn"
                                                            data-id="{{ $clinic->id }}"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="{{ __('translation.common.reject') }}">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $clinics->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-hospital display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.clinic.no_clinics_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.clinic.no_clinics_found_message') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">{{ __('translation.clinic.reject_clinic') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reject-form">
                        <input type="hidden" id="reject-clinic-id">
                        <div class="mb-3">
                            <label class="form-label">{{ __('translation.clinic.rejection_reason') }} <span class="text-danger">*</span></label>
                            <textarea id="reject-reason" class="form-control" rows="4" required placeholder="{{ __('translation.clinic.rejection_reason_placeholder') }}"></textarea>
                            <small class="text-muted">{{ __('translation.clinic.rejection_reason_help') }}</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm-reject">
                        <i class="bi bi-x-lg me-2"></i>{{ __('translation.common.reject') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));

    // Approve handler
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            
            const result = await SwalHelper.confirm(
                '{{ __('translation.clinic.confirm_approve') }}',
                '{{ __('translation.clinic.approve_clinic_message') }}',
                {
                    icon: 'question',
                    confirmButtonText: '{{ __('translation.common.approve') }}',
                    cancelButtonText: '{{ __('translation.common.cancel') }}',
                    confirmButtonColor: '#198754'
                }
            );
            
            if (!result.isConfirmed) return;
            
            SwalHelper.showLoading('{{ __('translation.common.processing') }}');
            
            try {
                const response = await fetch('{{ url('/' . app()->getLocale() . '/dashboard/clinics') }}/' + id + '/approve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await SwalHelper.success('{{ __('translation.common.success') }}', data.message);
                    window.location.reload();
                } else {
                    SwalHelper.error('{{ __('translation.common.error') }}', data.message || '{{ __('translation.common.error') }}');
                }
            } catch (error) {
                console.error('Error:', error);
                SwalHelper.error('{{ __('translation.common.error') }}', error.message || '{{ __('translation.common.error') }}');
            }
        });
    });

    // Reject handler - open modal
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('reject-clinic-id').value = this.dataset.id;
            document.getElementById('reject-reason').value = '';
            rejectModal.show();
        });
    });

    // Confirm reject
    document.getElementById('confirm-reject').addEventListener('click', async function() {
        const id = document.getElementById('reject-clinic-id').value;
        const reason = document.getElementById('reject-reason').value;
        
        if (!reason.trim()) {
            SwalHelper.error('{{ __('translation.common.error') }}', '{{ __('translation.clinic.reason_required') }}');
            return;
        }

        SwalHelper.showLoading('{{ __('translation.common.processing') }}');

        try {
            const response = await fetch('{{ url('/' . app()->getLocale() . '/dashboard/clinics') }}/' + id + '/reject', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ reason: reason })
            });
            
            const data = await response.json();
            
            if (data.success) {
                rejectModal.hide();
                await SwalHelper.success('{{ __('translation.common.success') }}', data.message);
                window.location.reload();
            } else {
                SwalHelper.error('{{ __('translation.common.error') }}', data.message || '{{ __('translation.common.error') }}');
            }
        } catch (error) {
            console.error('Error:', error);
            SwalHelper.error('{{ __('translation.common.error') }}', error.message || '{{ __('translation.common.error') }}');
        }
    });
});
</script>
@endpush
