@extends('layout.main')
@include('layout.extra_meta')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="row justify-content-start py-3">
            <div class="col-xxl-8 col-xl-10 text-start">
                <span class="badge bg-light text-dark fw-normal shadow px-2 py-1 mb-2">
                    <i class="bi bi-image me-2"></i> {{ __('translation.images.title') }}
                </span>
                <h3 class="fw-bold">{{ __('translation.images.header_title') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('translation.images.header_description') }}
                </p>
            </div>
        </div>

        <!-- images Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('translation.images.title') }}</h5>
                        <div class="d-flex gap-2">

                        </div>
                    </div>

                    <div class="card-body">
                        @if($images->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('translation.images.image') }}</th>
                                        <th>{{ __('translation.images.key') }}</th>
                                        <th>{{ __('translation.images.actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($images as $model)
                                        <tr>
                                            <td><strong>#{{ $model->id }}</strong></td>
                                            <td>
                                                <div>
                                                   <a href="{{ $model->image_path }}" target="_blank"><img src="{{ $model->image_path }}" alt="{{ $model->key }}" class="avatar-sm rounded-circle img-thumbnail" style="object-fit: cover;"> </a>{{ $model->key  }}
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $model->key  }}</strong>
                                                    @if($model->page)
                                                        <br><small class="text-muted">{{ $model->page }}</small>
                                                    @endif
                                                </div>
                                            </td>


                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                        data-model='@json($model)'
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="{{ __('translation.images.edit') }}">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $images->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-inbox display-4 text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('translation.images.no_images_found') }}</h5>
                                <p class="text-muted mb-0">{{ __('translation.images.no_images_found_message') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @include('dashboard.config.images.form')
    @include('modules.i18n', ['page' => 'images'])
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
            const formData = modal.querySelector('.data-form');
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
            form.action = `{{ route('config_images.index') }}/${data.id}`;

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
    </script>
@endpush
