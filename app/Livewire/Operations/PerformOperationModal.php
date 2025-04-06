<?php

namespace App\Livewire\Operations;

use App\Livewire\ModalComponent;
use App\Models\DispositionUnit;
use Livewire\Attributes\On;

class PerformOperationModal extends ModalComponent
{
    public $title;

    #[On('performOperationModal')]
    public function performOperationModal(DispositionUnit $operation)
    {
        $this->title = __('Perform operation - ') . $operation->disu_container_number;
    }

    public function performOperation()
    {
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
