<div>
    <div class="row">
        <div class="col-lg-2">
            <x-text-input-full :label="__('Container number')" wire:model='dispositionUnit.disu_container_number'></x-text-input-full>
            <x-input-error :messages="$errors->get('dispositionUnit.disu_container_number')" class="mt-2" />
        </div>
        @if($this->checkIfIsInTruckRelation())
            <div class="col-lg-2">
                <x-text-input-full :label="__('Car number plate')" wire:model='dispositionUnit.disu_car_number'></x-text-input-full>
                <x-input-error :messages="$errors->get('dispositionUnit.disu_car_number')" class="mt-2" />
            </div>
        @endif
        <div class="col-lg-2">
            <x-textarea :label="__('Notes')" wire:model='dispositionUnit.disu_notes'></x-textarea>
            <x-input-error :messages="$errors->get('dispositionUnit.disu_notes')" class="mt-2" />
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-sm-1">
            <x-button class="btn-success" wire:click='addDispositionUnit'>
                {{ __('Add') }}
            </x-button>
        </div>
    </div>
</div>
