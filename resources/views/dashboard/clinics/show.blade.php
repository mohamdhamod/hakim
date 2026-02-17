@extends('layout.main')
@include('layout.extra_meta')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">{{ $clinic->display_name }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('translation.dashboard.title') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinics.index') }}">{{ __('translation.clinic.clinics') }}</a></li>
                        <li class="breadcrumb-item active">{{ $clinic->display_name }}</li>
                    </ol>
                </nav>
            </div>
            @if($clinic->isPending())
            <div>
                <button type="button" class="btn btn-success approve-btn" data-id="{{ $clinic->id }}">
                    <i class="bi bi-check-lg me-2"></i>{{ __('translation.common.approve') }}
                </button>
                <button type="button" class="btn btn-danger reject-btn" data-id="{{ $clinic->id }}">
                    <i class="bi bi-x-lg me-2"></i>{{ __('translation.common.reject') }}
                </button>
            </div>
            @endif
        </div>

        <div class="row">
            {{-- Clinic Info --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-primary text-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-building me-2"></i>
                            {{ __('translation.clinic.info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-muted" style="width: 35%;">{{ __('translation.clinic.name') }}</th>
                                <td>{{ $clinic->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.clinic.address') }}</th>
                                <td>{{ $clinic->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.clinic.phone') }}</th>
                                <td>{{ $clinic->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.clinic.status_label') }}</th>
                                <td><span class="badge {{ $clinic->status_badge_class }}">{{ $clinic->status_label }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.clinic.created_at') }}</th>
                                <td>{{ $clinic->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @if($clinic->isApproved())
                            <tr>
                                <th class="text-muted">{{ __('translation.clinic.approved_at') }}</th>
                                <td>{{ $clinic->approved_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.clinic.approved_by') }}</th>
                                <td>{{ $clinic->approver->name ?? '-' }}</td>
                            </tr>
                            @endif
                            @if($clinic->status === 'rejected')
                            <tr>
                                <th class="text-muted">{{ __('translation.clinic.rejection_reason') }}</th>
                                <td class="text-danger">{{ $clinic->rejection_reason }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- Statistics --}}
                @if($clinic->isApproved())
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-transparent border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-bar-chart me-2"></i>
                            {{ __('translation.clinic.statistics') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-0 text-primary">{{ $clinic->patients->count() }}</h3>
                                    <small class="text-muted">{{ __('translation.clinic.total_patients') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3 text-center">
                                    <h3 class="mb-0 text-success">{{ $clinic->examinations->count() }}</h3>
                                    <small class="text-muted">{{ __('translation.clinic.total_examinations') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Doctor Info --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-success text-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-person-badge me-2"></i>
                            {{ __('translation.clinic.doctor_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th class="text-muted" style="width: 35%;">{{ __('translation.user.name') }}</th>
                                <td>{{ $clinic->doctor->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.user.email') }}</th>
                                <td>{{ $clinic->doctor->email }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.user.phone') }}</th>
                                <td>{{ $clinic->doctor->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.user.country') }}</th>
                                <td>{{ $clinic->doctor->country->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('translation.user.registered_at') }}</th>
                                <td>{{ $clinic->doctor->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('translation.clinic.reject_clinic') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <form id="reject-form">
                    <input type="hidden" id="reject-clinic-id" value="{{ $clinic->id }}">
                    <div class="mb-3">
                        <label class="form-label">{{ __('translation.clinic.rejection_reason') }} <span class="text-danger">*</span></label>
                        <textarea id="reject-reason" class="form-control" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translation.common.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirm-reject">{{ __('translation.common.reject') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            
            const result = await SwalHelper.confirm(
                '{{ __('translation.clinic.confirm_approve') }}',
                '',
                { confirmButtonText: '{{ __('translation.common.approve') }}', confirmButtonColor: '#198754' }
            );
            
            if (!result.isConfirmed) return;
            
            SwalHelper.showLoading('{{ __('translation.common.processing') }}');
            
            try {
                const data = await ApiClient.post('{{ url('/' . app()->getLocale() . '/dashboard/clinics') }}/' + id + '/approve');
                if (data.success) {
                    await SwalHelper.success('{{ __('translation.common.success') }}', data.message);
                    window.location.reload();
                } else {
                    SwalHelper.error('{{ __('translation.common.error') }}', data.message);
                }
            } catch (error) {
                SwalHelper.error('{{ __('translation.common.error') }}', error.message);
            }
        });
    });

    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
            modal.show();
        });
    });

    document.getElementById('confirm-reject')?.addEventListener('click', async function() {
        const id = document.getElementById('reject-clinic-id')?.value;
        const reason = document.getElementById('reject-reason')?.value;
        
        if (!reason?.trim()) {
            SwalHelper.error('{{ __('translation.common.error') }}', '{{ __('translation.clinic.reason_required') }}');
            return;
        }

        SwalHelper.showLoading('{{ __('translation.common.processing') }}');

        try {
            const data = await ApiClient.post('{{ url('/' . app()->getLocale() . '/dashboard/clinics') }}/' + id + '/reject', { reason: reason });
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('rejectModal'))?.hide();
                await SwalHelper.success('{{ __('translation.common.success') }}', data.message);
                window.location.reload();
            } else {
                SwalHelper.error('{{ __('translation.common.error') }}', data.message);
            }
        } catch (error) {
            SwalHelper.error('{{ __('translation.common.error') }}', error.message);
        }
    });
});
</script>
@endpush
