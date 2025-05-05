<?php

namespace App\Livewire\Dispositions\DispositionUnits;

use Validator\Validator;
use App\Models\Disposition;
use Livewire\Attributes\On;
use App\Models\DispositionUnit;
use App\Livewire\TableComponent;
use App\Enums\DispositionStatusEnum;
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
        if ($this->checkIfDispositionWasCancelledOrFinalized()) {
            $this->sweetAlert('error', __('Cannot add new unit to cancelled disposition'));
            return;
        }

        $this->validate();

        if ($this->checkIfIsInCarriageRelation()) {
            $this->dispositionUnit = (new DispositionService)->calcGrossWeight($this->dispositionUnit);
        }

        if (!$this->checkIfShouldAndIfExistsInDeposits()) {
            return;
        }

        if ($this->checkIfExistsInOtherDisposition()) {
            return;
        }

        try {
            if (!$this->dispositionHasUnits()) {
                $this->disposition->dis_start_date = now();
                $this->disposition->save();
            }
            $this->disposition->units()->save($this->dispositionUnit);
        } catch (\Exception $e) {
            $this->sweetAlert('error', __('Something went wrong'));
            return;
        }


        $this->queryRefresh();
        $this->dispatch('openAddEditDispositionModal', $this->disposition->dis_id);
        $this->dispatch('refreshOperationsCounter');
        $this->dispositionUnit = new DispositionUnit();
    }

    public function deleteDispositionUnitConfirm(DispositionUnit $dispositionUnit) : void
    {
        $this->sweetAlertConfirm(
            'warning',
            __('Are you sure you want to delete this container?'),
            __('This action cannot be undone'),
            'deleteDispositionUnit',
            'dispositionUnit',
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
            $this->dispatch('openAddEditDispositionModal', $this->disposition->dis_id);
            $this->dispatch('refreshOperationsCounter');
            return;
        }

        $this->sweetAlert('error', __('An error occurred while deleting the container'));
    }

    public function checkIfIsInTruckRelation() : bool
    {
        if ($this->disposition->dis_relation_from == OperationRelationEnum::TRUCK || $this->disposition->dis_relation_to == OperationRelationEnum::TRUCK) {
            return true;
        }
        return false;
    }

    public function checkIfIsInCarriageRelation() : bool
    {
        if ($this->disposition->dis_relation_from == OperationRelationEnum::CARRIAGE || $this->disposition->dis_relation_to == OperationRelationEnum::CARRIAGE) {
            return true;
        }
        return false;
    }

    public function dispositionHasUnits() : bool
    {
        return (new DispositionService())->checkIfDispositionHasAnyUnits($this->disposition);
    }

    public function checkIfDispositionWasCancelledOrFinalized() : bool
    {
        if ($this->disposition->dis_status == DispositionStatusEnum::CANCELLED || $this->disposition->dis_status == DispositionStatusEnum::FINALIZED) {
            return true;
        }
        return false;
    }

    public function checkIfUnitCanBeDeleted(DispositionUnit $dispositionUnit) : bool
    {
        if (!$dispositionUnit->disu_cardunit_id && !$this->checkIfDispositionWasCancelledOrFinalized()) {
            return true;
        }

        return false;
    }

    private function checkIfExistsInOtherDisposition() : bool
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

    private function checkIfShouldAndIfExistsInDeposits() : bool
    {
        $dispositionWithContainerNumber = (new DispositionService())->checkIfUnitExistsInDeposits($this->dispositionUnit->disu_container_number);

        if ($this->disposition->dis_relation_from == OperationRelationEnum::YARD && !$dispositionWithContainerNumber) {
            $this->sweetAlert('error', __("This container is not in deposits"));
            return false;
        }

        if ($this->disposition->dis_relation_to == OperationRelationEnum::YARD && $dispositionWithContainerNumber) {
            $this->sweetAlert('error', __("This container already exists in deposits"));
            return false;
        }

        return true;
    }

    public function render()
    {
        return view('livewire.dispositions.disposition-units.disposition-units-form-table', ['data' => $this->queryRefresh()]);
    }
}
