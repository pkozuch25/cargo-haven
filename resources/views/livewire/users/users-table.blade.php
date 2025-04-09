<div class="space-y-4">
    <div class="flex justify-between items-center mb-2">
        @include('livewire.partial.table-perpage')
    </div>
    <div style="position: relative">
        @include('livewire.partial.loader')
        <div class="table-responsive">
            <table style="width: 100%">
                <x-thead-table>
                    <x-table-row>
                        <x-th-table wire:click="sort('name')"
                            class="{{ $sortColumn == 'name' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Name') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('email')"
                            class="{{ $sortColumn == 'email' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Email') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('created_at')"
                            class="{{ $sortColumn == 'created_at' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Created at') }}
                        </x-th-table>
                        <x-th-table width="15%">
                            {{ __('Roles') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Actions') }}
                        </x-th-table>
                    </x-table-row>
                    <x-table-row>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.name"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.email"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.created_at"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="roles-select" multiple>
                                    @foreach (\Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{ $role->id }}" @if(array_key_exists($role->name, $selectedRoles ?? [])) selected @endif>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search></x-th-table-search>
                    </x-table-row>
                </x-thead-table>
                <tbody>
                    @forelse($data as $user)
                        <x-tr-hover>
                            <x-td-table>{{ $user->name }}</x-td-table>
                            <x-td-table>{{ $user->email }}</x-td-table>
                            <x-td-table class="text-center">{{ $user->created_at }}</x-td-table>
                            <x-td-table style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap;">
                                @foreach($user->roles as $role)
                                    <x-pill class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 mr-1 mb-1">
                                        {{ $role->name }}
                                    </x-pill>
                                @endforeach
                            </x-td-table>
                            <x-td-table class="text-center">
                                <x-button icon="ti ti-user-check" class="btn-primary px-[6px] py-[3px]" modal="manage-user-modal" wire:click="$dispatch('openManageUserModal', {user: {{ $user }}})" title="{{ __('Manage user') }}">
                                </x-button>
                            </x-td-table>
                        </x-tr-hover>
                    @empty
                        <x-tr-hover>
                            <td colspan="5" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                        </x-tr-hover>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.partial.table-pagination')

    @push('javascript')
        <script>
            window.addEventListener('refreshSelect2', function(event) {
                $(function() {
                    initSelect2();
                })
            })

            initSelect2();

            function initSelect2() {
                $('#roles-select').select2();
                $('#roles-select').on('change', function (e) {
                    var data = $('#roles-select').select2("val");
                    @this.set('searchTerm.relationSelectMultiple.roles.id', data);
                });
            };
        </script>
    @endpush
</div>
