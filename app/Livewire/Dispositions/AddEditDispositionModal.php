<?php

namespace App\Livewire\Dispositions;

use App\Enums\OperationRelationEnum;
use Livewire\Attributes\On;
use App\Livewire\ModalComponent;
use App\Models\Disposition;

class AddEditDispositionModal extends ModalComponent
{
    public $disposition, $edit = false, $title, $relationFromFormAvailableRelations, $relationToFormAvailableRelations;

    protected $rules = [
        'disposition.dis_suggested_date' => ['required', 'dateTime'],
        'disposition.dis_relation_from' => ['required', OperationRelationEnum::class], //todo prawidłowa walidacja
        'disposition.dis_relation_to' => ['required', OperationRelationEnum::class],
    ];

    public function mount()
    {
        $this->relationFromFormAvailableRelations = OperationRelationEnum::cases();
        $this->relationToFormAvailableRelations = OperationRelationEnum::cases();
    }

    public function updatedDispositionDisRelationFrom($value)
    {
        if ($value == '') {
            $this->relationToFormAvailableRelations = OperationRelationEnum::cases();
            $this->reset('disposition.dis_relation_to');
            return;
        }

        $this->relationToFormAvailableRelations = OperationRelationEnum::casesExcept(OperationRelationEnum::from($value));
    }

    public function updatedDispositionDisRelationTo($value)
    {
        if ($value == '') {
            $this->relationFromFormAvailableRelations = OperationRelationEnum::cases();
            $this->reset('disposition.dis_relation_from');
            return;
        }

        $this->relationFromFormAvailableRelations = $value == '' ? OperationRelationEnum::cases() : OperationRelationEnum::casesExcept(OperationRelationEnum::from($value));
    }

    #[On('openAddEditDispositionModal')]
    public function openAddEditDispositionModal(?Disposition $disposition = null)
    {
        if ($disposition) {
            $this->disposition = $disposition;
            $this->edit = true;
        } else {
            $this->disposition = new Disposition();
        }
        $this->title = $this->edit ? __('Edit disposition') : __('Add disposition');
    }

    public function save()
    {
        $this->validate();

        if ($this->disposition->dis_relation_from == $this->disposition->dis_relation_to) {
            $this->sweetAlert('error', __('Relation from and relation to cannot be the same'), 2000);
            return;
        }

        $this->disposition->save();
        if (!$this->edit) {
            $this->edit = true;
        } else {
            $this->dispatch('closeModal');
        }

        $this->dispatch('refreshDispositionTable');
    }

    public function render()
    {
        return view('livewire.dispositions.add-edit-disposition-modal');
    }
}
