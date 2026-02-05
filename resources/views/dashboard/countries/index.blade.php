@extends('layout.main')
@include('layout.extra_meta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-link-45deg me-2"></i> {{ __('translation.countries.title') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.countries.header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.countries.header_description') }}
                </p>
            </div>
        </div>

        <!-- countries Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('translation.countries.title') }}</h5>
                        <div class="d-flex gap-2">
                            <button type="button" onclick="openCreate()" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('translation.countries.form.buttons.add') }}
                            </button>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($countries->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('translation.countries.name') }}</th>
                                        <th>{{ __('translation.countries.phone_extension') }}</th>
                                        <th>{{ __('translation.countries.code') }}</th>
                                        <th>{{ __('translation.countries.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($countries as $model)
                                        <tr>
                                            <td><strong>#{{ $model->id }}</strong></td>
                                            <td>
                                                <div>
                                                    <strong>{{ $model->name  }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $model->phone_extension  }}</strong>

                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <a href="{{ $model->flag_url }}" target="_blank"><img src="{{ $model->flag_url }}" alt="{{ $model->code }}" class="avatar-sm rounded-circle img-thumbnail" style="object-fit: cover;"> </a>{{ $model->code  }}
                                                </div>
                                            </td>


                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.countries.edit') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.countries.view')}}"
                                                        class="btn btn-info btn-sm view-btn">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <button class="btn btn-sm btn-danger delete-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.users.delete') }}">
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
                                {{ $countries->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.countries.no_countries_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.countries.no_countries_found_message') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('dashboard.countries.form')
    @include('modules.confirm')
    @include('modules.view')
    @include('modules.i18n', ['page' => 'countries'])
    <script>
        const i18n = window.i18n;
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    window.openEdit(data);
                });
            });
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const json = this.getAttribute('data-model');
                    const data = JSON.parse(json);
                    confirmDelete(data , `{{ route('countries.index') }}/${data.id}` , i18n , data.name);
                });
            });

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
            const formData = modal.querySelector('.form-data');
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

            // Set form action - use update route
            form.action = `{{ route('countries.index') }}/${data.id}`;

            // Update modal title and button
            if (modalTitle) {
                modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>' + i18n.form.title_edit;
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
            clearFormErrors(formData);
            // Fill form with data
            fillForm(formData, data);
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
                {label: i18n.labels.name, value: data.name || ''},
                {label: i18n.labels.phone_extension, value: data.phone_extension || ''},
                {label: i18n.labels.code, value: data.code || ''},
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
            form.action = `{{ route('countries.store') }}`;

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


            // Show modal
            showModal(modal);
        }
    </script>
@endpush
