<div class="modal modal-blur fade"
     id="tablar-kit-confirm-modal"
     tabindex="-1"
     role="dialog"
     aria-hidden="true"
     aria-labelledby="tablar-kit-confirm-modal-title">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-status bg-danger" id="tablar-kit-confirm-modal-status"></div>

            <div class="modal-header">
                <h5 class="modal-title" id="tablar-kit-confirm-modal-title">{{ __('Are you sure?') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>

            <div class="modal-body">
                <p class="mb-0" id="tablar-kit-confirm-modal-message"></p>
                <div class="alert alert-danger d-none mt-3" id="tablar-kit-confirm-modal-alert" role="alert"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>
                <button type="button" class="btn btn-danger" id="tablar-kit-confirm-modal-button">
                    {{ __('Confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
