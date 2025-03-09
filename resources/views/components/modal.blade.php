<div class="modal modal-cargo fade" id="{{ $id }}" role="dialog" wire:ignore.self wire:click.self='closeModal(true)' data-bs-focus="false">
    <div class="modal-dialog modal-{{ $size }}">
        <div class="modal-content">
            <div class="modal-header flex items-center justify-between">
                <h4 class="modal-title"><i class="{{ $icon }} mr-2" style="font-size: 24px"></i>{{ $title }}</h4>
                @if(isset($pillText) && isset($pillClass))
                    <x-pill class="{{ $pillClass }}">{{ $pillText }}</x-pill>
                @endif
            </div>

            <div class="modal-body">
                @include('livewire.partial.loader')
                {{ $modalBody }}
            </div>

            <div class="modal-footer">
                {{ $modalFooter }}
                <x-button-close-modal></x-button-close-modal>
            </div>
        </div>
    </div>
</div>



