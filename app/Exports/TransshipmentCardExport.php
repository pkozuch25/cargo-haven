<?php

namespace App\Exports;

use App\Livewire\TableComponent;
use App\Models\TransshipmentCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class TransshipmentCardExport extends TableComponent implements FromView, ShouldQueue
{
    use Queueable, Exportable;

    public $dateRange;
    public TransshipmentCard $transshipmentCard;

    public function __construct($transshipmentCard)
    {
        $this->transshipmentCard = $transshipmentCard;
    }

    public function view(): View
    {
        return view('excel.transshipment-card-export', [
            'data' => $this->transshipmentCard->units,
            'transshipmentCard' => $this->transshipmentCard
        ]);
    }
}
