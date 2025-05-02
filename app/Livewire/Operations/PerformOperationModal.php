<?php

namespace App\Livewire\Operations;

use Exception;
use App\Models\Deposit;
use Livewire\Attributes\On;
use App\Models\DispositionUnit;
use App\Livewire\ModalComponent;
use App\Models\TransshipmentCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Enums\DispositionStatusEnum;
use App\Enums\OperationRelationEnum;
use App\Services\DispositionService;
use App\Services\StorageYardService;
use Illuminate\Support\Facades\Auth;
use App\Models\TransshipmentCardUnit;
use App\Services\TransshipmentCardService;
use App\DataTypes\StorageCellsAvailabilityDataType;

class PerformOperationModal extends ModalComponent
{
    public $title, $operation, $availableCards = [], $disposition, $relationFrom, $relationTo, $deposit, $hasUnitsAbove = false, $cellsAboveString;
    public $selectedCard, $netWeight, $tareWeight, $notes, $carriageNumberTo, $truckNumberTo, $availableStorageCells, $selectedRow, $selectedColumn, $selectedHeight, $checkIfColumnIsValid;
    private $transshipmentCardService, $selectedStorageCell, $dispositionService, $storageYardService;

    protected function rules()
    {
        return [
            'selectedCard' => 'required',
            'truckNumberTo' => [
                'nullable',
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::TRUCK->value,
                'string',
            ],
            'netWeight' => [
                function ($attribute, $value, $fail) {
                    if (in_array($this->relationTo, [OperationRelationEnum::TRUCK, OperationRelationEnum::CARRIAGE])) {
                        if ($value === null || $value === '') {
                            $fail(__('Field is required when relation is truck or carriage.'));
                        } else if (!is_numeric($value)) {
                            $fail(__('This field must be a number.'));
                        } else if ((int)$value < 1) {
                            $fail(__('This field must have a value of at least 1.'));
                        }
                    }
                }
            ],
            'tareWeight' => [
                'nullable',
                'min:1',
                'integer',
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::CARRIAGE->value
            ],
            'carriageNumberTo' => [
                'nullable',
                'string',
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::CARRIAGE->value
            ],
            'selectedColumn' => [
                'nullable',
                'integer',
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::YARD->value
            ],
            'selectedRow' => [
                'nullable',
                'string',
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::YARD->value
            ],
            'selectedHeight' => [
                'nullable',
                'integer',
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::YARD->value
            ],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function boot()
    {
        $this->transshipmentCardService = new TransshipmentCardService();
        $this->storageYardService = new StorageYardService();
        $this->dispositionService = new DispositionService();
    }

    #[On('performOperationModal')]
    public function performOperationModal(DispositionUnit $operation): void
    {
        $this->operation = $operation->load(['disposition.storageYard']);
        $this->disposition = $this->operation->disposition;
        $this->relationFrom = $this->disposition->dis_relation_from;
        $this->relationTo = $this->disposition->dis_relation_to;

        $this->availableCards = $this->transshipmentCardService->getAvailableCards($this->operation);
        $this->title = __('Execute operation - ') . $operation->disu_container_number;

        if ($this->relationTo == OperationRelationEnum::YARD) {
            $this->availableStorageCells = $this->storageYardService->getAvailableStorageCells($this->disposition->dis_yard_id);
        }

        if ($this->relationFrom == OperationRelationEnum::YARD) {
            $this->operation->loadMissing('departureDeposit.storageCell');

            if (!$this->operation->departureDeposit?->storageCell) {
                return;
            }

            $storageCellsAboveObject = $this->storageYardService->checkIfStorageCellsAboveAreAvailable($this->operation->departureDeposit->storageCell);
            $this->cellsAboveString = $storageCellsAboveObject->occupiedCellsText;

            if (!$storageCellsAboveObject->areAvailable) {
                $this->hasUnitsAbove = true;
            }
        }
    }

    public function selectRow(string $row)
    {
        $this->reset('selectedHeight');
        $this->selectedRow = $row;
    }

    public function selectHeight(string $height)
    {
        $this->selectedHeight = $height;
    }

    public function updatedCheckIfColumnIsValid($column): void
    {
        if (!is_numeric($column)) {
            $this->reset(['selectedColumn', 'selectedRow', 'selectedHeight']);
            return;
        }

        $this->reset(['selectedRow', 'selectedHeight']);

        if (array_key_exists($column, $this->availableStorageCells)) {
            $this->selectedColumn = $column;
        }
    }

    public function getRowClass($row)
    {
        if ($this->selectedRow == $row) {
            return "btn-success";
        } else {
            return "btn-secondary";
        }
    }

    public function getHeightClass($height)
    {
        if ($this->selectedHeight == $height) {
            return "btn-success";
        } else {
            return "btn-secondary";
        }
    }

    public function relationToYard(): bool
    {
        return $this->relationTo == OperationRelationEnum::YARD;
    }

    public function relationToCarriage(): bool
    {
        return $this->relationTo == OperationRelationEnum::CARRIAGE;
    }

    public function relationToTruck(): bool
    {
        return $this->relationTo == OperationRelationEnum::TRUCK;
    }

    public function checkIfContainerCanBePlacedOnThisLevel(int $level): bool
    {
        $availableLevelsToPlaceContainer = $this->availableStorageCells[$this->selectedColumn][$this->selectedRow];

        $availableHeights = collect($availableLevelsToPlaceContainer)
            ->filter(function ($items, $height) {
                return isset($items[0]['cell_available']) && $items[0]['cell_available'] === 1;
            })
            ->keys()
            ->toArray();

        if (in_array($level, $availableHeights)) {
            return true;
        }

        return false;
    }

    public function getPhraseToDisplayInsideHeightButton(int $level): string | int
    {
        $cell = $this->availableStorageCells[$this->selectedColumn][$this->selectedRow][$level][0];
        return array_key_exists('deposit', $cell) && $cell['deposit'] && array_key_exists('dep_number', $cell['deposit'])
            ? $cell['deposit']['dep_number'] : $level;
    }

    public function performOperation(): void
    {
        if ($this->hasUnitsAbove) {
            $this->sweetAlert('error', __("Cannot execute operation, system found existing deposits placed above current storage cell: ") . $this->cellsAboveString);
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            $cardUnit = $this->createNewCardUnit();

            $card = null;

            if ($this->selectedCard == 'newCard') {
                $card = $this->transshipmentCardService->createNewCard($this->operation);
            } else {
                $card = TransshipmentCard::findOrFail((int) $this->selectedCard);
            }

            $cardUnit->tcu_tc_id = $card->tc_id;
            $cardUnit->save();

            $this->operation->disu_cardunit_id = $cardUnit->tcu_id;
            $this->operation->save();

            $this->manageDeposit($card, $cardUnit);
            $this->markDispositionAsCompletedIfConditionsAreMet();

            DB::commit();

            $this->dispatch('refreshOperationsCounter');
            $this->dispatch('refreshOperations');
            $this->closeModal();
            $this->sweetAlert('success', __('Operation executed successfully!'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error performing operation: ' . $e->getMessage(), [
                'exception' => $e,
                'operation_id' => $this->operation->disu_id ?? null,
                'container_number' => $this->operation->disu_container_number ?? null
            ]);

            $this->sweetAlert('error', __('An error occurred: ') . $e->getMessage());
        }
    }

    private function manageDeposit(TransshipmentCard $card, TransshipmentCardUnit $cardUnit): void
    {
        if (!$this->relationToYard()) {
            $this->deposit->dep_departure_date = now();
            $this->deposit->dep_departure_disu_id = $this->operation->disu_id;
            $this->deposit->dep_departure_card_id = $card->tc_id;
            $this->deposit->dep_departure_cardunit_id = $cardUnit->tcu_id;
            $this->deposit->save();
        } else {
            $this->deposit = new Deposit();
            $this->deposit->dep_sc_id = $this->selectedStorageCell->sc_id;
            $this->deposit->dep_arrival_date = now();
            $this->deposit->dep_number = $this->operation->disu_container_number;
            $this->deposit->dep_arrival_disu_id = $this->operation->disu_id;
            $this->deposit->dep_arrival_card_id = $card->tc_id;
            $this->deposit->dep_arrival_cardunit_id = $cardUnit->tcu_id;
            $this->deposit->dep_gross_weight = $cardUnit->tcu_gross_weight;
            $this->deposit->dep_net_weight = $cardUnit->tcu_net_weight;
            $this->deposit->dep_tare_weight = $cardUnit->tcu_tare_weight;
            $this->deposit->save();
        }
    }

    private function createNewCardUnit(): TransshipmentCardUnit
    {
        $cardUnit = new TransshipmentCardUnit();
        $cardUnit->tcu_notes = $this->notes;
        $cardUnit->tcu_operator_id = Auth::id();
        $cardUnit->tcu_disp_id = $this->operation->disu_id;
        $cardUnit->tcu_container_number = $this->operation->disu_container_number;

        $cardUnit = $this->determineRelationAndSetAttributes($cardUnit);

        return $cardUnit;
    }

    private function determineRelationAndSetAttributes(TransshipmentCardUnit $cardUnit): TransshipmentCardUnit
    {
        if ($this->relationToYard()) {
            $this->searchAndSetStorageCell();
            if (!$this->checkIfContainerCanBePlacedOnThisLevel($this->selectedHeight)) {
                throw new Exception(__('Container cannot be placed on this level right now'));
            }
            $cardUnit = $this->performOperationToYard($cardUnit);
        } else if ($this->relationToCarriage()) {
            $cardUnit = $this->performOperationToCarriage($cardUnit);
        } else if ($this->relationToTruck()) {
            $cardUnit = $this->performOperationToTruck($cardUnit);
        }

        return $cardUnit;
    }

    private function performOperationToTransport(TransshipmentCardUnit $cardUnit, OperationRelationEnum $transportType): TransshipmentCardUnit
    {
        $cardUnit->tcu_net_weight = $this->netWeight;

        if ($transportType == OperationRelationEnum::CARRIAGE) {
            $cardUnit->tcu_tare_weight = $this->tareWeight;
            $cardUnit->tcu_carriage_number_to = $this->carriageNumberTo;
            $cardUnit = $this->transshipmentCardService->calcGrossWeight($cardUnit);
        } else if ($transportType == OperationRelationEnum::TRUCK) {
            $cardUnit->tcu_truck_number_to = $this->truckNumberTo;
        }

        $arrivalDeposit = Deposit::with(['arrivalDispositionUnit', 'storageCell'])
            ->available()
            ->where('dep_number', $this->operation->disu_container_number)
            ->first();

        if (!$arrivalDeposit) {
            throw new Exception(__('Could not find suitable deposit for container ') . $this->operation->disu_container_number);
        }

        $this->deposit = $arrivalDeposit;

        $arrivalDispositionUnit = $arrivalDeposit->arrivalDispositionUnit;

        if (!$arrivalDispositionUnit) {
            throw new Exception(__('Could not find suitable disposition unit for container ') . $this->operation->disu_container_number);
        }

        $cardUnit->tcu_carriage_number_from = $arrivalDispositionUnit->disu_carriage_number;
        $cardUnit->tcu_truck_number_from = $arrivalDispositionUnit->disu_car_number;

        $cell = $arrivalDeposit->storageCell;

        if (!$cell) {
            throw new Exception(__('Deposit does not have assigned storage cell'));
        }

        $cardUnit = $this->transshipmentCardService->createYardPosistion($cardUnit, $cell);

        return $cardUnit;
    }

    private function performOperationToCarriage(TransshipmentCardUnit $cardUnit): TransshipmentCardUnit
    {
        return $this->performOperationToTransport($cardUnit, OperationRelationEnum::CARRIAGE);
    }

    private function performOperationToTruck(TransshipmentCardUnit $cardUnit): TransshipmentCardUnit
    {
        return $this->performOperationToTransport($cardUnit, OperationRelationEnum::TRUCK);
    }

    private function performOperationToYard(TransshipmentCardUnit $cardUnit): TransshipmentCardUnit
    {
        $cardUnit->tcu_net_weight = $this->operation->disu_container_net_weight;
        $cardUnit->tcu_gross_weight = $this->operation->disu_container_gross_weight;
        $cardUnit->tcu_tare_weight = $this->operation->disu_container_tare_weight;

        $cardUnit->tcu_carriage_number_from = $this->operation->disu_carriage_number;
        $cardUnit->tcu_truck_number_from = $this->operation->disu_car_number;

        $cardUnit = $this->transshipmentCardService->createYardPosistion($cardUnit, $this->selectedStorageCell);

        return $cardUnit;
    }

    private function searchAndSetStorageCell()
    {
        $this->selectedStorageCell = $this->storageYardService->searchForStorageCell($this->disposition->dis_yard_id, $this->selectedColumn, $this->selectedRow, $this->selectedHeight);
        if (!$this->selectedStorageCell) {
            throw new Exception(__("Storage cell not found"), 404);
        }
    }

    private function markDispositionAsCompletedIfConditionsAreMet(): void
    {
        if ($this->dispositionService->checkIfDispositionHasAnyUnits($this->disposition)) {
            $this->disposition->dis_completion_date = now();
            $this->disposition->dis_status = DispositionStatusEnum::FINALIZED;
            $this->disposition->save();
        }
    }

    public function render()
    {
        return view('livewire.operations.perform-operation-modal');
    }
}
