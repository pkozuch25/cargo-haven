<?php

namespace App\Livewire\StorageYards;

use App\Models\StorageCell;
use App\Models\StorageYard;
use Livewire\Attributes\On;
use App\Livewire\ModalComponent;
use App\Services\StorageYardService;

class AddEditStorageYardModal extends ModalComponent
{
    public $yard, $edit = false, $title;

    protected function rules()
    {
        return [
            'yard.sy_name' => ['required', 'string', 'unique:storage_yards,sy_name' . (',' . $this->yard->sy_id) . ',sy_id'],
            'yard.sy_name_short' => ['required', 'string', 'unique:storage_yards,sy_name_short' . (',' . $this->yard->sy_id) . ',sy_id'],
            'yard.sy_length' => ['required', 'integer', 'max:100', 'min:1'],
            'yard.sy_width' => ['required', 'integer', 'max:100', 'min:1'],
            'yard.sy_cell_count' => ['required', 'integer', 'max:50', 'min:1'],
            'yard.sy_row_count' => ['required', 'integer', 'max:20', 'min:1'],
            'yard.sy_height' => ['required', 'integer', "max:4", 'min:1'],
        ];
    }

    #[On('openAddEditYardModal')]
    public function openAddEditYardModal(?StorageYard $yard = null)
    {
        if ($yard->exists) {
            $this->yard = $yard;
            $this->edit = true;
        } else {
            $this->yard = new StorageYard();
        }

        $this->updateTitle();
    }

    public function save() : void
    {
        $this->validate();

        if ($this->edit && $this->checkIfNonEditableFieldsAreDirty()) {
            $this->sweetAlert('error', __('You cannot edit non-editable fields.'));
            return;
        }

        if ($this->edit && $this->yard->isDirty(['sy_name_short'])) {
            StorageCell::where('sc_yard_id', $this->yard->sy_id)->update(['sc_yard_name_short' => $this->yard->sy_name_short]);
        }

        if (!$this->yard->save()) {
            $this->sweetAlert('error', __('Something went wrong'));
            return;
        }

        $this->sweetAlert('success', __("Saved."));

        if (!$this->edit) {
            (new StorageYardService())->generateStorageCells($this->yard);
            $this->edit = true;
        } else {
            $this->closeModal(true);
        }

        $this->dispatch('refreshDispositionTable');

        $this->updateTitle();
    }

    private function updateTitle() : void
    {
        $this->title = $this->edit ? __('Edit Storage Yard') : __('Add Storage Yard');
    }

    private function checkIfNonEditableFieldsAreDirty() : bool
    {
        return $this->yard->isDirty(['sy_length', 'sy_width', 'sy_cell_count', 'sy_row_count', 'sy_height']);
    }

    public function render()
    {
        return view('livewire.storage-yards.add-edit-storage-yard-modal');
    }
}
