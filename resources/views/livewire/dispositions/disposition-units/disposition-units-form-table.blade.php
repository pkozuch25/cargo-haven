<div>
    @if($disposition->dis_status != App\Enums\DispositionStatusEnum::FINALIZED)
        <hr class="mt-4 mb-1"/>
        <div class="row">
            <div class="col-lg-2">
                <x-input-full :disabled="$this->checkIfDispositionWasCancelledOrFinalized()" :label="__('Container number')" wire:model='dispositionUnit.disu_container_number'></x-input-full>
                <x-input-error :messages="$errors->get('dispositionUnit.disu_container_number')" class="mt-2" />
            </div>
            @if($this->checkIfIsInTruckRelation())
                <div class="col-lg-2">
                    <x-input-full :disabled="$this->checkIfDispositionWasCancelledOrFinalized()" :label="__('Car number plate')" wire:model='dispositionUnit.disu_car_number'></x-input-full>
                    <x-input-error :messages="$errors->get('dispositionUnit.disu_car_number')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-input-full :disabled="$this->checkIfDispositionWasCancelledOrFinalized()" :label="__('Driver')" wire:model='dispositionUnit.disu_driver'></x-input-full>
                    <x-input-error :messages="$errors->get('dispositionUnit.disu_driver')" class="mt-2" />
                </div>
            @endif
            <div class="col-lg-2">
                <x-input-full :disabled="$this->checkIfDispositionWasCancelledOrFinalized()" min="1" type="number" :label="__('Net weight')" wire:model='dispositionUnit.disu_container_net_weight'></x-input-full>
                <x-input-error :messages="$errors->get('dispositionUnit.disu_container_net_weight')" class="mt-2" />
            </div>
            @if($this->checkIfIsInCarriageRelation())
                <div class="col-lg-2">
                    <x-input-full :disabled="$this->checkIfDispositionWasCancelledOrFinalized()" min="1" type="number" :label="__('Tare weight')" wire:model='dispositionUnit.disu_container_tare_weight'></x-input-full>
                    <x-input-error :messages="$errors->get('dispositionUnit.disu_container_tare_weight')" class="mt-2" />
                </div>
                <div class="col-lg-2">
                    <x-input-full :disabled="$this->checkIfDispositionWasCancelledOrFinalized()" :label="__('Carriage number')" wire:model='dispositionUnit.disu_carriage_number'></x-input-full>
                    <x-input-error :messages="$errors->get('dispositionUnit.disu_carriage_number')" class="mt-2" />
                </div>
            @endif
            <div class="col-lg-2">
                <x-textarea :disabled="$this->checkIfDispositionWasCancelledOrFinalized()" :label="__('Notes')" wire:model='dispositionUnit.disu_notes'></x-textarea>
                <x-input-error :messages="$errors->get('dispositionUnit.disu_notes')" class="mt-2" />
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-1">
                @if(!$this->checkIfDispositionWasCancelledOrFinalized())
                    <x-button class="btn-success" wire:click='addDispositionUnit'>
                        {{ __('Add') }}
                    </x-button>
                @endif
            </div>
        </div>
    @endif
    @if ($this->dispositionHasUnits())
        <hr class="my-4"/>
        @include('livewire.dispositions.disposition-units.partial.unit-table')
    @endif
</div>
