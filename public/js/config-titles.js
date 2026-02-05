/**
 * Config Titles Management JavaScript
 * Enhanced functionality for ConfigTitles dashboard
 */

$(document).ready(function() {
    // Initialize ConfigTitles management
    ConfigTitlesManager.init();
});

const ConfigTitlesManager = {
    table: null,
    
    init: function() {
        this.initDataTable();
        this.initEventHandlers();
        this.initTinyMCE();
    },
    
    initDataTable: function() {
        this.table = $('#config_titles').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: window.configTitlesRoutes.index,
                data: function (d) {
                    d.pages = $("#pages").val();
                },
                method: "GET",
            },
            dom: 'Blfrtip',
            responsive: true,
            buttons: [
                'csvHtml5',
                {
                    text: '<i class="bi bi-plus-circle"></i> Create New',
                    className: 'btn btn-primary btn-sm',
                    action: function (e, dt, node, config) {
                        window.location.href = window.configTitlesRoutes.create;
                    }
                }
            ],
            columnDefs: [
                {"visible": false, "targets": 3}, // Hide description column by default
            ],
            columns: [
                {"data": "id"},
                {"data": "page"},
                {"data": "title"},
                {
                    data: "description",
                    render: function (data) {
                        return data ? ConfigTitlesManager.stripHtml(data) : '';
                    }
                },
                {
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false
                }
            ],
            order: [[0, 'desc']], // Order by ID descending
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
    },
    
    initEventHandlers: function() {
        const self = this;
        
        // Column selector
        $('#columnSelector').multiselect({ maxHeight: 250 });
        $('#columnSelector').on('change', function () {
            var selectedColumns = $(this).val();
            self.table.columns().visible(false);
            selectedColumns.forEach(function (columnIndex) {
                self.table.column(columnIndex).visible(true);
            });
            self.table.draw();
        });
        
        // Pages filter
        $("#pages").change(function () {
            self.table.ajax.reload();
        });
        
        // Form submission
        $('.add-form').on('submit', function (event) {
            self.handleFormSubmit(event, this);
        });
        
        // View button
        this.table.on('click', '.view', function () {
            const data = self.getRowData($(this));
            self.openView(data);
        });
        
        // Edit button (for inline editing)
        this.table.on('click', '.edit', function () {
            const data = self.getRowData($(this));
            self.openEdit(data);
        });
        
        // Delete button
        this.table.on('click', '.delete-btn', function () {
            self.handleDelete($(this));
        });
        
        // Modal events
        $('#add-modal').on('hidden.bs.modal', function (event) {
            $(this).removeClass('in').addClass('out');
            self.resetForm();
        });
    },
    
    initTinyMCE: function() {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#description',
                height: 300,
                plugins: 'preview importcss searchreplace autolink autosave directionality code visualblocks visualchars fullscreen image link codesample table charmap pagebreak nonbreaking insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                menubar: true,
                directionality: document.dir || 'ltr',
                toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview save print | template link anchor codesample | ltr rtl',
                autosave_ask_before_unload: true,
                autosave_interval: '30s',
                autosave_prefix: '{path}{query}-{id}-',
                autosave_restore_when_empty: false,
                autosave_retention: '2m',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
        }
    },
    
    getRowData: function($element) {
        let $tr = $element.closest('tr');
        if ($tr.hasClass('child')) {
            $tr = $tr.prev('.parent');
        }
        return this.table.row($tr).data();
    },
    
    openEdit: function(data) {
        const $modal = $('#add-modal');
        const $form = $('.add-form');
        
        // Reset form
        $form[0].reset();
        this.clearErrors($modal);
        
        // Remove existing method field and add PUT method
        $form.find('input[name="_method"]').remove();
        $form.append('<input type="hidden" name="_method" value="PUT">');
        
        // Set form action
        $form.attr('action', window.configTitlesRoutes.update.replace(':id', data.id));
        
        // Fill form fields
        $modal.find('#modal-key').val(data.key || '');
        $modal.find('#modal-page').val(data.page || '');
        $modal.find('input[name="title"]').val(data.title || '');
        
        // Set TinyMCE content
        if (tinymce.get('description')) {
            tinymce.get('description').setContent(this.stripHtml(data.description || ''));
        } else {
            $modal.find('textarea[name="description"]').val(this.stripHtml(data.description || ''));
        }
        
        // Update modal appearance
        $modal.find('.modal-title').text('Edit Config Title');
        $modal.find('button[type="submit"]').html('<i class="bi bi-check-circle"></i> Update Changes');
        
        // Show modal
        $modal.modal('show').removeClass('out').addClass('in');
    },
    
    openView: function(data) {
        const $modal = $('#view-modal');
        const $data = $('#data');
        
        const fields = [
            {label: 'ID', value: data.id || ''},
            {label: 'Key', value: data.key || ''},
            {label: 'Page', value: data.page || ''},
            {label: 'Title', value: data.title || ''},
            {label: 'Description', value: this.removeHtmlTags(data.description || '')},
            {label: 'Created', value: data.created_at || ''},
            {label: 'Updated', value: data.updated_at || ''}
        ];

        const fieldHtml = fields.map(field => `
            <div class="row mb-2">
                <div class="col-md-3">
                    <label class="fw-bold">${field.label}:</label>
                </div>
                <div class="col-md-9">
                    <span class="text-muted">${field.value}</span>
                </div>
            </div>
        `).join('');

        $data.html(fieldHtml);
        $modal.modal('show').removeClass('out').addClass('in');
    },
    
    handleFormSubmit: function(event, form) {
        event.preventDefault();
        
        const $form = $(form);
        const $submitBtn = $form.find('button[type="submit"]');
        const originalText = $submitBtn.html();
        
        // Get TinyMCE content
        if (tinymce.get('description')) {
            $form.find('textarea[name="description"]').val(tinymce.get('description').getContent());
        }
        
        // Show loading state
        $submitBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i> Processing...');
        
        $.ajax({
            url: $form.attr('action'),
            method: $form.attr('method') || 'POST',
            data: new FormData(form),
            processData: false,
            contentType: false,
            success: (response) => {
                if (response.success) {
                    this.showSuccess(response.message || 'Operation completed successfully!');
                    $('#add-modal').modal('hide');
                    this.table.ajax.reload(null, false);
                } else {
                    this.showError(response.message || 'Operation failed!');
                }
            },
            error: (xhr) => {
                this.handleFormErrors(xhr, $form);
            },
            complete: () => {
                $submitBtn.prop('disabled', false).html(originalText);
            }
        });
    },
    
    handleDelete: function($element) {
        const deleteUrl = $element.data('url');
        const itemId = $element.data('id');
        
        if (!confirm('Are you sure you want to delete this config title?')) {
            return;
        }
        
        const $btn = $element;
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split"></i>');
        
        $.ajax({
            url: deleteUrl,
            method: 'DELETE',
            data: {
                _token: window.csrfToken || $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    this.showSuccess(response.message || 'Config title deleted successfully!');
                    this.table.ajax.reload(null, false);
                } else {
                    this.showError(response.message || 'Failed to delete config title!');
                }
            },
            error: (xhr) => {
                console.error('Delete error:', xhr);
                this.showError('An error occurred while deleting the config title.');
            },
            complete: () => {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    },
    
    handleFormErrors: function(xhr, $form) {
        this.clearErrors($form.closest('.modal'));
        
        if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            $.each(errors, (field, messages) => {
                const $input = $form.find(`[name="${field}"]`);
                $input.addClass('is-invalid');
                $input.after(`<span class="invalid-feedback" role="alert"><strong>${messages[0]}</strong></span>`);
            });
            this.showError('Please fix the validation errors.');
        } else {
            this.showError('An error occurred. Please try again.');
        }
    },
    
    clearErrors: function($modal) {
        $modal.find('.invalid-feedback').remove();
        $modal.find('.form-control').removeClass('is-invalid');
    },
    
    resetForm: function() {
        const $form = $('.add-form');
        $form[0].reset();
        $form.find('input[name="_method"]').remove();
        $form.attr('action', window.configTitlesRoutes.store);
        
        $('#add-modal .modal-title').text('Create Config Title');
        $('#add-modal button[type="submit"]').html('<i class="bi bi-check-circle"></i> Save Changes');
        
        if (tinymce.get('description')) {
            tinymce.get('description').setContent('');
        }
    },
    
    stripHtml: function(html) {
        if (!html) return '';
        const doc = new DOMParser().parseFromString(html, 'text/html');
        return doc.body.textContent || "";
    },
    
    removeHtmlTags: function(encodedText) {
        if (!encodedText) return '';
        const parser = new DOMParser();
        const doc = parser.parseFromString(encodedText, 'text/html');
        return doc.body.textContent || "";
    },
    
    showSuccess: function(message) {
        if (typeof toastr !== 'undefined') {
            toastr.success(message);
        } else {
            alert(message);
        }
    },
    
    showError: function(message) {
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }
};