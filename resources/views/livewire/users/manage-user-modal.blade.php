<div>
    <x-modal id="manage-user-modal" size="sm" title="{{ __('Manage user') }}" icon="ti ti-user-check">
        <x-slot name="modalBody">
            <div class="mt-4 mb-4">
                <div class="mb-2">{{ __('User') }}: <strong>{{ $user->name ?? '' }}</strong></div>
                <div class="mb-4">{{ __('Email') }}: <strong>{{ $user->email ?? '' }}</strong></div>
                
                @if($availableRoles)
                    <x-input-label value="{{ __('Select roles:') }}" />
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
            </div>
        </x-slot>

        <x-slot name="modalFooter">
            <x-button class="btn-success" wire:click='saveRoles()'>
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-modal>
    @push('javascript')
        <script>
            var myModalEl = document.getElementById('manage-user-modal')
            
            myModalEl.addEventListener('shown.bs.modal', event => {
                $('#storage-yards-select').select2({
                    dropdownParent: $('#manage-user-modal'),
                });
                $('#storage-yards-select').on('change', function (e) {
                    var data = $('#storage-yards-select').select2("val");
                    @this.set('selectedStorageYards', data);
                });
            })
        </script>
    @endpush
</div>
