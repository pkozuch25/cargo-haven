<x-modal id="perform-operation-modal" size="md" title="{{ $title }}" icon="ti ti-file-text">
    <x-slot name="modalBody">
        <div class="min-h-[200px]">
            @if($hasUnitsAbove)
                <div class="row h-100 d-flex align-items-center justify-content-center">
                    <div class="col-md-12">
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="ti ti-alert-triangle me-2 fs-4"></i>
                            <div>
                                {{ __("Cannot execute operation, system found existing deposits placed above current storage cell: ") }}
                                {{ $cellsAboveString }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <x-form-select :label="__('Card')" wire:model.live='selectedCard'>
                            <option value="" selected>{{ __("Select") }}</option>
                            <option value="newCard">{{ __("New card") }}</option>
                            @if($availableCards)
                                @foreach ($availableCards as $card)
                                    <option value="{{ $card['tc_id'] }}">{{ $card['tc_number'] }}</option>
                                @endforeach
                            @endif
                        </x-form-select>
                        <x-input-error :messages="$errors->get('selectedCard')" class="mt-2" />
                    </div>
                </div>
                @if($this->relationToCarriage())
                    <div class="row">
                        <div class="col-md-6">
                            <x-input-full type="number" min="1" :label="__('Net weight')" wire:model='netWeight'></x-input-full>
                            <x-input-error :messages="$errors->get('netWeight')" class="mt-2" />
                        </div>
                        <div class="col-md-6">
                            <x-input-full type="number" min="1" :label="__('Tare weight')" wire:model='tareWeight'></x-input-full>
                            <x-input-error :messages="$errors->get('tareWeight')" class="mt-2" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <x-input-full :label="__('Carriage number')" wire:model='carriageNumberTo'></x-input-full>
                            <x-input-error :messages="$errors->get('carriageNumberTo')" class="mt-2" />
                        </div>
                    </div>
                @elseif($this->relationToTruck())
                    <div class="row">
                        <div class="col-md-6">
                            <x-input-full type="number" min="1" :label="__('Net weight')" wire:model='netWeight'></x-input-full>
                            <x-input-error :messages="$errors->get('netWeight')" class="mt-2" />
                        </div>
                        <div class="col-md-6">
                            <x-input-full :label="__('Truck number')" wire:model='truckNumberTo'></x-input-full>
                            <x-input-error :messages="$errors->get('truckNumberTo')" class="mt-2" />
                        </div>
                    </div>
                @else
                    @if ($availableStorageCells)
                        <div class="row">
                            <div class="col-md-4">
                                <x-input-label>
                                    {{ __('Column') }}
                                </x-input-label>
                                <x-input-full type="number" wire:model.live='checkIfColumnIsValid'>
                                </x-input-full>
                                <x-input-error :messages="$errors->get('selectedColumn')" class="mt-2" />
                            </div>
                            <div class="col-md-4">
                                <div class="row mb-1">
                                    @if ($selectedColumn)
                                        <x-input-label>
                                            {{ __('Row') }}
                                        </x-input-label>
                                        @foreach ($availableStorageCells[$selectedColumn] as $key => $row)
                                            <div class="row mb-1">
                                                <div class="col-md-12">
                                                    <x-button wire:key='{{ "perform-operation-row-" . $key }}' :class="$this->getRowClass($key)" style="width: 100%; padding: 3px" :title="$key" wire:click="selectRow('{{ $key }}')">{{ $key }}</x-button>
                                                </div>
                                            </div>
                                        @endforeach
                                        <x-input-error :messages="$errors->get('selectedRow')" class="mt-2" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row mb-1">
                                    @if ($selectedRow)
                                        <x-input-label>
                                            {{ __('Height') }}
                                        </x-input-label>
                                        @if($disposition->storageYard)
                                            @for($i = $disposition->storageYard->sy_height; $i > 0; $i--)
                                                <div class="row mb-1">
                                                    <div class="col-md-12">
                                                        @php
                                                            $containerCanBePlacedOnThisLevel = $this->checkIfContainerCanBePlacedOnThisLevel($i);
                                                        @endphp
                                                        <x-button wire:key='{{ "perform-operation-height-" . $i }}' :disabled="!$containerCanBePlacedOnThisLevel"
                                                            :class="$this->getHeightClass($i)" style="width: 100%; padding: 3px"
                                                            :title="$i" wire:click="selectHeight('{{ $i }}')">
                                                            @if($containerCanBePlacedOnThisLevel)
                                                                {{ $i }}
                                                            @else
                                                                {{ $this->getPhraseToDisplayInsideHeightButton($i)}}
                                                            @endif
                                                            </x-button>
                                                    </div>
                                                </div>
                                            @endfor
                                            <x-input-error :messages="$errors->get('selectedHeight')" class="mt-2" />
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <x-textarea :label="__('Notes')" wire:model='notes'></x-textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                    </div>
                </div>
            @endif
        </div>
    </x-slot>
    <x-slot name="modalFooter">
        @if(!$hasUnitsAbove)
            <x-button class="btn-success mr-2" wire:click='performOperation'>
                {{ __('Execute') }}
            </x-button>
        @endif
    </x-slot>
</x-modal>
