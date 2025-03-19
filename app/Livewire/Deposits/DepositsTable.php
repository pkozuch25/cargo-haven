<?php

namespace App\Livewire\Deposits;

use App\Models\Deposit;
use App\Models\Disposition;
use Illuminate\Validation\Rule;
use App\Livewire\TableComponent;
use App\Enums\OperationRelationEnum;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\TableComponentInterface;
use App\Models\DispositionUnit;
use App\Services\DispositionService;

class DepositsTable extends TableComponent implements TableComponentInterface
{
    public $sortColumn = 'dep_arrival_date', $displayArchivedCheckbox = false, $dispositionCreationArray = [], $relationToRelations, $relationTo, $driver, $carriageNumber, $carNumber;

    protected function rules() {
        return [
            'relationTo' => ['required', Rule::enum(OperationRelationEnum::class)],
            'carNumber' => [
                'nullable',
                'string',
                'required_if:relationTo,' . OperationRelationEnum::TRUCK->value,
            ],
            'driver' => [
                'nullable',
                'string',
                'required_if:relationTo,' . OperationRelationEnum::TRUCK->value,
            ],
            'carriageNumber' => [
                'nullable',
                'string',
                'required_if:relationTo,' . OperationRelationEnum::CARRIAGE->value
            ]
        ];
    }

    public function mount()
    {
        $this->relationToRelations = OperationRelationEnum::casesExcept(OperationRelationEnum::YARD);
    }

    public function queryRefresh()
    {
        $query = Deposit::query()
            ->with('arrivalDispositionUnit.disposition', 'departureDispositionUnit.disposition', 'storageCell');
            if ($this->displayArchivedCheckbox) {
                $query->archived();
            } else {
                $query->available();
            }
        return $this->tableRefresh($query);
    }

    public function addDepositToDispositionCreationArray($depositId) : void
    {
        if (in_array($depositId, $this->dispositionCreationArray)) {
            return;
        }
        $this->dispositionCreationArray[] = $depositId;
    }

    public function removeDepositFromDispositionCreationArray($depositId) : void
    {
        $this->dispositionCreationArray = array_diff($this->dispositionCreationArray, [$depositId]);
    }

    public function generateDispositionFromSelectedDeposits()
    {
        $this->validate();

        if ($this->relationTo == OperationRelationEnum::YARD) {
            $this->sweetAlert('error', __('You cannot create disposition from yard to yard'));
            return;
        }

        $disposition = new Disposition();

        $disposition->dis_relation_to = $this->relationTo;
        $disposition->dis_relation_from = OperationRelationEnum::YARD;
        $disposition->dis_created_by_id = Auth::id();
        $disposition->dis_yard_id = Deposit::find($this->dispositionCreationArray[0])->storageCell->sc_yard_id;
        $disposition->dis_suggested_date = now();

        (new DispositionService())->createDispositionNumber($disposition);

        $disposition->save();
        $disposition->refresh();

        $generateDispositionUnitsFromSelectedDeposits = Deposit::whereIn('dep_id', $this->dispositionCreationArray)->get();

        foreach ($generateDispositionUnitsFromSelectedDeposits as $generateDispositionUnitFromSelectedDeposit) {
            $dispositionUnit = DispositionUnit::create([
                'disu_dis_id' => $disposition->dis_id,
                'disu_driver' => $this->driver,
                'disu_carriage_number' => $this->carriageNumber,
                'disu_car_number' => $this->carNumber,
                'disu_container_number' => $generateDispositionUnitFromSelectedDeposit->dep_number,
                'disu_container_gross_weight' => $generateDispositionUnitFromSelectedDeposit->dep_gross_weight,
                'disu_container_net_weight' => $generateDispositionUnitFromSelectedDeposit->dep_net_weight,
                'disu_container_tare_weight' => $generateDispositionUnitFromSelectedDeposit->dep_tare_weight,
            ]);

            $dispositionUnit->refresh();
            $generateDispositionUnitFromSelectedDeposit->dep_departure_disu_id = $dispositionUnit->disu_id;
            $generateDispositionUnitFromSelectedDeposit->save();

        }

        return redirect()->route('dispositions.index', ['disp' => $disposition->dis_id]);
    }

    public function checkIfDepositIsFromTheSameYard($depositId) : bool
    {
        $deposit = Deposit::find($depositId);

        if (!$deposit) {
            return false;
        }

        if ($this->dispositionCreationArray == []) {
            return true;
        }

        if ($deposit->storageCell->sc_yard_id == Deposit::find($this->dispositionCreationArray[0])->storageCell->sc_yard_id) {
            return true;
        }

        return false;
    }

    public function checkIfIsInCarriageRelation() : bool
    {
        if ($this->relationTo == OperationRelationEnum::CARRIAGE->value) {
            return true;
        }

        return false;
    }

    public function checkIfIsInTruckRelation() : bool
    {
        if ($this->relationTo == OperationRelationEnum::TRUCK->value) {
            return true;
        }

        return false;
    }

    public function render()
    {
        return view('livewire.deposits.deposits-table', ['data' => $this->queryRefresh()]);
    }
}
