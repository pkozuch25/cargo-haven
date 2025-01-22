<div>
    <x-modal id="change-request-status-modal" size="md" title="{{ __('Change request status') }}" icon="fas fa-tasks">
        <x-slot name="modalBody">

            <div class="mt-6">
                <select class="form-control" style="color: white !important" wire:model="requestStatus">
                    <option value="null">{{ __("Select") }}</option>
                    @foreach (App\Enums\RegistrationRequestStatusEnum::cases() as $statusEnum)
                        <option value="{{ $statusEnum->value }}">{{ __($statusEnum->name()) }}</option>
                    @endforeach
                </select>
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