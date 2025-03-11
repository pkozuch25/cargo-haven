<x-modal id="add-edit-storage-yard-modal" size="fullscreen" title="{{ $title }}" icon="ti ti-box">
    <x-slot name="modalBody">
        @if ($yard)
            <div class="row">
                <div class="col-lg-2">
                    <x-input-full :label="__('Name')" wire:model='yard.sy_name' />
                    <x-input-error :messages="$errors->get('yard.sy_name')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-input-full :label="__('Name short')" wire:model='yard.sy_name_short' />
                    <x-input-error :messages="$errors->get('yard.sy_name_short')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-input-full max="100" min="1" type="number" :disabled="$edit" :label="__('Length')" wire:model='yard.sy_length' />
                    <x-input-error :messages="$errors->get('yard.sy_length')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-input-full max="100" min="1" type="number" :disabled="$edit" :label="__('Width')" wire:model='yard.sy_width' />
                    <x-input-error :messages="$errors->get('yard.sy_width')" class="mt-2" />
                </div>
                <div class="col-lg-1">
                    <x-input-full max="50" min="1" type="number" :disabled="$edit" :label="__('Cell count')" wire:model='yard.sy_cell_count' />
                    <x-input-error :messages="$errors->get('yard.sy_cell_count')" class="mt-2" />
                </div>
                <div class="col-lg-1">
                    <x-input-full max="20" min="1" type="number" :disabled="$edit" :label="__('Row count')" wire:model='yard.sy_row_count' />
                    <x-input-error :messages="$errors->get('yard.sy_row_count')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-input-full max="4" min="1" type="number" :disabled="$edit" :label="__('Height')" wire:model='yard.sy_height' />
                    <x-input-error :messages="$errors->get('yard.sy_height')" class="mt-2" />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-11">
                    <x-button class="btn-success mr-2" wire:click='save'>
                        {{ __('Save') }}
                    </x-button>
                </div>
            </div>
        @endif
    </x-slot>
    <x-slot name="modalFooter">
    </x-slot>
</x-modal>
