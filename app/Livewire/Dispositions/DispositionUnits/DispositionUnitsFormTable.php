<?php

namespace App\Livewire\Dispositions\DispositionUnits;

use Validator\Validator;
use App\Models\Disposition;
use Livewire\Attributes\On;
use App\Models\DispositionUnit;
use App\Livewire\TableComponent;
use App\Enums\OperationRelationEnum;
use App\Services\DispositionService;
use App\Interfaces\TableComponentInterface;

class DispositionUnitsFormTable extends TableComponent implements TableComponentInterface
{
    public $disposition, $dispositionUnit;

    protected function rules()
    {
        return [
            'dispositionUnit.disu_container_number' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!(new Validator())->isValid($value)) {
                        $fail(__('The container number format is invalid.'));
                    }
                }
            ],
            'dispositionUnit.disu_notes' => ['nullable', 'string'],
            'dispositionUnit.disu_car_number' => [
                'nullable',
                'string',
                'required_if:disposition.dis_relation_from,' . OperationRelationEnum::TRUCK->value,
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::TRUCK->value,
            ],
            'dispositionUnit.disu_driver' => [
                'nullable',
                'string',
                'required_if:disposition.dis_relation_from,' . OperationRelationEnum::TRUCK->value,
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::TRUCK->value,
            ],
            'dispositionUnit.disu_container_net_weight' => ['required', 'integer', 'min:1'],
            'dispositionUnit.disu_container_tare_weight' => [
                'nullable',
                'min:1',
                'integer',
                'required_if:disposition.dis_relation_from,' . OperationRelationEnum::CARRIAGE->value,
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::CARRIAGE->value
            ],
            'dispositionUnit.disu_carriage_number' => [
                'nullable',
                'string',
                'required_if:disposition.dis_relation_from,' . OperationRelationEnum::CARRIAGE->value,
                'required_if:disposition.dis_relation_to,' . OperationRelationEnum::CARRIAGE->value
            ]
        ];
    }

    public function mount(Disposition $disposition)
    {
        $this->disposition = $disposition;
        $this->dispositionUnit = new DispositionUnit();
    }

    public function queryRefresh()
    {
        return $this->tableRefresh($this->disposition->units()->with('disposition'));
    }

    public function addDispositionUnit(): void
    {
        $this->validate();

        if ($this->checkIfIsInCarriageRelation()) {
            $this->dispositionUnit = (new DispositionService)->calcGrossWeight($this->dispositionUnit);
        }
        // $this->checkIfExistsInDeposits() - sprawdź czy już istnieje w depozytach - todo

        if ($this->checkIfExistsInOtherDisposition()) {
            return;
        }

        try {
            $this->disposition->units()->save($this->dispositionUnit);
        } catch (\Exception $e) {
            $this->dispatch('error', ['message' => __('An error occurred while saving the data')]);
            return;
        }

        $this->queryRefresh();

        $this->dispositionUnit = new DispositionUnit();
    }

    public function deleteDispositionUnitConfirm(DispositionUnit $dispositionUnit): void
    {
        $this->sweetAlertConfirm(
            'warning',
            __('Are you sure you want to delete this container?'),
            __('This action cannot be undone'),
            'deleteDispositionUnit',
            $dispositionUnit->disu_id
        );
    }

    #[On('deleteDispositionUnit')]
    public function deleteDispositionUnit(DispositionUnit $dispositionUnit): void
    {
        if ($dispositionUnit->disu_cardunit_id) {
            $this->sweetAlert('error', __('The container cannot be deleted because it was already processed'));
            return;
        }

        if ($dispositionUnit->delete()) {
            $this->sweetAlert('success', __('The container has been successfully deleted'));
            $this->queryRefresh();
            return;
        }

        $this->sweetAlert('error', __('An error occurred while deleting the container'));
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

    public function dispositionHasUnits(): bool
    {
        return (new DispositionService())->checkIfDispositionHasAnyUnits($this->disposition);
    }

    private function checkIfExistsInOtherDisposition(): bool
    {
        $dispositionWithContainerNumber = (new DispositionService())->checkIfUnitExistsInDisposition($this->dispositionUnit->disu_container_number);

        if (!$dispositionWithContainerNumber) {
            return false;
        }

        if ($dispositionWithContainerNumber->dis_number == $this->disposition->dis_number) {
            $this->sweetAlert('error', __("The container already exists in the current disposition"));
            return true;
        }

        if ($dispositionWithContainerNumber) {
            $this->sweetAlert('error', __("The container already exists in disposition {$dispositionWithContainerNumber->dis_number}"));
            return true;
        }

        return false;
    }

    public function render()
    {
        return view('livewire.dispositions.disposition-units.disposition-units-form-table', ['data' => $this->queryRefresh()]);
    }
}
