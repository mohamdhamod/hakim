<!-- Activation Confirmation Modal -->
<div class="modal fade" id="confirmActivateModal" tabindex="-1" aria-labelledby="confirmActivateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmActivateModalLabel">{{ __('translation.modal.confirm_activate.title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmActivateMessage">{{ __('translation.modal.confirm_activate.message') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('translation.modal.confirm_activate.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="confirmActivateBtn">{{ __('translation.modal.confirm_activate.confirm') }}</button>
            </div>
        </div>
    </div>
</div>
