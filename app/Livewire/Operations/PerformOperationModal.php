<?php

namespace App\Livewire\Operations;

use Livewire\Attributes\On;
use App\Models\DispositionUnit;
use App\Livewire\ModalComponent;
use App\Models\TransshipmentCard;
use App\Enums\OperationRelationEnum;
use App\Models\Deposit;
use App\Models\TransshipmentCardUnit;
use App\Services\TransshipmentCardService;
use Exception;
use Illuminate\Support\Facades\Auth;

class PerformOperationModal extends ModalComponent
{
    public $title, $operation, $availableCards = [], $disposition, $relationFrom, $relationTo;
    public $selectedCard, $netWeight, $tareWeight, $tcu_notes, $carriageNumberTo, $truckNumberTo;
    private $transshipmentCardService;

    protected function rules() { 
        return [
            'selectedCard' => 'required',
            'truckNumberTo' => [
                'nullable',
                'string',
                'required_if:relationTo,' . OperationRelationEnum::TRUCK->value,
            ],
            'netWeight' => [
                'nullable',
                'integer', 
                'min:1',
                function ($attribute, $value, $fail) {
                    if (in_array($this->relationTo, [OperationRelationEnum::TRUCK->value, OperationRelationEnum::CARRIAGE->value]) && ($value === null || $value === '')) {
                        $fail(__('The net weight field is required when relation is truck or carriage.'));
                    }
                }
            ],
            'tareWeight' => [
                'nullable',
                'min:1',
                'integer',
                'required_if:relationTo,' . OperationRelationEnum::CARRIAGE->value
            ],
            'carriageNumberTo' => [
                'nullable',
                'string',
                'required_if:relationTo,' . OperationRelationEnum::CARRIAGE->value
            ],
            'truckNumberTo' => [
                'nullable',
                'string',
                'required_if:relationTo,' . OperationRelationEnum::TRUCK->value
            ],
            'tcu_notes' => ['nullable', 'string'],
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

        $cardUnit = new TransshipmentCardUnit();
        $cardUnit->tcu_notes = $this->tcu_notes;
        $cardUnit->tcu_operator_id = Auth::id();
        $cardUnit->tcu_disp_id = $this->disposition->dis_id;
        $cardUnit->tcu_carriage_number_from = $this->operation->disu_carriage_number;

        $cardUnit = $this->determineRelationAndSetAttributes($cardUnit);

        $card = null;

        if ($this->selectedCard == 'newCard') {
            $card = $this->transshipmentCardService->createNewCard($this->operation);
        } else {
            $card = TransshipmentCard::findOrFail((int) $this->selectedCard);
        }

        $cardUnit->tcu_tc_id = $card->tc_id;
        $cardUnit->save();
        // todo do dyspozycji operacji trzeba dodać cardUnitId, gdy wszystkie jednostki z dyspozycji zostaną zrealizowane należy zmienić status i ustawić datę zakończenia
        // (dis_completion_date). dodatkowo z każdej jednostki trzeba wygenerować jednostki transshipment card albo cały nagłówek gdy użytkownik wybrał w selectcie "nowa karta".
        // gdy jest to przeładunek na plac należy wybrać miejsce składowania do którego ma być przypisane(dostępne na placu wybranym w dyspozycji)
        // i dodać do depozytów z odpowiednim id karty przeładunkowej i dyspozycji.
        // pamiętaj o depozycie - dep_departure_date
        $this->dispatch('refreshOperationsCounter');
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

        $cardUnit->tcu_yard_position = $cell->sc_yard_name_short . '/' . $cell->sc_cell. '/' . $cell->sc_row . '/' . $cell->sc_height;

        return $cardUnit;
    }


    private function performOperationToTruck(TransshipmentCardUnit $cardUnit) : TransshipmentCardUnit
    {
        if ($this->relationToYard()) {
            $cardUnit->tcu_net_weight = $this->netWeight;

        } else {
            $cardUnit->tcu_net_weight = $this->netWeight;
            $cardUnit->tcu_tare_weight = $this->tareWeight;
        }
        $cardUnit->tcu_carriage_number_to = $this->carriageNumberTo;
        $cardUnit->tcu_truck_number_to = $this->truckNumberTo;

        return $cardUnit;
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
