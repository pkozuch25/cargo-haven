<div>
    <x-modal id="change-request-status-modal" size="sm" title="{{ __('Change request status') }}" icon="fas fa-tasks">
        <x-slot name="modalBody">
            <div class="mt-1 mb-4">
                <x-select wire:model="requestStatus" :label="__('Status')">
                    <option value="null">{{ __("Select") }}</option>
                    @foreach (App\Enums\RegistrationRequestStatusEnum::cases() as $statusEnum)
                        <option value="{{ $statusEnum->value }}">{{ __($statusEnum->name()) }}</option>
                    @endforeach
                </x-select>
                <x-input-error :messages="$errors->get('requestStatus')" class="mt-2" />
            </div>
            
            @if($availableRoles)
                <x-input-label value="{{ __('Roles:') }}" />
                <div>
                    @foreach($availableRoles as $role)
                        <div class="flex items-center">
                            <x-checkbox wire:model="selectedRoles" value="{{ $role->id }}" :id="'role-'.$role->id" :label="$role->name" >
                            </x-checkbox>
                        </div>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('selectedRoles')" class="mt-2" />
            @endif
            
            @if($availableStorageYards && count($availableStorageYards) > 0)
                <x-input-label value="{{ __('User yards:') }}" />
                <div wire:ignore>
                    <select style="height: 30px;" id="storage-yards-select" multiple>
                        @foreach($availableStorageYards as $yard)
                            <option value="{{ $yard->sy_id }}" @if(in_array($yard->sy_id, $selectedStorageYards)) selected @endif>{{ $yard->sy_name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('selectedStorageYards')" class="mt-2" />
            @endif
        </x-slot>

        <x-slot name="modalFooter">
            <x-button class="btn-success" wire:click='saveStatus()'>
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-modal>
    @push('javascript')
        <script>
            var myModalEl = document.getElementById('change-request-status-modal')
            
            myModalEl.addEventListener('shown.bs.modal', event => {
                $('#storage-yards-select').select2({
                    dropdownParent: $('#change-request-status-modal'),
                });
                $('#storage-yards-select').on('change', function (e) {
                    var data = $('#storage-yards-select').select2("val");
                    @this.set('selectedStorageYards', data);
                });
            })
        </script>
    @endpush
</div>

