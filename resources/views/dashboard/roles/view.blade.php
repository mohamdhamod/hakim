{{-- ADD MODEL START --}}
<div id="view-modal" class="modal fade-scale" role="dialog" tabindex="-1" aria-hidden="true" aria-labelledby="ModelLabel">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ModelLabel">{{ __('translation.roles.view_sub_program_info') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 50vh; overflow-y: auto;">
                <form id="frm_add" class="view-form">

                    <div class="row" id="data">

</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal">
                    {{ __('translation.general.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
{{-- ADD MODEL END --}}
