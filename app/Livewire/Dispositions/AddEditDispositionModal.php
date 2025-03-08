<?php

namespace App\Livewire\Dispositions;

use App\Models\Disposition;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use App\Livewire\ModalComponent;
use Illuminate\Support\Facades\DB;
use App\Enums\OperationRelationEnum;
use App\Services\DispositionService;
use Illuminate\Support\Facades\Auth;

class AddEditDispositionModal extends ModalComponent
{
    public $disposition, $edit = false, $title, $relationFromFormAvailableRelations, $relationToFormAvailableRelations, $dispositionOperators;

    protected function rules()
    {
        return [
            'disposition.dis_yard_id' => ['required', 'integer'],
            'disposition.dis_relation_from' => ['required', Rule::enum(OperationRelationEnum::class)],
            'disposition.dis_relation_to' => ['required', Rule::enum(OperationRelationEnum::class)],
            'disposition.dis_notes' => ['nullable', 'string'],
            'disposition.dis_suggested_date' => ['required', 'date'],
            'dispositionOperators' => ['required', 'array'],
        ];
    }

    public function mount()
    {
        $this->loadRelationsFromForm();
    }

    #[On('openAddEditDispositionModal')]
    public function openAddEditDispositionModal(?Disposition $disposition = null)
    {
        if ($disposition->exists) {
            $this->disposition = $disposition;
            $this->edit = true;
            $this->loadRelationsToForm();
        } else {
            $this->disposition = new Disposition();
        }

        $this->disposition->loadMissing('operators');
        $this->dispositionOperators = $this->disposition->operators()->pluck('id')->toArray();

        $this->title = $this->edit ? __('Edit disposition') : __('Add disposition');
        $this->dispatch('iniSelect2', ['operators' => $this->dispositionOperators]);
        $this->dispatch('flatpickr');
        $this->loadRelationsFromForm();
    }

    public function updatedDispositionDisRelationFrom($value)
    {
        if ($value == "") {
            $this->relationToFormAvailableRelations = OperationRelationEnum::cases();
            return;
        }

        $selectedEnum = OperationRelationEnum::from((int) $value);

        if ($selectedEnum != OperationRelationEnum::YARD) {
            $this->disposition->dis_relation_to = OperationRelationEnum::YARD;
            $this->relationToFormAvailableRelations = [OperationRelationEnum::YARD];
            return;
        }

        $this->disposition->dis_relation_to = OperationRelationEnum::CARRIAGE;
        $this->relationToFormAvailableRelations = OperationRelationEnum::casesExcept($selectedEnum);
    }


    public function checkIfOperatorIsAssignedToOtherDispositions(array $data, array $dataBefore)
    {
        $dispositionsString = (new DispositionService())->checkIfOperatorBelongsToDisposition($data, $dataBefore);

        if ($dispositionsString != null) {
            $this->sweetAlert('warning', __('Operator is already assigned to disposition ') . $dispositionsString);
        }

        $this->dispositionOperators = $data;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->checkIfFromAndToRelationsAreTheSame()) {
            $this->sweetAlert('error', __('Relation from and relation to cannot be the same'));
            return;
        }

        if ($this->checkIfHasExactlyOneYardRelation()) {
            $this->sweetAlert('error', __('One of the relations must be a yard relation'));
            return;
        }

        if ($this->dispositionHasUnits() && $this->checkIfNonEditableFieldsAreDirty()) {
            $this->sweetAlert('error', __('Disposition has units, some fields cannot be edited'));
            return;
        }

        try {
            DB::transaction(function () {
                $this->disposition->dis_created_by_id = Auth::id();
                $this->disposition->save();
                $this->disposition->operators()->sync($this->dispositionOperators);
            });

            if (!$this->edit) {
                $this->edit = true;
                $this->sweetAlert('success', __('Disposition added successfully'));
                $this->title = __('Edit disposition');
            } else {
                $this->sweetAlert('success', __('Edited'));
                if (!$this->dispositionHasUnits()) {
                    $this->closeModal(true);
                }
            }

            $this->dispatch('refreshDispositionTable');
        } catch (\Exception $e) {
            $this->sweetAlert('error', __('Something went wrong'));
            return;
        }
    }

    public function dispositionHasUnits(): bool
    {
        return (new DispositionService())->checkIfDispositionHasAnyUnits($this->disposition);
    }

    private function loadRelationsFromForm()
    {
        $this->relationFromFormAvailableRelations = OperationRelationEnum::cases();
    }

    private function loadRelationsToForm() : void
    {
        if ($this->disposition->dis_relation_from != OperationRelationEnum::YARD) {
            $this->disposition->dis_relation_to = OperationRelationEnum::YARD;
            $this->relationToFormAvailableRelations = [OperationRelationEnum::YARD];
            return;
        }
        $this->relationToFormAvailableRelations = OperationRelationEnum::casesExcept($this->disposition->dis_relation_from);
    }

    private function checkIfFromAndToRelationsAreTheSame(): bool
    {
        return $this->disposition->dis_relation_from &&
            $this->disposition->dis_relation_to &&
            $this->disposition->dis_relation_from->value == $this->disposition->dis_relation_to->value;
    }

    private function checkIfHasExactlyOneYardRelation(): bool
    {
        return $this->disposition->dis_relation_from &&
            $this->disposition->dis_relation_to &&
            $this->disposition->dis_relation_from != OperationRelationEnum::YARD
            && $this->disposition->dis_relation_to != OperationRelationEnum::YARD;
    }

    private function checkIfNonEditableFieldsAreDirty(): bool
    {
        return $this->disposition->isDirty(['dis_relation_from', 'dis_relation_to', 'dis_yard_id']);
    }

    public function render()
    {
        return view('livewire.dispositions.add-edit-disposition-modal');
    }
}
