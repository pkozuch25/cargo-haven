<?php

namespace App\Livewire\Dispositions\DispositionUnits;

use Validator\Validator;
use App\Models\Disposition;
use App\Models\DispositionUnit;
use App\Livewire\TableComponent;
use App\Enums\OperationRelationEnum;
use App\Interfaces\TableComponentInterface;

class DispositionUnitsFormTable extends TableComponent implements TableComponentInterface
{
    public $disposition, $dispositionUnit;
 
    protected function rules() {
        return [
            'dispositionUnit.disu_container_number' => [
                'required',
                function($attribute, $value, $fail) {
                    if (!(new Validator())->isValid($value)) {
                        $fail(__('The container number format is invalid.'));
                    }
                }
            ],
            'dispositionUnit.disu_notes' => ['nullable', 'string'],
            'dispositionUnit.disu_car_number' => [
                'nullable',
                'string',
                'required_if:disposition.dis_relation_from,'.OperationRelationEnum::TRUCK->value,
                'required_if:disposition.dis_relation_to,'.OperationRelationEnum::TRUCK->value,
            ],
        ];
    }

    public function mount(Disposition $disposition)
    {
        $this->disposition = $disposition;
        $this->dispositionUnit = new DispositionUnit();
    }

    public function queryRefresh()
    {
        
    }

    public function addDispositionUnit()
    {
        $this->validate();

        // $this->checkIfExistsInDeposits() - sprawdź czy już istnieje w depozytach - todo

        $this->dispositionUnit = new DispositionUnit();
        
    }

    public function checkIfIsInTruckRelation()
    {
        if ($this->disposition->dis_relation_from == OperationRelationEnum::TRUCK || $this->disposition->dis_relation_to == OperationRelationEnum::TRUCK) {
            return true;
        }
        return false;
    }

    public function checkIfIsInCarriageRelation()
    {
        if ($this->disposition->dis_relation_from == OperationRelationEnum::CARRIAGE || $this->disposition->dis_relation_to == OperationRelationEnum::CARRIAGE) {
            return true;
        }
        return false;
    }

    public function render()
    {
        return view('livewire.dispositions.disposition-units.disposition-units-form-table');
    }
}
