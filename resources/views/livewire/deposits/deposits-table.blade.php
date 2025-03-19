<div class="space-y-4">
    <div class="flex justify-between items-center mb-2">
        @include('livewire.partial.table-perpage')
        <div>
            <x-checkbox type='checkbox' style='margin-left:20px;' :label="__('Display archived deposits')" :id="'display-archived-deposits-checkbox'" wire:model.live="displayArchivedCheckbox" />
        </div>
    </div>
    <div style="position: relative">
        @include('livewire.partial.loader')
        <div class="table-responsive">
            <table style="width: 100%">
                <x-thead-table>
                    <x-table-row>
                        <x-th-table wire:click="sort('dep_number')"
                            class="{{ $sortColumn == 'dep_number' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Number') }}
                        </x-th-table>
                        <x-th-table width="8%" wire:click="sort('dep_arrival_date')"
                            class="{{ $sortColumn == 'dep_arrival_date' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Arrival date') }}
                        </x-th-table>
                        <x-th-table width="8%" wire:click="sort('dep_departure_date')"
                            class="{{ $sortColumn == 'dep_departure_date' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Departure date') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('dep_gross_weight')"
                            class="{{ $sortColumn == 'dep_gross_weight' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Gross weight') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('dep_tare_weight')"
                            class="{{ $sortColumn == 'dep_tare_weight' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Tare weight') }}
                        </x-th-table>
                        <x-th-table wire:click="sort('dep_net_weight')"
                            class="{{ $sortColumn == 'dep_net_weight' ? ($sortDirection == 'desc' ? 'sorting sorting_desc' : 'sorting sorting_asc') : 'sorting' }}">
                            {{ __('Net weight') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Arrival disposition') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Departure disposition') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Arrival card') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Departure card') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Storage cell') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Actions') }}
                        </x-th-table>
                    </x-table-row>
                    <x-table-row>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.dep_number"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input class="flatpickr-range" wire:model.live.debounce.500ms="searchTerm.date.dep_arrival_date"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input class="flatpickr-range" wire:model.live.debounce.500ms="searchTerm.date.dep_departure_date"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.dep_gross_weight"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.dep_tare_weight"/>
                        </x-th-table-search>
                        <x-th-table-search>
                            <x-search-text-input wire:model.live.debounce.500ms="searchTerm.text.dep_net_weight"/>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                        <x-th-table-search>
                        </x-th-table-search>
                        <x-th-table-search>
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
                    @forelse($data as $deposit)
                        <x-tr-hover>
                            <x-td-table class="text-center">{{ $deposit->dep_number }}</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->dep_arrival_date }}</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->dep_departure_date }}</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->dep_gross_weight }}</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->dep_tare_weight }}</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->dep_net_weight }}</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->arrivalDispositionUnit?->disposition?->dis_number }}</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->departureDispositionUnit?->disposition?->dis_number }}</x-td-table>
                            <x-td-table class="text-center">placeholder</x-td-table>
                            <x-td-table class="text-center">placeholder</x-td-table>
                            <x-td-table class="text-center">{{ $deposit->storageCell?->cell_text }}</x-td-table>
                            <x-td-table class="float-right">
                                @if(can('add_dispositions') && !$deposit->dep_departure_disu_id && !$deposit->dep_departure_date && $this->checkIfDepositIsFromTheSameYard($deposit->dep_id))
                                    @if(!in_array($deposit->dep_id, $dispositionCreationArray))
                                        <x-button icon="ti ti-plus" class="btn-primary px-[6px] py-[3px]" title="{{ __('Generate disposition from this deposit') }}" wire:click="addDepositToDispositionCreationArray({{ $deposit->dep_id }})"/>
                                    @else
                                        <x-button icon="ti ti-minus" class="btn-primary px-[6px] py-[3px]" title="{{ __('Don\'t generate disposition from this deposit') }}" wire:click="removeDepositFromDispositionCreationArray({{ $deposit->dep_id }})"/>
                                    @endif
                                @endif
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
        @if($dispositionCreationArray != [])
            <div class="row">
                <div class="col-lg-2">
                    <x-form-select :label="__('Relation to')" wire:model.live='relationTo'>
                        <option value="">{{ __("Select") }}</option>
                        @if($relationToRelations)
                            @foreach ($relationToRelations as $relation)
                                <option value="{{$relation}}">{{ $relation->name() }}</option>
                            @endforeach
                        @endif
                    </x-form-select>
                    <x-input-error :messages="$errors->get('relationTo')" class="mt-2" />
                </div>
                @if($this->checkIfIsInTruckRelation())
                    <div class="col-lg-2">
                        <x-input-full :label="__('Car number plate')" wire:model='carNumber'></x-input-full>
                        <x-input-error :messages="$errors->get('carNumber')" class="mt-2" />
                    </div>
                    <div class="col-lg-2">
                        <x-input-full :label="__('Driver')" wire:model='driver'></x-input-full>
                        <x-input-error :messages="$errors->get('driver')" class="mt-2" />
                    </div>
                @endif
                @if($this->checkIfIsInCarriageRelation())
                    <div class="col-lg-2">
                        <x-input-full :label="__('Carriage number')" wire:model='carriageNumber'></x-input-full>
                        <x-input-error :messages="$errors->get('carriageNumber')" class="mt-2" />
                    </div>
                @endif
                <div class="col-lg-2" style="align-content: end;">
                    @if($relationTo != "")
                        <x-button class="btn-success" wire:click="generateDispositionFromSelectedDeposits">{{ __('Generate disposition') }}</x-button>
                    @endif
                </div>
            </div>
        @endif
    </div>
    @include('livewire.partial.table-pagination')
</div>
