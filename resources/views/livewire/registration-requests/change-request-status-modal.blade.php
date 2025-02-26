<div>
    <x-modal id="change-request-status-modal" size="sm" title="{{ __('Change request status') }}" icon="fas fa-tasks">
        <x-slot name="modalBody">
            <div class="mt-4 mb-4">
                <x-select wire:model="requestStatus">
                    <option value="null">{{ __("Select") }}</option>
                    @foreach (App\Enums\RegistrationRequestStatusEnum::cases() as $statusEnum)
                        <option value="{{ $statusEnum->value }}">{{ __($statusEnum->name()) }}</option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('requestStatus')" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="modalFooter">

            <x-button class="btn-success" wire:click='saveStatus()'>
                {{ __('Save') }}
            </x-button>
        </x-slot>

    </x-modal>
</div>
