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
                        <x-th-table wire:click="sort('sy_name')"
                            class="{{ $sortColumn == 'sy_name' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Name') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('sy_name_short')"
                            class="{{ $sortColumn == 'sy_name_short' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Name short') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('sy_length')"
                            class="{{ $sortColumn == 'sy_length' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Length') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('sy_width')"
                            class="{{ $sortColumn == 'sy_width' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Width') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('sy_cell_count')"
                            class="{{ $sortColumn == 'sy_cell_count' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Cell count') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('sy_height')"
                            class="{{ $sortColumn == 'sy_height' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Height') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Actions') }}
                        </x-th-table>
                    </x-table-row>
                    <x-table-row>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.sy_name"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.sy_name_short"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.sy_length"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.sy_width"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.sy_cell_count"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.sy_height"/>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                    </x-table-row>
                </x-thead-table>
                <tbody>
                    @forelse($data as $yard)
                        <x-tr-hover>
                            <x-td-table class="text-center">{{ $yard->sy_name }}</x-td-table>
                            <x-td-table class="text-center">{{ $yard->sy_name_short }}</x-td-table>
                            <x-td-table class="text-center">{{ $yard->sy_length }}</x-td-table>
                            <x-td-table class="text-center">{{ $yard->sy_width }}</x-td-table>
                            <x-td-table class="text-center">{{ $yard->sy_cell_count }}</x-td-table>
                            <x-td-table class="text-center">{{ $yard->sy_height }}</x-td-table>
                            <x-td-table class="float-right">
                                @can('edit_storage_yards')
                                    <x-button icon="fa fa-edit" class="btn-primary px-[6px] py-[3px]" modal="add-edit-storage-yard-modal" wire:click="dispatch('openAddEditYardModal', {yard: {{ $yard->sy_id }} })"/>
                                @endcan
                            </x-td-table>
                        </x-tr-hover>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.partial.table-pagination')
</div>
