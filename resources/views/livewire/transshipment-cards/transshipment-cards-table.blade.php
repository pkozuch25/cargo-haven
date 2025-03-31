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
                        <x-th-table wire:click="sort('tc_number')"
                            class="{{ $sortColumn == 'tc_number' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Number') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('created_at')"
                            class="{{ $sortColumn == 'created_at' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Created at') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('tc_relation_from')"
                            class="{{ $sortColumn == 'tc_relation_from' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Relation from') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('tc_relation_to')"
                            class="{{ $sortColumn == 'tc_relation_to' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Relation to') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('tc_status')"
                            class="{{ $sortColumn == 'tc_status' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Status') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Storage yard') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Created By') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Actions') }}
                        </x-th-table>
                    </x-table-row>
                    <x-table-row>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.tc_number"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input class="flatpickr-range" wire:model.live.debounce.500ms="searchTerm.date.created_at"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="card-relation-from-select" multiple>
                                    @foreach ($relationEnum::cases() as $relation)
                                        <option value="{{ $relation }}">{{ $relation->name() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="card-relation-to-select" multiple>
                                    @foreach ($relationEnum::cases() as $relation)
                                        <option value="{{ $relation }}">{{ $relation->name() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="card-status-select" multiple>
                                    @foreach ($statusEnum::cases() as $status)
                                        <option value="{{ $status }}">{{ $status->name() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                    </x-table-row>
                </x-thead-table>
                <tbody>
                    @forelse($data as $card)
                        <x-tr-hover>
                            <x-td-table class="text-center">{{ $card->tc_number }}</x-td-table>
                            <x-td-table class="text-center">{{ $card->created_at->format('Y-m-d H:i') }}</x-td-table>
                            <x-td-table class="text-center">{{ $card->tc_relation_from ? $card->tc_relation_from->name() : '' }}</x-td-table>
                            <x-td-table class="text-center">{{ $card->tc_relation_to ? $card->tc_relation_to->name() : '' }}</x-td-table>
                            <x-td-table class="text-center">
                                @if($card->tc_status)
                                <x-pill class="{{ $card->tc_status->color() }}">
                                        {{ $card->tc_status->name() }}
                                </x-pill>
                                @endif
                            </x-td-table>
                            <x-td-table class="text-center">{{ $card->storageYard?->sy_name }}</x-td-table>
                            <x-td-table class="text-center">{{ $card->createdBy?->name }}</x-td-table>
                            <x-td-table class="float-right">
                                @can('view_transshipment_cards')
                                    <x-button icon="ti ti-eye" class="btn-primary px-[6px] py-[3px]" title="{{ __('View') }}"
                                        modal="show-transshipment-card-details-modal" wire:click="dispatch('openTransshipmentCardDetailsModal', {cardId: {{ $card->tc_id }} })" />
                                @endcan
                            </x-td-table>
                        </x-tr-hover>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.partial.table-pagination')

    @push('javascript')
        <script>
            initSelect2();

            function initSelect2() {
                $('#card-relation-from-select').select2();
                $('#card-relation-from-select').on('change', function (e) {
                    var data = $('#card-relation-from-select').select2("val");
                    @this.set('searchTerm.selectMultiple.tc_relation_from', data);
                });

                $('#card-relation-to-select').select2();
                $('#card-relation-to-select').on('change', function (e) {
                    var data = $('#card-relation-to-select').select2("val");
                    @this.set('searchTerm.selectMultiple.tc_relation_to', data);
                });

                $('#card-status-select').select2();
                $('#card-status-select').on('change', function (e) {
                    var data = $('#card-status-select').select2("val");
                    @this.set('searchTerm.selectMultiple.tc_status', data);
                });
            };
        </script>
    @endpush
</div>
