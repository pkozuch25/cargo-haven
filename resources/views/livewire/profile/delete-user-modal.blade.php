<div>
    <x-modal id="delete-user-modal" size="md" title="{{ __('Are you sure you want to delete your account?') }}" icon="fa fa-sign-in">
        <x-slot name="modalBody">

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input wire:model='password'
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
        </x-slot>
        
        <x-slot name="modalFooter">
            
            <x-danger-button class="ms-3" wire:click='deleteUser()'>
                {{ __('Delete Account') }}
            </x-danger-button>
        </x-slot>
        
    </x-modal>
</div>