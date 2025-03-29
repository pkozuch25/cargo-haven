<x-modal id="show-transshipment-card-details-modal" size="fullscreen" title="{{ __('Transshipment Card') }} #{{ $transshipmentCard?->tc_number }}" pillText="{{ $transshipmentCard?->tc_status?->name() }}" pillClass="{{ $transshipmentCard?->tc_status?->color() }}" icon="ti ti-file-text">
    <x-slot name="modalBody">
        @if ($transshipmentCard)
            <div class="row">
                <div class="col-lg-2">
                    <x-input-label>{{ __('Yard') }}</x-input-label>
                    <div class="p-2 border rounded bg-light">
                        {{ $transshipmentCard->storageYard?->sy_name ?? '-' }}
                    </div>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Relation from') }}</x-input-label>
                    <div class="p-2 border rounded bg-light">
                        {{ $transshipmentCard->tc_relation_from?->name() ?? '-' }}
                    </div>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Relation to') }}</x-input-label>
                    <div class="p-2 border rounded bg-light">
                        {{ $transshipmentCard->tc_relation_to?->name() ?? '-' }}
                    </div>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Created at') }}</x-input-label>
                    <div class="p-2 border rounded bg-light">
                        {{ $transshipmentCard->created_at?->format('Y-m-d H:i') ?? '-' }}
                    </div>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Created by') }}</x-input-label>
                    <div class="p-2 border rounded bg-light">
                        {{ $transshipmentCard->createdBy?->name ?? '-' }}
                    </div>
                </div>
                <div class="col-lg-2">
                    <x-input-label>{{ __('Status') }}</x-input-label>
                    <div class="p-2 border rounded bg-light">
                        <span class="badge {{ $transshipmentCard->tc_status?->color() }}">
                            {{ $transshipmentCard->tc_status?->name() ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <x-input-label>{{ __('Notes') }}</x-input-label>
                    <div class="p-2 border rounded bg-light" style="min-height: 80px;">
                        {{ $transshipmentCard->tc_notes ?? '-' }}
                    </div>
                </div>
            </div>

            @can('edit_transshipment_cards')
                <div class="row mt-3">
                    <div class="col-sm-12">
                        @if($transshipmentCard->tc_status == \App\Enums\TransshipmentCardStatusenum::PROCESSING)
                            <x-button class="btn-success" wire:click='completeCard'>
                                {{ __('Complete Card') }}
                            </x-button>
                        @elseif($transshipmentCard->tc_status == \App\Enums\TransshipmentCardStatusenum::COMPLETED)
                            <x-button class="btn-warning" wire:click='reopenCard'>
                                {{ __('Reopen Card') }}
                            </x-button>
                        @endif
                    </div>
                </div>
            @endcan

            <div class="row mt-4">
                <div class="col-lg-12">
                    <h4 class="mb-3">{{ __('Transshipment Units') }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Container Number') }}</th>
                                    <th>{{ __('Operator') }}</th>
                                    <th>{{ __('Disposition') }}</th>
                                    <th>{{ __('Yard Position') }}</th>
                                    <th>{{ __('From Carriage') }}</th>
                                    <th>{{ __('To Carriage') }}</th>
                                    <th>{{ __('From Truck') }}</th>
                                    <th>{{ __('To Truck') }}</th>
                                    <th>{{ __('Gross Weight') }}</th>
                                    <th>{{ __('Net Weight') }}</th>
                                    <th>{{ __('Tare Weight') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transshipmentCard->units as $unit)
                                    <tr>
                                        <td>{{ $unit->tcu_container_number ?? '-' }}</td>
                                        <td>{{ $unit->operator?->name ?? '-' }}</td>
                                        <td>{{ $unit->disposition?->dis_number ?? '-' }}</td>
                                        <td>{{ $unit->tcu_yard_position ?? '-' }}</td>
                                        <td>{{ $unit->tcu_carriage_number_from ?? '-' }}</td>
                                        <td>{{ $unit->tcu_carriage_number_to ?? '-' }}</td>
                                        <td>{{ $unit->tcu_truck_number_from ?? '-' }}</td>
                                        <td>{{ $unit->tcu_truck_number_to ?? '-' }}</td>
                                        <td>{{ $unit->tcu_gross_weight ?? '-' }}</td>
                                        <td>{{ $unit->tcu_net_weight ?? '-' }}</td>
                                        <td>{{ $unit->tcu_tare_weight ?? '-' }}</td>
                                        <td>{{ $unit->created_at?->format('Y-m-d H:i') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">{{ __('No units found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </x-slot>
    <x-slot name="modalFooter">
        <x-button-close-modal></x-button-close-modal>
    </x-slot>
</x-modal>
