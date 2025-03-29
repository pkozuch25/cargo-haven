<?php

namespace App\Livewire\TransshipmentCards;

use App\Models\TransshipmentCard;
use App\Enums\TransshipmentCardStatusenum;
use App\Livewire\ModalComponent;
use Livewire\Attributes\On;

class ShowTransshipmentCardDetailsModal extends ModalComponent
{
    public $transshipmentCard;
    public $cardId;

    #[On('openTransshipmentCardDetailsModal')]
    public function openModal($cardId)
    {
        $this->cardId = $cardId;
        $this->loadTransshipmentCard();
    }

    public function loadTransshipmentCard()
    {
        $this->transshipmentCard = TransshipmentCard::with(['storageYard', 'createdBy', 'units.operator', 'units.disposition'])
            ->findOrFail($this->cardId);
    }

    public function completeCard()
    {
        if (!$this->transshipmentCard) {
            return;
        }

        if (!can('edit_transshipment_cards')) {
            $this->sweetAlert('error', __('You do not have permission to complete this card'));
            return;
        }

        $this->transshipmentCard->tc_status = TransshipmentCardStatusenum::COMPLETED;
        $this->transshipmentCard->save();

        $this->loadTransshipmentCard();
        $this->sweetAlert('success', __('Card has been completed successfully'));
    }

    public function reopenCard()
    {
        if (!$this->transshipmentCard) {
            return;
        }

        if (!can('edit_transshipment_cards')) {
            $this->sweetAlert('error', __('You do not have permission to reopen this card'));
            return;
        }

        $this->transshipmentCard->tc_status = TransshipmentCardStatusenum::PROCESSING;
        $this->transshipmentCard->save();

        $this->loadTransshipmentCard();
        $this->sweetAlert('success', __('Card has been reopened successfully'));
    }

    public function render()
    {
        return view('livewire.transshipment-cards.show-transshipment-card-details-modal');
    }
}
