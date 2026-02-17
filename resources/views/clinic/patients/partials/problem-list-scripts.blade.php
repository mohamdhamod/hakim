<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Problem List View Handlers
    // ========================================
    document.querySelectorAll('.view-btn[data-type="problem"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.dataset.model);
            document.getElementById('view_problem_title').textContent = data.title || '-';
            document.getElementById('view_problem_icd_code').textContent = data.icd_code || '-';
            document.getElementById('view_problem_onset_date').textContent = data.onset_date ? new Date(data.onset_date).toLocaleDateString() : '-';
            document.getElementById('view_problem_resolved_date').textContent = data.resolved_date ? new Date(data.resolved_date).toLocaleDateString() : '-';
            document.getElementById('view_problem_status').innerHTML = data.status_label || data.status || '-';
            document.getElementById('view_problem_severity').innerHTML = data.severity_label || data.severity || '-';
            document.getElementById('view_problem_notes').textContent = data.notes || '-';
            new bootstrap.Modal(document.getElementById('viewProblemModal')).show();
        });
    });

    // ========================================
    // Problem List Edit Handlers
    // ========================================
    document.querySelectorAll('.edit-btn[data-type="problem"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.dataset.model);
            const form = document.getElementById('editProblemForm');
            form.action = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/problems") }}/${data.id}`;
            document.getElementById('edit_problem_title').value = data.title || '';
            document.getElementById('edit_problem_icd_code').value = data.icd_code || '';
            document.getElementById('edit_problem_onset_date').value = data.onset_date ? data.onset_date.split('T')[0] : '';
            document.getElementById('edit_problem_resolved_date').value = data.resolved_date ? data.resolved_date.split('T')[0] : '';
            document.getElementById('edit_problem_status').value = data.status || 'active';
            document.getElementById('edit_problem_severity').value = data.severity || '';
            document.getElementById('edit_problem_notes').value = data.notes || '';
            new bootstrap.Modal(document.getElementById('editProblemModal')).show();
        });
    });

    // ========================================
    // Problem List Delete Handlers
    // ========================================
    document.querySelectorAll('.delete-btn[data-type="problem"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const data = JSON.parse(this.dataset.model);
            const deleteUrl = `{{ url("/" . app()->getLocale() . "/clinic/patients/" . $patient->file_number . "/problems") }}/${data.id}`;
            confirmDelete(data, deleteUrl, window.i18n, data.title || '');
        });
    });

    // ========================================
    // Problem List Form Submit (handleFormSubmit)
    // ========================================
    document.querySelectorAll('.problem-form').forEach(form => {
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
