@extends('layout.main')
@include('layout.extra_meta')

@section('content')

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-hospital me-2"></i> {{ __('translation.specialties.title') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.specialties.header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.specialties.header_description') }}
                </p>
            </div>
        </div>

        <!-- Medical Specialties Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('translation.specialties.title') }}</h5>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="openCreate()" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('translation.specialties.buttons.new_title') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($specialties->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width: 60px;">{{ __('translation.specialties.icon') }}</th>
                                        <th>{{ __('translation.specialties.name') }}</th>
                                        <th>{{ __('translation.specialties.key') }}</th>
                                        <th>{{ __('translation.specialties.color') }}</th>
                                        <th>{{ __('translation.specialties.sort_order') }}</th>
                                        <th>{{ __('translation.specialties.status') }}</th>
                                        <th>{{ __('translation.specialties.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($specialties as $model)
                                        <tr>
                                            <td>
                                                <span class="d-flex align-items-center justify-content-center rounded-circle" 
                                                      style="width: 40px; height: 40px; background-color: {{ $model->color ?? '#6c757d' }}20;">
                                                    <i class="fas {{ $model->icon ?? 'fa-stethoscope' }}" style="color: {{ $model->color ?? '#6c757d' }};"></i>
                                                </span>
                                            </td>
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
                                                <span class="d-inline-block rounded" 
                                                      style="width: 24px; height: 24px; background-color: {{ $model->color ?? '#6c757d' }};"
                                                      title="{{ $model->color }}"></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $model->sort_order }}</span>
                                            </td>
                                            <td>
                                                @if($model->active)
                                                    <span class="badge bg-success">{{ __('translation.specialties.active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('translation.specialties.inactive') }}</span>
                                                @endif
                                            </td>
                                            <td class="actions">
                                                

                                                <!-- Edit button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-primary edit-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.specialties.edit') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>

                                                <!-- View button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-info view-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.specialties.view') }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <!-- Activate / Deactivate button -->
                                                @php($isActive = isset($model->active) && $model->active)
                                                <button type="button"
                                                        class="btn btn-sm process-btn {{ $isActive ? 'btn-warning' : 'btn-success' }}"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ $isActive ? __('translation.specialties.deactivate') : __('translation.specialties.activate') }}"
                                                        aria-pressed="{{ $isActive ? 'true' : 'false' }}">
                                                    <i class="bi {{ $isActive ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                                </button>

                                                <!-- Delete button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-danger delete-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.specialties.delete') }}">
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
                                {{ $specialties->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.specialties.no_specialties_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.specialties.no_specialties_found_message') }}</p>
                                <button type="button" onclick="openCreate()" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-circle me-2"></i> {{ __('translation.specialties.buttons.new_title') }}
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
    @include('dashboard.specialties.form')
    @include('modules.confirm')
    @include('modules.confirm_activate')
    @include('modules.view')
    @include('modules.i18n', ['page' => 'specialties'])
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
                    confirmDelete(data, `{{ route('specialties.index') }}/${data.id}`, i18n, data.name);
                });
            });

            // Activate/Deactivate button handler
            document.querySelectorAll('.process-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmActivate(data, `{{ route('specialties.index') }}/${data.id}/updateActiveStatus`, i18n);
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

            if (!modal || !form) {
                console.error('Modal or form not found');
                return;
            }

            // Reset form
            form.reset();

            // Add method override for PUT request
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            // Set form action
            form.action = `{{ route('specialties.index') }}/${data.id}`;

            // Update modal title and button
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-pencil-square me-2"></i>' + i18n.form.title_edit;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_update;
            }

            // Show edit-only fields
            modal.querySelectorAll('.edit-only').forEach(field => {
                field.style.display = 'block';
            });

            // Clear previous errors
            clearFormErrors(modal);

            // Fill form with data
            fillForm(modal, data);

            // Show modal
            showModal(modal);
        };

        // Open view modal
        window.openView = function (data) {
            const modal = document.getElementById('view-modal');
            const dataContainer = modal.querySelector('#data');

            if (!modal || !dataContainer) {
                console.error('View modal or data container not found');
                return;
            }

            // Define fields to display
            const fields = [
                {label: i18n.labels.id, value: data.id || ''},
                {label: i18n.labels.key, value: data.key || ''},
                {label: i18n.labels.name, value: data.name || ''},
                {label: i18n.labels.description, value: data.description || ''},
                {label: i18n.labels.icon, value: data.icon || ''},
                {label: i18n.labels.color, value: `<span class="d-inline-block rounded me-2" style="width: 20px; height: 20px; background-color: ${data.color || '#6c757d'};"></span>${data.color || ''}`},
                {label: i18n.labels.sort_order, value: data.sort_order || '0'},
                {label: i18n.labels.status, value: data.active ? i18n.labels.active : i18n.labels.inactive},
                {label: i18n.labels.created_at, value: data.created_at || ''}
            ];

            // Generate HTML for fields
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

            // Clear previous errors
            clearFormErrors(modal);

            // Show modal
            showModal(modal);
        }

        // Open create modal
        window.openCreate = function () {
            const modal = document.getElementById('add-modal');
            const form = modal.querySelector('.add-form');
            const modalTitle = modal.querySelector('.modal-title');
            const saveButton = modal.querySelector('#afm_btnSaveIt');

            if (!modal || !form) {
                console.error('Modal or form not found');
                return;
            }

            // Reset form
            form.reset();

            // Remove method override for POST request
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }

            // Set form action for create
            form.action = `{{ route('specialties.store') }}`;

            // Update modal title and button
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>' + i18n.form.title_add;
            }
            if (saveButton) {
                saveButton.innerHTML = '<i class="bi bi-check-circle me-1"></i>' + i18n.form.btn_save;
            }

            // Hide edit-only fields
            modal.querySelectorAll('.edit-only').forEach(field => {
                field.style.display = 'none';
            });

            // Clear previous errors
            clearFormErrors(modal);

            // Set default values
            const colorInput = form.querySelector('#color');
            if (colorInput) {
                colorInput.value = '#4A90D9';
            }

            // Show modal
            showModal(modal);
        };
    </script>
@endpush
