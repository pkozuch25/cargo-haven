<x-modal id="show-transshipment-card-details-modal" size="fullscreen" title="{{ __('Transshipment card') }} {{ $transshipmentCard?->tc_number }}" pillText="{{ $transshipmentCard?->tc_status?->name() }}" pillClass="{{ $transshipmentCard?->tc_status?->color() }}" icon="ti ti-file-text">
    <x-slot name="modalBody">
        @if ($transshipmentCard)
            <div class="row">
                <div class="col-lg-2">
                    <x-input-label>{{ __('Yard') }}</x-input-label>
                    <x-fake-input>
                        {{ $transshipmentCard->storageYard?->sy_name ?? '-' }}
                    </x-fake-input>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Relation from') }}</x-input-label>
                    <x-fake-input>
                        {{ $transshipmentCard->tc_relation_from?->name() ?? '-' }}
                    </x-fake-input>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Relation to') }}</x-input-label>
                    <x-fake-input>
                        {{ $transshipmentCard->tc_relation_to?->name() ?? '-' }}
                    </x-fake-input>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Created at') }}</x-input-label>
                    <x-fake-input>
                        {{ $transshipmentCard->created_at?->format('Y-m-d H:i') ?? '-' }}
                    </x-fake-input>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Created by') }}</x-input-label>
                    <x-fake-input>
                        {{ $transshipmentCard->createdBy?->name ?? '-' }}
                    </x-fake-input>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <x-input-label>{{ __('Notes') }}</x-input-label>
                    <x-fake-input style="min-height: 80px;">
                        {{ $transshipmentCard->tc_notes ?? '-' }}
                    </x-fake-input>
                </div>
            </div>

            @can('edit_transshipment_cards')
                <div class="row mt-3">
                    <div class="col-sm-12">
                        @if($transshipmentCard->tc_status == \App\Enums\TransshipmentCardStatusenum::PROCESSING)
                            <x-button class="btn-success" wire:click='completeCard'>
                                {{ __('Complete card') }}
                            </x-button>
                        @elseif($transshipmentCard->tc_status == \App\Enums\TransshipmentCardStatusenum::COMPLETED)
                            <x-button class="btn-warning" wire:click='reopenCard'>
                                {{ __('Reopen card') }}
                            </x-button>
                        @endif
                    </div>
                </div>
            @endcan

            <div class="row mt-4">
                <div class="col-lg-12">
                    <h4 class="mb-3">{{ __('Transshipment card units') }}</h4>
                    <div class="table-responsive">
                        <table style="width: 100%">
                            <x-thead-table>
                                <x-table-row>
                                    <x-th-table>{{ __('Container number') }}</x-th-table>
                                    <x-th-table>{{ __('Operator') }}</x-th-table>
                                    <x-th-table>{{ __('Disposition') }}</x-th-table>
                                    <x-th-table>{{ __('Yard position') }}</x-th-table>
                                    <x-th-table>{{ __('From carriage') }}</x-th-table>
                                    <x-th-table>{{ __('To carriage') }}</x-th-table>
                                    <x-th-table>{{ __('From truck') }}</x-th-table>
                                    <x-th-table>{{ __('To truck') }}</x-th-table>
                                    <x-th-table>{{ __('Gross weight') }}</x-th-table>
                                    <x-th-table>{{ __('Net weight') }}</x-th-table>
                                    <x-th-table>{{ __('Tare weight') }}</x-th-table>
                                    <x-th-table>{{ __('Created at') }}</x-th-table>
                                </x-table-row>
                            </x-thead-table>
                            <tbody>
                                @forelse($transshipmentCard->units as $unit)
                                    <x-tr-hover>
                                        <x-td-table>{{ $unit->tcu_container_number ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->operator?->name ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->disposition?->dis_number ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_yard_position ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_carriage_number_from ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_carriage_number_to ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_truck_number_from ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_truck_number_to ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_gross_weight ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_net_weight ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->tcu_tare_weight ?? '-' }}</x-td-table>
                                        <x-td-table>{{ $unit->created_at?->format('Y-m-d H:i') ?? '-' }}</x-td-table>
                                    </x-tr-hover>
                                @empty
                                    <x-tr-hover>
                                        <td colspan="12" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">{{ __('No results') }}</td>
                                    </x-tr-hover>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </x-slot>
    <x-slot name="modalFooter">
    </x-slot>
    @push('javascript')
        <script>
            window.addEventListener('openTransshipmentCardModalBlade', function() {
                $('#show-transshipment-card-details-modal').modal('show');
            })
        </script>
    @endpush
</x-modal>
