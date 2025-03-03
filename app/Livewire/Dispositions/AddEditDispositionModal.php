<?php

namespace App\Livewire\Dispositions;

use App\Models\Disposition;
use Illuminate\Support\Arr;
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

    protected function rules() {
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
        $this->relationFromFormAvailableRelations = OperationRelationEnum::cases();
        $this->relationToFormAvailableRelations = OperationRelationEnum::cases();
    }

    #[On('openAddEditDispositionModal')]
    public function openAddEditDispositionModal(?Disposition $disposition = null)
    {
        if ($disposition->exists) {
            $this->disposition = $disposition;
            $this->edit = true;
        } else {
            $this->disposition = new Disposition();
        }

        $this->disposition->loadMissing('operators');
        $this->dispositionOperators = $this->disposition->operators()->pluck('id')->toArray();

        $this->title = $this->edit ? __('Edit disposition') : __('Add disposition');
        $this->dispatch('iniSelect2', ['operators' => $this->dispositionOperators]);
        $this->dispatch('flatpickr');
    }

    public function updatedDispositionDisRelationFrom($value)
    {
        if ($value == '') {
            $this->relationToFormAvailableRelations = OperationRelationEnum::cases();
            $this->relationFromFormAvailableRelations = OperationRelationEnum::cases();
            $this->reset('disposition.dis_relation_to');
            return;
        }

        $this->relationToFormAvailableRelations = OperationRelationEnum::casesExcept(OperationRelationEnum::from($value));
    }

    public function updatedDispositionDisRelationTo($value)
    {
        if ($value == '') {
            $this->relationFromFormAvailableRelations = OperationRelationEnum::cases();
            $this->relationToFormAvailableRelations = OperationRelationEnum::cases();
            $this->reset('disposition.dis_relation_from');
            return;
        }

        $this->relationFromFormAvailableRelations = OperationRelationEnum::casesExcept(OperationRelationEnum::from($value));
    }

    public function checkIfOperatorIsAssignedToOtherDispositions(array $data, array $dataBefore)
    {
        $dispositionsString = (new DispositionService())->checkIfOperatorBelongsToDisposition($data, $dataBefore);

        if ($dispositionsString != null) {
            $this->sweetAlert('warning', __('Operator is already assigned to disposition ') . $dispositionsString, 3000);
        }

        $this->dispositionOperators = $data;
    }

    public function save() : void
    {
        if ($this->disposition->dis_relation_from && $this->disposition->dis_relation_to && $this->disposition->dis_relation_from == $this->disposition->dis_relation_to) {
            $this->sweetAlert('error', __('Relation from and relation to cannot be the same'), 2000);
            return;
        }

        $this->validate();

        try {
            DB::transaction(function () {
                $this->disposition->dis_created_by_id = Auth::id();
                $this->disposition->save();
                $this->disposition->operators()->sync($this->dispositionOperators);
            });

            if (!$this->edit) {
                $this->edit = true;
                $this->sweetAlert('success', __('Disposition added successfully'), 3000);
                $this->title = __('Edit disposition');
            } else {
                $this->sweetAlert('success', __('Edited'), 3000);
                $this->dispatch('closeModal');
            }

            $this->dispatch('refreshDispositionTable');

        } catch (\Exception $e) {
            dd($e);
            $this->sweetAlert('error', __('Something went wrong'), 3000);
            return;
        }
    }

    public function render()
    {
        return view('livewire.dispositions.add-edit-disposition-modal');
    }
}
