@extends('layout.main')

@push('styles')
    <style>
    </style>
@endpush

@section('content')
    <div class="container-fluid py-5">

        <!-- Page Title -->
        <div class="row justify-content-start mb-4 text-start">
            <div class="col-xxl-12 col-xl-12">
            <span class="badge bg-light text-dark shadow px-2 py-1 mb-2 fs-6">
                <i class="bi bi-gear-wide-connected me-1"></i> {{ __('translation.config_titles.header.badge') }}
            </span>
                <h3 class="fw-bold">{{ __('translation.config_titles.header.title') }}</h3>
                <p class="fs-6 text-muted mb-0">{{ __('translation.config_titles.header.description') }}</p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">{{ __('translation.config_titles.titles_list') }}</h5>
                <div class="d-flex gap-2">
                    <button type="button" onclick="openCreate()" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> {{ __('translation.config_titles.buttons.new_title') }}
                    </button>
                </div>
            </div>

            <div class="card-body">
                <!-- Export and Column Visibility Controls -->
                <div class="mb-3 d-flex gap-2">
                    <!-- Export Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                            <i class="bi bi-download me-1"></i>{{ __('translation.export.menu') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:void(0)"
                                   onclick="copyTableToClipboard('configTitlesTable')">
                                    <i class="bi bi-clipboard me-1"></i>{{ __('translation.export.copy') }}
                                </a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)"
                                   onclick="exportTableToCSV('configTitlesTable', 'dropdown-data.csv')">
                                    <i class="bi bi-file-earmark-spreadsheet me-1"></i>{{ __('translation.export.csv') }}
                                </a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)"
                                   onclick="printTable('configTitlesTable')">
                                    <i class="bi bi-printer me-1"></i>{{ __('translation.export.print') }}
                                </a></li>
                        </ul>
                    </div>

                    <!-- Column Visibility Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                            <i class="bi bi-eye me-1"></i>{{ __('translation.columns.dropdown') }}
                        </button>
                        <div class="dropdown-menu p-3" style="min-width: 200px;">
                            <h6 class="dropdown-header">{{ __('translation.columns.toggle') }}</h6>
                            <div class="form-check">
                                <input class="form-check-input column-toggle" type="checkbox" data-column="0"
                                       id="col-id">
                                <label class="form-check-label" for="col-id">#</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input column-toggle" type="checkbox" data-column="1"
                                       id="col-key">
                                <label class="form-check-label"
                                       for="col-company">{{ __('translation.config_titles.table.headers.key') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input column-toggle" type="checkbox" checked data-column="2"
                                       id="col-page">
                                <label class="form-check-label"
                                       for="col-page">{{ __('translation.config_titles.table.headers.page') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input column-toggle" type="checkbox" checked data-column="3"
                                       id="col-title">
                                <label class="form-check-label"
                                       for="col-title">{{ __('translation.config_titles.table.headers.title') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input column-toggle" type="checkbox" data-column="4"
                                       id="col-description">
                                <label class="form-check-label"
                                       for="col-description">{{ __('translation.config_titles.table.headers.description') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input column-toggle" type="checkbox" data-column="5"
                                       id="col-creation-date">
                                <label class="form-check-label"
                                       for="col-creation-date">{{ __('translation.config_titles.table.headers.creation_date') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input column-toggle" type="checkbox" checked data-column="6"
                                       id="col-actions">
                                <label class="form-check-label"
                                       for="col-actions">{{ __('translation.config_titles.table.headers.actions') }}</label>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted"
                                       id="column-count">{{ __('translation.columns.all_visible', ['count' => 7]) }}</small>
                            </div>
                            <div class="d-flex">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="toggleAllColumns(true)">
                                    <i class="bi bi-check-all me-1"></i>{{ __('translation.columns.show_all') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive-custom">
                    <table id="configTitlesTable"
                           class="table table-striped table-hover table-modern table-compact table-sticky-header align-middle mt-2 mb-0 dataTable">
                        <thead class="thead-sm" style="width:100%">
                        <tr>
                            <th>#</th>
                            <th>{{ __('translation.config_titles.table.headers.key') }}</th>
                            <th>{{ __('translation.config_titles.table.headers.page') }}</th>
                            <th>{{ __('translation.config_titles.table.headers.title') }}</th>
                            <th>{{ __('translation.config_titles.table.headers.description') }}</th>
                            <th>{{ __('translation.config_titles.table.headers.creation_date') }}</th>
                            <th>{{ __('translation.config_titles.table.headers.actions') }}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>


    @component('dashboard.config.config_titles.form')
    @endcomponent
    @component('modules.view')
    @endcomponent
    @component('modules.confirm')
    @endcomponent
@stop

@push('scripts')
    <script>
        // Build i18n dictionary for JS
        @php($i18n = [
            'datatable' => [
                'search' => __('translation.datatable.search'),
                'lengthMenu' => __('translation.datatable.lengthMenu'),
                'info' => __('translation.datatable.info'),
                'processing' => __('translation.datatable.processing'),
                'emptyTable' => __('translation.datatable.emptyTable'),
                'zeroRecords' => __('translation.datatable.zeroRecords'),
            ],
            'table' => [
                'id' => __('translation.config_titles.table.headers.id'),
                'key' => __('translation.config_titles.table.headers.key'),
                'page' => __('translation.config_titles.table.headers.page'),
                'title' => __('translation.config_titles.table.headers.title'),
                'description' => __('translation.config_titles.table.headers.description'),
                'creation_date' => __('translation.config_titles.table.headers.creation_date'),
                'actions' => __('translation.config_titles.table.headers.actions'),
            ],
            'labels' => [
                'id' => __('translation.config_titles.table.headers.id'),
                'key' => __('translation.config_titles.table.headers.key'),
                'page' => __('translation.config_titles.table.headers.page'),
                'title' => __('translation.config_titles.table.headers.title'),
                'description' => __('translation.config_titles.table.headers.description'),
                'created_at' => __('translation.config_titles.table.headers.creation_date'),
            ],
            'form' => [
                'title_edit' => __('translation.config_titles.form.modal.title_edit'),
                'title_add' => __('translation.config_titles.form.modal.title_add'),
                'btn_update' => __('translation.config_titles.form.buttons.update'),
                'btn_save' => __('translation.config_titles.form.buttons.save'),
            ],
            'confirm' => [
                'message' => __('translation.modal.confirm_delete.message_with_item'),
            ],
            'toasts' => [
                'delete_success' => __('translation.js.toasts.delete_success'),
                'delete_failed' => __('translation.js.toasts.delete_failed'),
            ],
        ])
        const i18n = @json($i18n);
        // Initialize the application
        document.addEventListener('DOMContentLoaded', initializePage);

        // Initialize DataTables with export functionality
        function initializeTable() {
            window.dataTable = new window.DataTable('#configTitlesTable', {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('config_titles.index') }}",
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': (window.Utils && typeof window.Utils.getCSRFToken === 'function') ? window.Utils.getCSRFToken() : ''
                    },
                    data: function (d) {
                        // Add any additional parameters here if needed
                        return d;
                    }
                },
                columns: [
                    {data: 'id', name: 'id', title: i18n.table.id},
                    {data: 'key', name: 'key', title: i18n.table.key},
                    {data: 'page', name: 'page', title: i18n.table.page},
                    {data: 'title', name: 'title', title: i18n.table.title},
                    {
                        data: 'description',
                        name: 'description',
                        title: i18n.table.description,
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        title: i18n.table.creation_date,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        title: i18n.table.actions,
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "{{ __('translation.datatable.all') }}"]],
                renderer: 'bootstrapIcons',
                language: {
                    search: i18n.datatable.search,
                    lengthMenu: i18n.datatable.lengthMenu,
                    info: i18n.datatable.info,
                    processing: i18n.datatable.processing,
                    emptyTable: i18n.datatable.emptyTable,
                    zeroRecords: i18n.datatable.zeroRecords
                },
                columnDefs: [
                    {"visible": false, "targets": 0},
                    {"visible": false, "targets": 1},
                    {"visible": false, "targets": 4},
                    {"visible": false, "targets": 5},
                ],
                scrollX: false,
                scrollY: false,
                autoWidth: false,
                responsive: false
            });

            // Set the current DataTable reference for global functions
            setCurrentDataTable(window.dataTable);

            // Initialize column visibility toggles
            initializeColumnVisibility('#configTitlesTable');

            // Initialize table event handlers
            initializeTableEvents();
        }

        // Initialize table event handlers for CRUD operations
        function initializeTableEvents() {
            // Handle button clicks using event delegation
            document.addEventListener('click', function (e) {
                const target = e.target.closest('.dropdown-item');
                if (!target) return;

                e.preventDefault();

                const row = target.closest('tr');
                let targetRow = row;

                // Handle responsive child rows
                if (row && row.classList.contains('child')) {
                    targetRow = row.previousElementSibling;
                }

                if (!targetRow || !window.currentDataTable) return;

                const rowData = window.currentDataTable.row(targetRow).data();

                // Handle different button types
                if (target.classList.contains('edit-btn')) {
                    window.openEdit(rowData);
                } else if (target.classList.contains('view-btn')) {
                    window.openView(rowData);
                } else if (target.classList.contains('delete-btn')) {
                    window.confirmDelete(rowData);
                }
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


        }

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

            // Set form action - use update route
            form.action = `{{ route('config_titles.index') }}/${data.id}`;

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

            // Set TinyMCE content if available
            if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
                tinymce.get('description').setContent(stripHtml(data.description || ''));
            }

            // Show modal
            showModal(modal);
        };

        // Open edit modal
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
                {label: i18n.labels.page, value: data.page || ''},
                {label: i18n.labels.title, value: data.title || ''},
                {label: i18n.labels.description, value: removeHtmlTags(data.description || '')},
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

        // Define CRUD functions (available immediately)

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
            form.action = `{{ route('config_titles.store') }}`;

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

            // Clear TinyMCE content if available
            if (typeof tinymce !== 'undefined' && tinymce.get('description')) {
                tinymce.get('description').setContent('');
            }

            // Show modal
            showModal(modal);
        }

        // Confirm delete operation
        window.confirmDelete = function (data) {
            const modal = document.getElementById('confirmModal');
            const message = document.getElementById('confirmMessage');
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            if (modal && message && confirmBtn) {
                // Set the confirmation message
                message.textContent = (i18n.confirm.message || '').replace(':item', (data.title || data.key));

                // Remove any existing event listeners
                const newConfirmBtn = confirmBtn.cloneNode(true);
                confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

                // Add new event listener
                newConfirmBtn.addEventListener('click', function () {
                    hideModal(modal);
                    const resourceUrl = `{{ route('config_titles.index') }}/${data.id}`;
                    window.deleteItem(resourceUrl, {
                        successMessage: i18n.toasts.delete_success,
                        errorMessage: i18n.toasts.delete_failed
                    });
                });

                // Show the modal
                showModal(modal);
            } else {
                // Fallback to alert if modal elements not found
                if (window.confirm && window.confirm((i18n.confirm.message || '').replace(':item', (data.title || data.key)))) {
                    const resourceUrl = `{{ route('config_titles.index') }}/${data.id}`;
                    window.deleteItem(resourceUrl, {
                        successMessage: i18n.toasts.delete_success,
                        errorMessage: i18n.toasts.delete_failed
                    });
                } else {
                    console.error('Confirmation modal not found and confirm() not available');
                }
            }
        }

        // Use generalized deleteItem from general.js
        // Initialize page after DOM is loaded
        function initializePage() {
            initializeTable();
            initializeDropdownBehavior();
        }
    </script>

@endpush

