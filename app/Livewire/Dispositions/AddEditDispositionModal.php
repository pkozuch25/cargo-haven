<?php

namespace App\Livewire\Dispositions;

use App\Models\Disposition;
use App\Models\StorageYard;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Validation\Rule;
use App\Livewire\ModalComponent;
use Illuminate\Support\Facades\DB;
use App\Enums\DispositionStatusEnum;
use App\Enums\OperationRelationEnum;
use App\Services\DispositionService;
use Illuminate\Support\Facades\Auth;

class AddEditDispositionModal extends ModalComponent
{
    public $disposition, $edit = false, $title, $relationFromFormAvailableRelations, $relationToFormAvailableRelations, $dispositionOperators, $storageYards;

    #[Url(nullable: true, keep: false)]
    public $disp;

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
        if ($this->disp != null) {
            $this->disposition = Disposition::where('dis_id', $this->disp)->first();
            $this->openAddEditDispositionModal($this->disposition);
            $this->dispatch('openDispositionModalBlade');
            $this->dispatch('setDisNumberInDispositionsTable', $this->disposition->dis_number);
        }
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

        $this->updateTitle();

        $this->dispatch('iniSelect2', ['operators' => $this->dispositionOperators]);
        $this->dispatch('flatpickr');

        $this->loadRelationsFromForm();
        $this->loadStorageYards();
        $this->disposition->syncOriginal();
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

    public function updateTitle()
    {
        if ($this->edit) {
            $this->title = __('Edit disposition') . ' ' . $this->disposition->dis_number;
        } else {
            $this->title = __('Add disposition');
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->sweetAlertValidation()) {
            return;
        }

        try {
            DB::transaction(function () {
                $this->disposition->dis_created_by_id = Auth::id();
                $this->disposition->save();
                $this->disposition->operators()->sync($this->dispositionOperators);
            });

            if (!$this->edit) {
                $this->disposition = (new DispositionService())->createDispositionNumber($this->disposition);
                $this->disposition->save();
                $this->edit = true;
                $this->updateTitle();
                $this->sweetAlert('success', __('Disposition added successfully'));
            } else {
                $this->sweetAlert('success', __('Edited'));
                if (!$this->dispositionHasUnits()) {
                    $this->closeModal(true);
                }
            }

            $this->dispatch('refreshDispositionTable');
            $this->dispatch('refreshOperationsCounter');
        } catch (\Exception $e) {
            $this->sweetAlert('error', __('Something went wrong'));
            return;
        }
    }

    public function dispositionHasUnits(): bool
    {
        return (new DispositionService())->checkIfDispositionHasAnyUnits($this->disposition);
    }

    public function dispositionWasCancelledOrFinalized(): bool
    {
        return $this->disposition->dis_status == DispositionStatusEnum::CANCELLED || $this->disposition->dis_status == DispositionStatusEnum::FINALIZED;
    }

    public function canChangeStatus(): bool
    {
        return $this->disposition->dis_status ==
            DispositionStatusEnum::NOT_CONFIRMED ||
            $this->disposition->dis_status == DispositionStatusEnum::CONFIRMED;
    }

    public function changeStatus(): void
    {
        if (!$this->canChangeStatus()) {
            $this->sweetAlert('error', __('Cannot change status'));
            return;
        }

        if ($this->disposition->dis_status == DispositionStatusEnum::NOT_CONFIRMED) {
            $this->disposition->dis_status = DispositionStatusEnum::CONFIRMED;
        } else if ($this->disposition->dis_status == DispositionStatusEnum::CONFIRMED) {
            $this->disposition->dis_status = DispositionStatusEnum::PROCESSING;
        }

        $this->disposition->save();
        $this->dispatch('refreshDispositionTable');
        $this->dispatch('refreshOperationsCounter');
    }

    public function cancelDispositionConfirm() : void
    {
        $this->sweetAlertConfirm(
            'warning',
            __('Are you sure you want to cancel this disposition?'),
            __('This action cannot be undone'),
            'cancelDisposition',
            '',
            $this->disposition->dis_id
        );
    }

    #[On('cancelDisposition')]
    public function cancelDisposition() : void
    {
        if ($this->disposition->dis_status != DispositionStatusEnum::NOT_CONFIRMED) {
            $this->sweetAlert('error', __('This disposition cannot be cancelled anymore'));
            return;
        }

        $this->disposition->dis_status = DispositionStatusEnum::CANCELLED;
        $this->disposition->save();

        $this->sweetAlert('success', __('Disposition has been successfully cancelled'));
        $this->closeModal(true);
        $this->dispatch('refreshDispositionTable');
        $this->dispatch('refreshOperationsCounter');
    }

    private function loadRelationsFromForm()
    {
        $this->relationFromFormAvailableRelations = OperationRelationEnum::cases();
    }

    private function loadRelationsToForm(): void
    {
        if ($this->disposition->dis_relation_from != OperationRelationEnum::YARD) {
            $this->disposition->dis_relation_to = OperationRelationEnum::YARD;
            $this->relationToFormAvailableRelations = [OperationRelationEnum::YARD];
            return;
        }
        $this->relationToFormAvailableRelations = OperationRelationEnum::casesExcept($this->disposition->dis_relation_from);
    }

    private function loadStorageYards()
    {
        $this->storageYards = getAuthenticatedUserModel()->storageYards()->get();
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

    private function checkIfYardWithProvidedIdExists(): bool
    {
        return $this->disposition->dis_yard_id && !StorageYard::find($this->disposition->dis_yard_id);
    }

    private function sweetAlertValidation(): bool
    {
        if ($this->checkIfFromAndToRelationsAreTheSame()) {
            $this->sweetAlert('error', __('Relation from and relation to cannot be the same'));
            return true;
        }

        if ($this->checkIfHasExactlyOneYardRelation()) {
            $this->sweetAlert('error', __('One of the relations must be a yard relation'));
            return true;
        }

        if ($this->dispositionHasUnits() && $this->checkIfNonEditableFieldsAreDirty()) {
            $this->sweetAlert('error', __('Disposition has units, some fields cannot be edited'));
            return true;
        }

        if ($this->checkIfYardWithProvidedIdExists()) {
            $this->sweetAlert('error', __('Yard with provided id does not exist'));
            return true;
        }

        return false;
    }

    public function render()
    {
        return view('livewire.dispositions.add-edit-disposition-modal');
    }
}
