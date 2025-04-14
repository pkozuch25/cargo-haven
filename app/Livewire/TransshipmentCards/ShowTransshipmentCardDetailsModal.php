<?php

namespace App\Livewire\TransshipmentCards;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use App\Livewire\ModalComponent;
use App\Models\TransshipmentCard;
use Maatwebsite\Excel\Facades\Excel;
use App\Enums\TransshipmentCardStatusEnum;
use App\Exports\TransshipmentCardExport;
use Carbon\Carbon;

class ShowTransshipmentCardDetailsModal extends ModalComponent
{
    public $transshipmentCard;
    public $cardId;

    #[Url(nullable: true, keep: false)]
    public $card;

    public function mount()
    {
        if ($this->card != null) {
            $this->cardId = TransshipmentCard::where('tc_id', $this->card)->first()->tc_id;
            $this->openModal($this->cardId);
            $this->dispatch('openTransshipmentCardModalBlade');
            $this->dispatch('setTcNumberInTransshipmentCardsTable', $this->transshipmentCard->tc_number);
        }
    }

    #[On('openTransshipmentCardDetailsModal')]
    public function openModal($cardId)
    {
        $this->cardId = $cardId;
        $this->loadTransshipmentCard();
    }

    public function loadTransshipmentCard()
    {
        $this->transshipmentCard = TransshipmentCard::with(['storageYard', 'createdBy', 'units.operator', 'units.dispositionUnit.disposition'])
            ->findOrFail($this->cardId);
    }

    public function exportToXls()
    {
        return Excel::download(new TransshipmentCardExport($this->transshipmentCard), __("transshipment-card-") . Carbon::now()->format('y-m-d-H:i') . '.xlsx'); //todo
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

        $this->transshipmentCard->tc_status = TransshipmentCardStatusEnum::COMPLETED;
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

        $this->transshipmentCard->tc_status = TransshipmentCardStatusEnum::PROCESSING;
        $this->transshipmentCard->save();

        $this->loadTransshipmentCard();
        $this->sweetAlert('success', __('Card has been reopened successfully'));
    }

    public function render()
    {
        return view('livewire.transshipment-cards.show-transshipment-card-details-modal');
    }
}
