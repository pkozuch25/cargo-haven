<?php

namespace App\Livewire;

use Livewire\Component;

class MainComponent extends Component
{

    public function sweetAlert(string $type, string $text, int $time = 3000)
    {
        $this->dispatch('sweetAlert', ['type' => $type, 'text' => $text, 'time' => $time]);
    }

    public function sweetAlertCenter(string $type, string $text, int $time)
    {
        $this->dispatch('sweetAlertCenter', ['type' => $type, 'text' => $text, 'time' => $time]);
    }

    public function sweetAlertConfirm(string $type, string $title, string $text, string $method, ?int $id)
    {
        $this->dispatch('sweetAlertConfirm', [
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'method' => $method,
            'id' => $id
        ]);
    }

    public function sweetAlertConfirmWithNoButton(string $type, string $title, string $text, string $method, ?int $id)
    {
        $this->dispatch('sweetAlertConfirmWithNoButton', [
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'method' => $method,
            'id' => $id
        ]);
    }

    public function sweetAlertConfirmWithNoButtonAction(string $type, string $title, string $text, array $yesMethod, array $noMethod)
    {
        $this->dispatch('sweetAlertConfirmWithNoButtonAction', [
            'type' => $type,
            'title' => $title,
            'text' => $text,
            'yes' => $yesMethod,
            'no' => $noMethod
        ]);
    }

    public function sweetAlertSaved()
    {
        $this->sweetAlert('success', __('Saved'), 800);
    }

    public function sweetAlertDeleted()
    {
        $this->sweetAlert('success', __('Deleted'), 800);
    }

    public function sweetAlertError()
    {
        $this->sweetAlert('error', __('Błąd'), 800);
    }


}
