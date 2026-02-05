@extends('layout.main')
@include('layout.extra_meta')

@section('content')
<div class="bg-light min-vh-100">
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">{{ __('translation.clinic.pending_approvals') }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('translation.dashboard.title') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('clinics.index') }}">{{ __('translation.clinic.clinics') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('translation.clinic.pending') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Alert --}}
        <div class="alert alert-warning d-flex align-items-center mb-4">
            <i class="bi bi-exclamation-triangle fs-4 me-3"></i>
            <div>
                {{ __('translation.clinic.pending_approval_admin_notice') }}
            </div>
        </div>

        {{-- Pending Clinics Table --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pending-table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('translation.clinic.name') }}</th>
                                <th>{{ __('translation.clinic.doctor') }}</th>
                                <th>{{ __('translation.clinic.email') }}</th>
                                <th>{{ __('translation.clinic.phone') }}</th>
                                <th>{{ __('translation.clinic.submitted_at') }}</th>
                                <th>{{ __('translation.common.actions') }}</th>
                            </tr>
                        </thead>
                    </table>
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
$(document).ready(function() {
    var table = $('#pending-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('clinics.pending') }}',
        columns: [
            { data: 'name', name: 'name' },
            { data: 'doctor_name', name: 'doctor.name' },
            { data: 'doctor_email', name: 'doctor.email' },
            { data: 'doctor_phone', name: 'doctor.phone', defaultContent: '-' },
            { data: 'created_at_formatted', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[4, 'asc']],
        language: {
            url: '{{ asset('vendor/datatables/i18n/' . app()->getLocale() . '.json') }}'
        }
    });

    // Approve
    $(document).on('click', '.approve-btn', function() {
        var id = $(this).data('id');
        if (!confirm('{{ __('translation.clinic.confirm_approve') }}')) return;
        
        $.ajax({
            url: '{{ url('/' . app()->getLocale() . '/dashboard/clinics') }}/' + id + '/approve',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error');
            }
        });
    });

    // Open reject modal
    $(document).on('click', '.reject-btn', function() {
        $('#reject-clinic-id').val($(this).data('id'));
        $('#reject-reason').val('');
        $('#rejectModal').modal('show');
    });

    // Confirm reject
    $('#confirm-reject').on('click', function() {
        var id = $('#reject-clinic-id').val();
        var reason = $('#reject-reason').val();
        
        if (!reason.trim()) {
            alert('{{ __('translation.clinic.reason_required') }}');
            return;
        }

        $.ajax({
            url: '{{ url('/' . app()->getLocale() . '/dashboard/clinics') }}/' + id + '/reject',
            type: 'POST',
            data: { 
                _token: '{{ csrf_token() }}',
                reason: reason
            },
            success: function(response) {
                if (response.success) {
                    $('#rejectModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error');
            }
        });
    });
});
</script>
@endpush
