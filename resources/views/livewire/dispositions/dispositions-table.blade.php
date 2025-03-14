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
                        <x-th-table wire:click="sort('dis_number')"
                            class="{{ $sortColumn == 'dis_number' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Number') }}
                        </x-th-table>
                        <x-th-table width="8%" wire:click="sort('dis_relation_from')"
                            class="{{ $sortColumn == 'dis_relation_from' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Relation from') }}
                        </x-th-table>
                        <x-th-table width="8%" wire:click="sort('dis_relation_to')"
                            class="{{ $sortColumn == 'dis_relation_to' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Relation to') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('dis_suggested_date')"
                            class="{{ $sortColumn == 'dis_suggested_date' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Suggested date') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('dis_start_date')"
                            class="{{ $sortColumn == 'dis_start_date' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Start date') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('dis_completion_date')"
                            class="{{ $sortColumn == 'dis_completion_date' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Completion date') }}
                        </x-th-table>
                        <x-th-table width="10%">
                            {{ __('Status') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Created') }}
                        </x-th-table>
                        <x-th-table width="10%">
                            {{ __('Yard') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Units') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Actions') }}
                        </x-th-table>
                    </x-table-row>
                    <x-table-row>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.dis_number"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="disposition-relation-from-select" multiple>
                                    @foreach (\App\Enums\OperationRelationEnum::cases() as $operation)
                                        <option value="{{ $operation }}">{{ $operation->name() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="disposition-relation-to-select" multiple>
                                    @foreach (\App\Enums\OperationRelationEnum::cases() as $operation)
                                        <option value="{{ $operation }}">{{ $operation->name() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input class="flatpickr-range" wire:model.live.debounce.500ms="searchTerm.date.dis_suggested_date"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input class="flatpickr-range" wire:model.live.debounce.500ms="searchTerm.date.dis_start_date"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input class="flatpickr-range" wire:model.live.debounce.500ms="searchTerm.date.dis_completion_date"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="disposition-status-select" multiple>
                                    @foreach (\App\Enums\DispositionStatusEnum::cases() as $status)
                                        <option value="{{ $status }}">{{ $status->name() }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="disposition-users-select" multiple>
                                    @foreach ($allUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                            <div wire:ignore>
                                <select style="height: 30px;" id="disposition-yard-select" multiple>
                                    @foreach ($allYards as $yard)
                                        <option value="{{ $yard->sy_id }}">{{ $yard->sy_name_short }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                    </x-table-row>
                </x-thead-table>
                <tbody>
                    @forelse($data as $disposition)
                        <x-tr-hover>
                            <x-td-table class="text-center">{{ $disposition->dis_number }}</x-td-table>
                            <x-td-table class="text-center">{{ $disposition->dis_relation_from->name() }}</x-td-table>
                            <x-td-table class="text-center">{{ $disposition->dis_relation_to->name() }}</x-td-table>
                            <x-td-table class="text-center">{{ Carbon\Carbon::create($disposition->dis_suggested_date)->format('Y-m-d') }}</x-td-table>
                            <x-td-table class="text-center">{{ $disposition->dis_start_date }}</x-td-table>
                            <x-td-table class="text-center">{{ $disposition->dis_completion_date }}</x-td-table>
                            <x-td-table class="text-center"><x-pill class="{{ $disposition->dis_status->color() }}">{{ $disposition->dis_status->name() }}</x-pill></x-td-table>
                            <x-td-table class="text-center">{{ $disposition->createdBy->name }}</x-td-table>
                            <x-td-table class="text-center">{{ $disposition->storageYard?->sy_name_short }}<br> {{ $disposition->storageYard?->sy_name }}</x-td-table>
                            <x-td-table class="text-center">{{ $disposition->units_count}}</x-td-table>
                            <x-td-table class="float-right">
                                @can('edit_dispositions')
                                    <x-button icon="fa fa-edit" class="btn-primary px-[6px] py-[3px]" modal="add-edit-disposition-modal" wire:click="dispatch('openAddEditDispositionModal', {disposition: {{ $disposition->dis_id }} })"/>
                                @endcan
                            </x-td-table>
                        </x-tr-hover>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                        </tr>
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
                $('#disposition-status-select').select2();
                $('#disposition-status-select').on('change', function (e) {
                    var data = $('#disposition-status-select').select2("val");
                    @this.set('searchTerm.selectMultiple.dis_status', data);
                });

                $('#disposition-yard-select').select2();
                $('#disposition-yard-select').on('change', function (e) {
                    var data = $('#disposition-yard-select').select2("val");
                    @this.set('searchTerm.selectMultiple.dis_yard_id', data);
                });

                $('#disposition-users-select').select2();
                $('#disposition-users-select').on('change', function (e) {
                    var data = $('#disposition-users-select').select2("val");
                    @this.set('searchTerm.selectMultiple.dis_created_by_id', data);
                });

                $('#disposition-relation-from-select').select2();
                $('#disposition-relation-from-select').on('change', function (e) {
                    var data = $('#disposition-relation-from-select').select2("val");
                    @this.set('searchTerm.selectMultiple.dis_relation_from', data);
                });

                $('#disposition-relation-to-select').select2();
                $('#disposition-relation-to-select').on('change', function (e) {
                    var data = $('#disposition-relation-to-select').select2("val");
                    @this.set('searchTerm.selectMultiple.dis_relation_to', data);
                });
            };
        </script>
    @endpush
</div>
