<button type="button" class="btn btn-secondary modal-close" data-bs-dismiss="modal" wire:click='closeModal(true)'>{{ ($slot != '') ? $slot :  __('Zamknij') }}</button>
