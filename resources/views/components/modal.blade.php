<div class="modal modal-cargo fade" id="{{ $id }}" role="dialog" wire:ignore.self wire:click.self='closeModal(true)'>
    <div class="modal-dialog modal-{{ $size }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" wire:click.self='closeModal(true)' data-dismiss="modal" aria-label="{{ __('Zamknij') }}"><i class="ti ti-x"></i></button>
                <h4 class="modal-title"><i class="{{ $icon }}" style="margin-top:4px;"></i>{{ $title }}</h4>      
            </div>
   
            <div class="modal-body">
                @include('livewire.partial.modal-loader')
                {{ $modalBody }}
            </div>

            <div class="modal-footer">
                {{ $modalFooter }}
                <x-button-close-modal></x-button-close-modal>
            </div>
        </div>
    </div>
</div>

@push('javascript')
    <script>
        window.addEventListener('closeModal', event => {
            $('#{{ $id }}').modal('hide');
        });
    </script>
@endpush
     
     
