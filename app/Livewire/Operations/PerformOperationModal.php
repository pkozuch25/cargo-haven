<?php

namespace App\Livewire\Operations;

use App\Enums\DispositionStatusEnum;
use Exception;
use App\Models\Deposit;
use Livewire\Attributes\On;
use App\Models\DispositionUnit;
use App\Livewire\ModalComponent;
use App\Models\TransshipmentCard;
use App\Enums\OperationRelationEnum;
use App\Services\DispositionService;
use Illuminate\Support\Facades\Auth;
use App\Models\TransshipmentCardUnit;
use App\Services\TransshipmentCardService;

class PerformOperationModal extends ModalComponent
{
    public $title, $operation, $availableCards = [], $disposition, $relationFrom, $relationTo, $deposit;
    public $selectedCard, $netWeight, $tareWeight, $notes, $carriageNumberTo, $truckNumberTo;
    private $transshipmentCardService;

    protected function rules() { 
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
            'notes' => ['nullable', 'string'],
        ];
    }

    public function boot()
    {
        $this->transshipmentCardService = new TransshipmentCardService();
    }

    #[On('performOperationModal')]
    public function performOperationModal(DispositionUnit $operation) : void
    {
        $this->operation = $operation->load(['disposition']);
        $this->disposition = $this->operation->disposition;
        $this->relationFrom = $this->disposition->dis_relation_from;
        $this->relationTo = $this->disposition->dis_relation_to;

        $this->availableCards = $this->transshipmentCardService->getAvailableCards($this->operation);
        $this->title = __('Execute operation - ') . $operation->disu_container_number;
    }

    public function performOperation() : void
    {
        $this->validate();

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
        $this->checkIfDispositionShouldBeMarkedAsCompleted();

        // todo do dyspozycji operacji trzeba dodać cardUnitId, gdy wszystkie jednostki z dyspozycji zostaną zrealizowane należy zmienić status i ustawić datę zakończenia
        // (dis_completion_date). dodatkowo z każdej jednostki trzeba wygenerować jednostki transshipment card albo cały nagłówek gdy użytkownik wybrał w selectcie "nowa karta".
        // gdy jest to przeładunek na plac należy wybrać miejsce składowania do którego ma być przypisane(dostępne na placu wybranym w dyspozycji)
        // i dodać do depozytów z odpowiednim id karty przeładunkowej i dyspozycji.
        // pamiętaj o depozycie - dep_departure_date
        $this->dispatch('refreshOperationsCounter');
        $this->dispatch('refreshOperations');
        $this->closeModal();
        $this->sweetAlert('success', __('Operation executed successfully!'));
    }

    private function manageDeposit(TransshipmentCard $card, TransshipmentCardUnit $cardUnit) : void
    {
        if (!$this->relationToYard()) {
            $this->deposit->dep_departure_date = now();
            $this->deposit->dep_departure_disu_id = $this->operation->disu_id;
            $this->deposit->dep_departure_card_id = $card->tc_id;
            $this->deposit->dep_departure_cardunit_id = $cardUnit->tcu_id;
            $this->deposit->save();
        } else {
            $this->deposit->dep_arrival_date = now();
            $this->deposit->dep_arrival_disu_id = $this->operation->disu_id;
            $this->deposit->dep_arrival_card_id = $card->tc_id;
            $this->deposit->dep_arrival_cardunit_id = $cardUnit->tcu_id;
            $this->deposit->save();
        }
    }

    private function createNewCardUnit() : TransshipmentCardUnit
    {
        $cardUnit = new TransshipmentCardUnit();
        $cardUnit->tcu_notes = $this->notes;
        $cardUnit->tcu_operator_id = Auth::id();
        $cardUnit->tcu_disp_id = $this->operation->disu_id;

        $cardUnit = $this->determineRelationAndSetAttributes($cardUnit);

        return $cardUnit;
    }

    private function determineRelationAndSetAttributes(TransshipmentCardUnit $cardUnit) : TransshipmentCardUnit
    {
        if ($this->relationToYard()) {
            $cardUnit = $this->performOperationToYard($cardUnit);
        } else if ($this->relationToCarriage()) {
            $cardUnit = $this->performOperationToCarriage($cardUnit);
        } else if ($this->relationToTruck()) {
            $cardUnit = $this->performOperationToTruck($cardUnit);
        }

        return $cardUnit;
    }
    
    private function performOperationToCarriage(TransshipmentCardUnit $cardUnit) : TransshipmentCardUnit
    {
        $cardUnit->tcu_net_weight = $this->netWeight;
        $cardUnit->tcu_tare_weight = $this->tareWeight;
        $cardUnit->tcu_carriage_number_to = $this->carriageNumberTo;
        
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

        $cardUnit = $this->transshipmentCardService->calcGrossWeight($cardUnit);

        $cell = $arrivalDeposit->storageCell;

        if (!$cell) {
            throw new Exception(__('Deposit does not have aassigned storage cell'));
        }

        $cardUnit = $this->transshipmentCardService->createYardPosistion($cardUnit, $cell);

        return $cardUnit;
    }


    private function performOperationToTruck(TransshipmentCardUnit $cardUnit) : TransshipmentCardUnit
    {
        $cardUnit->tcu_net_weight = $this->netWeight;
        $cardUnit->tcu_truck_number_to = $this->truckNumberTo;
        
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
            throw new Exception(__('Deposit does not have aassigned storage cell'));
        }

        $cardUnit = $this->transshipmentCardService->createYardPosistion($cardUnit, $cell);

        return $cardUnit;
    }

    private function checkIfDispositionShouldBeMarkedAsCompleted() : void
    {
        if ((new DispositionService())->checkIfDispositionHasAnyUnits($this->disposition)) {
            $this->disposition->dis_completion_date = now();
            $this->disposition->dis_status = DispositionStatusEnum::FINALIZED;
            $this->disposition->save();
        }
    }

    public function relationToYard() : bool
    {
        return $this->relationTo == OperationRelationEnum::YARD;
    }

    public function relationToCarriage() : bool
    {
        return $this->relationTo == OperationRelationEnum::CARRIAGE;
    }

    public function relationToTruck() : bool
    {
        return $this->relationTo == OperationRelationEnum::TRUCK;
    }

    public function render()
    {
        return view('livewire.operations.perform-operation-modal');
    }
}
