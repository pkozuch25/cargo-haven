<div style="position: relative">
    @include('livewire.partial.loader')
    <div class="table-responsive">
        <table style="width: 100%">
            <x-thead-table>
                <x-table-row>
                    <x-th-table width="10%">
                        {{ __('Container number') }}
                    </x-th-table>
                    @if($this->checkIfIsInTruckRelation())
                        <x-th-table>
                            {{ __('Car number plate') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Driver') }}
                        </x-th-table>
                    @endif
                    <x-th-table>
                        {{ __('Net weight') }}
                    </x-th-table>
                    @if($this->checkIfIsInCarriageRelation())
                        <x-th-table>
                            {{ __('Tare weight') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Gross weight') }}
                        </x-th-table>
                        <x-th-table>
                            {{ __('Carriage number') }}
                        </x-th-table>
                    @endif
                    <x-th-table>
                        {{ __('Notes') }}
                    </x-th-table>
                    <x-th-table>
                        {{ __('Transshipment card') }}
                    </x-th-table>
                    <x-th-table>
                        {{ __('Status') }}
                    </x-th-table>
                    <x-th-table>
                        {{ __('Actions') }}
                    </x-th-table>
                </x-table-row>
            </x-thead-table>
            <tbody>
                @forelse($data as $dispositionUnit)
                    <x-tr-hover>
                        <x-td-table class="text-center">{{ $dispositionUnit->disu_container_number }}</x-td-table>
                        @if($this->checkIfIsInTruckRelation())
                            <x-td-table class="text-center">{{ $dispositionUnit->disu_car_number }}</x-td-table>
                            <x-td-table class="text-center">{{ $dispositionUnit->disu_driver }}</x-td-table>
                        @endif
                        <x-td-table class="text-center">{{ $dispositionUnit->disu_container_net_weight }}</x-td-table>
                        @if($this->checkIfIsInCarriageRelation())
                            <x-td-table class="text-center">{{ $dispositionUnit->disu_container_tare_weight }}</x-td-table>
                            <x-td-table class="text-center">{{ $dispositionUnit->disu_container_gross_weight }}</x-td-table>
                            <x-td-table class="text-center">{{ $dispositionUnit->disu_carriage_number }}</x-td-table>
                        @endif
                        <x-td-table class="text-center">{{ $dispositionUnit->disu_notes }}</x-td-table>
                        <x-td-table class="text-center">placeholder na link do karty</x-td-table>
                        <x-td-table class="text-center">
                            @if($dispositionUnit->disu_cardunit_id)
                                <x-pill class="bg-success">{{ __("Completed") }}</x-pill> 
                            @else
                                <x-pill class="bg-secondary">{{ __("Processing") }}</x-pill>
                            @endif
                        </x-td-table>
                        <x-td-table class="float-right">
                            @can('edit_dispositions')
                                @if($this->checkIfUnitCanBeDeleted($dispositionUnit))
                                    <x-button icon="ti ti-trash" class="btn-danger px-[6px] py-[3px]" wire:click="deleteDispositionUnitConfirm({{ $dispositionUnit->disu_id }})"/>
                                @endif
                            @endcan
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
