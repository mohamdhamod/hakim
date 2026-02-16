@extends('layout.main')
@include('layout.extra_meta')

@section('content')

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-heart-pulse me-2"></i> {{ __('translation.chronic_disease_types.title') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.chronic_disease_types.header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.chronic_disease_types.header_description') }}
                </p>
            </div>
        </div>

        <!-- Chronic Disease Types Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('translation.chronic_disease_types.title') }}</h5>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="openCreate()" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('translation.chronic_disease_types.buttons.new_title') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($items->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>{{ __('translation.chronic_disease_types.name') }}</th>
                                        <th>{{ __('translation.chronic_disease_types.key') }}</th>
                                        <th>{{ __('translation.chronic_disease_types.category') }}</th>
                                        <th>{{ __('translation.chronic_disease_types.icd11_code') }}</th>
                                        <th>{{ __('translation.chronic_disease_types.followup_days') }}</th>
                                        <th>{{ __('translation.chronic_disease_types.status') }}</th>
                                        <th>{{ __('translation.chronic_disease_types.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $model)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $model->name }}</strong>
                                                    @if($model->description)
                                                        <br><small class="text-muted">{{ Str::limit($model->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <code>{{ $model->key }}</code>
                                            </td>
                                            <td>
                                                @if($model->category)
                                                    <span class="badge bg-info">{{ $model->category }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $model->icd11_code ?? '-' }}</code>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $model->followup_interval_days ?? 30 }} {{ __('translation.common.days') }}</span>
                                            </td>
                                            <td>
                                                @if($model->is_active)
                                                    <span class="badge bg-success">{{ __('translation.chronic_disease_types.active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('translation.chronic_disease_types.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td class="actions">
                                                <!-- Edit button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-primary edit-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.chronic_disease_types.edit') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- View button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-info view-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.chronic_disease_types.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <!-- Activate / Deactivate button -->
                                                @php($isActive = isset($model->is_active) && $model->is_active)
                                                <button type="button"
                                                        class="btn btn-sm process-btn {{ $isActive ? 'btn-warning' : 'btn-success' }}"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $isActive ? __('translation.chronic_disease_types.deactivate') : __('translation.chronic_disease_types.activate') }}"
                                                        aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                                                    <i class="bi {{ $isActive ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>

                                                <!-- Delete button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-danger delete-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.chronic_disease_types.delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $items->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.chronic_disease_types.no_items_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.chronic_disease_types.no_items_found_message') }}</p>
                                <button type="button" onclick="openCreate()" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-2"></i> {{ __('translation.chronic_disease_types.buttons.new_title') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('dashboard.chronic_disease_types.form')
    @include('modules.confirm')
    @include('modules.confirm_activate')
    @include('modules.view')
    @include('modules.i18n', ['page' => 'chronic_disease_types'])
    <script>
        const i18n = window.i18n;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Edit button handler
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    window.openEdit(data);
                });
            });

            // Delete button handler
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmDelete(data, `{{ route('chronic_disease_types.index') }}/${data.id}`, i18n, data.name);
                });
            });

            // Activate/Deactivate button handler
            document.querySelectorAll('.process-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmActivate(data, `{{ route('chronic_disease_types.index') }}/${data.id}/updateActiveStatus`, i18n);
                });
            });

            // View button handler
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    window.openView(data);
                });
            });

            // Handle form submission
            const addForm = document.querySelector('.add-form');
            if (addForm && !addForm.__handleSubmitBound) {
                addForm.__handleSubmitBound = true;
                addForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    window.handleFormSubmit(e, this);
                });
            }
        });

        // Open edit modal
        window.openEdit = function (data) {
            const modal = document.getElementById('add-modal');
            const form = modal.querySelector('.add-form');
            const modalTitle = modal.querySelector('.modal-title');
            const saveButton = modal.querySelector('#afm_btnSaveIt');

            if (!modal || !form) return;

            form.reset();

            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            form.action = `{{ route('chronic_disease_types.index') }}/${data.id}`;

            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-pencil-square me-2"></i>' + i18n.form.title_edit;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_update;
            }

            modal.querySelectorAll('.edit-only').forEach(field => {
                field.style.display = 'block';
            });

            clearFormErrors(modal);
            fillForm(modal, data);
            showModal(modal);
        };

        // Open view modal
        window.openView = function (data) {
            const modal = document.getElementById('view-modal');
            const dataContainer = modal.querySelector('#data');

            if (!modal || !dataContainer) return;

            const fields = [
                {label: i18n.labels.id, value: data.id || ''},
                {label: i18n.labels.key, value: data.key || ''},
                {label: i18n.labels.name, value: data.name || ''},
                {label: i18n.labels.description, value: data.description || ''},
                {label: i18n.labels.category, value: data.category || ''},
                {label: i18n.labels.icd11_code, value: data.icd11_code || ''},
                {label: i18n.labels.followup_days, value: (data.followup_interval_days || 30) + ' ' + (i18n.common?.days || 'days')},
                {label: i18n.labels.status, value: data.is_active ? i18n.labels.active : i18n.labels.inactive},
                {label: i18n.labels.created_at, value: data.created_at || ''}
            ];

            const fieldHtml = fields.map(field => `
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="fw-bold">${field.label}:</label>
                    </div>
                    <div class="col-md-9">
                        <span class="text-muted">${field.value}</span>
                    </div>
                </div>
            `).join('');

            dataContainer.innerHTML = fieldHtml;
            clearFormErrors(modal);
            showModal(modal);
        }

        // Open create modal
        window.openCreate = function () {
            const modal = document.getElementById('add-modal');
            const form = modal.querySelector('.add-form');
            const modalTitle = modal.querySelector('.modal-title');
            const saveButton = modal.querySelector('#afm_btnSaveIt');

            if (!modal || !form) return;

            form.reset();

            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }

            form.action = `{{ route('chronic_disease_types.store') }}`;

            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>' + i18n.form.title_add;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_save;
            }

            modal.querySelectorAll('.edit-only').forEach(field => {
                field.style.display = 'none';
            });

            clearFormErrors(modal);
            showModal(modal);
        };
    </script>
@endpush
