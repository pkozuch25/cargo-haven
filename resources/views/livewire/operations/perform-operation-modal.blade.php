<x-modal id="perform-operation-modal" size="md" title="{{ $title }}" icon="ti ti-file-text">
    <x-slot name="modalBody">
        <div class="min-h-[200px]">
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
                </div>
            @else
                <div class="row">
                </div>
                @endif
            <div class="row">
                <div class="col-md-12">
                    <x-textarea :label="__('Notes')" wire:model='notes'></x-textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                </div>
            </div>
        </div>
    </x-slot>
    <x-slot name="modalFooter">
        <x-button class="btn-success mr-2" wire:click='performOperation'>
            {{ __('Execute') }}
        </x-button>
    </x-slot>
</x-modal>
