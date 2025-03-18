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
                        <x-th-table width="10%" wire:click="sort('disu_container_number')"
                            class="{{ $sortColumn == 'disu_container_number' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Container number') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __("Relation from") }}
                        </x-th-table>
                        <x-th-table>
                            {{ __("Relation to") }}
                        </x-th-table>
                        <x-th-table width="12%">
                            {{ __('Disposition') }}
                            <hr>
                            {{ __("Suggested date") }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Notes') }}
                        </x-th-table>
                        <x-th-table width="4%">
                            {{ __('Actions') }}
                        </x-th-table>
                    </x-table-row>
                </x-thead-table>
                <tbody>
                    @forelse($data as $operation)
                        <x-tr-hover>
                            <x-td-table class="text-center">{{ $operation->disu_container_number }}</x-td-table>
                            <x-td-table class="text-center">{{ $operation->disposition?->dis_relation_from->name() }}</x-td-table>
                            <x-td-table class="text-center">{{ $operation->disposition?->dis_relation_to->name() }}</x-td-table>
                            <x-td-table class="text-center"><a href="/">{{ $operation->disposition?->dis_number }}</a></x-td-table> {{-- todo przekierowanie do otwartego modala z dyspozycją --}}
                            <x-td-table class="text-center">{{ $operation->disu_notes }}</x-td-table>
                            <x-td-table class="float-right">
                                {{-- todo przycisk do wykonania operacji --}}
                            </x-td-table>
                        </x-tr-hover>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.partial.table-pagination')
</div>
