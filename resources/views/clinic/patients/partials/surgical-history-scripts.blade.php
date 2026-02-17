<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Surgical History View Handlers
    // ========================================
    document.querySelectorAll('.view-btn[data-type="surgery"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.dataset.model);
            document.getElementById('view_surgery_procedure_name').textContent = data.procedure_name || '-';
            document.getElementById('view_surgery_procedure_date').textContent = data.procedure_date ? new Date(data.procedure_date).toLocaleDateString() : '-';
            document.getElementById('view_surgery_hospital').textContent = data.hospital || '-';
            document.getElementById('view_surgery_surgeon').textContent = data.surgeon || '-';
            document.getElementById('view_surgery_indication').textContent = data.indication || '-';
            document.getElementById('view_surgery_complications').textContent = data.complications || '-';
            document.getElementById('view_surgery_notes').textContent = data.notes || '-';
            new bootstrap.Modal(document.getElementById('viewSurgicalHistoryModal')).show();
        });
    });

    // ========================================
    // Surgical History Edit Handlers
    // ========================================
    document.querySelectorAll('.edit-btn[data-type="surgery"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.dataset.model);
            const form = document.getElementById('editSurgeryForm');
            form.action = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/surgeries") }}/${data.id}`;
            document.getElementById('edit_surgery_procedure_name').value = data.procedure_name || '';
            document.getElementById('edit_surgery_procedure_date').value = data.procedure_date ? data.procedure_date.split('T')[0] : '';
            document.getElementById('edit_surgery_hospital').value = data.hospital || '';
            document.getElementById('edit_surgery_surgeon').value = data.surgeon || '';
            document.getElementById('edit_surgery_indication').value = data.indication || '';
            document.getElementById('edit_surgery_complications').value = data.complications || '';
            document.getElementById('edit_surgery_notes').value = data.notes || '';
            new bootstrap.Modal(document.getElementById('editSurgicalHistoryModal')).show();
        });
    });

    // ========================================
    // Surgical History Delete Handlers
    // ========================================
    document.querySelectorAll('.delete-btn[data-type="surgery"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            const deleteUrl = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/surgeries") }}/${data.id}`;
            confirmDelete(data, deleteUrl, window.i18n, data.procedure_name || '');
        });
    });

    // ========================================
    // Surgical History Form Submit (handleFormSubmit)
    // ========================================
    document.querySelectorAll('.surgery-form').forEach(form => {
        if (!form.__handleSubmitBound) {
            form.__handleSubmitBound = true;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                window.handleFormSubmit(e, this);
            });
        }
    });
});
</script>
