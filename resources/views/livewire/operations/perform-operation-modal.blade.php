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
        </div>
    </x-slot>
    <x-slot name="modalFooter">
        <x-button class="btn-success mr-2" wire:click='performOperation'>
            {{ __('Execute') }}
        </x-button>
    </x-slot>
</x-modal>
