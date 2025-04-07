<?php

namespace App\Livewire\Operations;

use Livewire\Attributes\On;
use App\Models\DispositionUnit;
use App\Livewire\ModalComponent;
use App\Models\TransshipmentCard;
use App\Services\TransshipmentCardService;

class PerformOperationModal extends ModalComponent
{
    public $title, $selectedCard, $operation, $availableCards = [];

    protected $rules = [
        'selectedCard' => 'required'
    ];

    #[On('performOperationModal')]
    public function performOperationModal(DispositionUnit $operation) : void
    {
        $this->operation = $operation;
        $this->availableCards = (new TransshipmentCardService())->getAvailableCards($this->operation);
        $this->title = __('Execute operation - ') . $operation->disu_container_number;
    }

    public function performOperation() : void
    {
        $this->validate();

        $card = null;

        if ($this->selectedCard == 'newCard') {
            $card = (new TransshipmentCardService())->createNewCard($this->operation);
        } else {
            $card = TransshipmentCard::findOrFail($this->selectedCard);
        }



        // todo do dyspozycji operacji trzeba dodać cardUnitId, gdy wszystkie jednostki z dyspozycji zostaną zrealizowane należy zmienić status i ustawić datę zakończenia
        // (dis_completion_date). dodatkowo z każdej jednostki trzeba wygenerować jednostki transshipment card albo cały nagłówek gdy użytkownik wybrał w selectcie "nowa karta".
        // gdy jest to przeładunek na plac należy wybrać miejsce składowania do którego ma być przypisane(dostępne na placu wybranym w dyspozycji)
        // i dodać do depozytów z odpowiednim id karty przeładunkowej i dyspozycji.
        $this->dispatch('refreshOperationsCounter');
    }

    public function render()
    {
        return view('livewire.operations.perform-operation-modal');
    }
}
