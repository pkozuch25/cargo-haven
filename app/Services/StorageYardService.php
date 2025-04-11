<?php

namespace App\Services;

use App\Models\StorageCell;
use App\Models\StorageYard;
use Illuminate\Support\Collection;

class StorageYardService
{
    public function alphabeticalRows(StorageYard $yard) : array
    {
        $alphabet = range('a', 'z');
        $rows = [];

        for ($i = 0; $i < $yard->sy_row_count; $i++) {
            $rows[] = strtoupper($alphabet[$i]);
        }

        return $rows;
    }

    public function generateStorageCells(StorageYard $yard) : void
    {
        $rows = $this->alphabeticalRows($yard);

        $cellsToGenerate = [];

        for ($cell = 1; $cell < $yard->sy_cell_count; $cell++) {
            foreach ($rows as $row) {
                for ($height = 1; $height <= $yard->sy_height; $height++) {

                    $newCell['sc_yard_name_short'] = $yard->sy_name_short;
                    $newCell['sc_cell'] = $cell;
                    $newCell['sc_row'] = $row;
                    $newCell['sc_height'] = $height;

                    array_push($cellsToGenerate, $newCell);
                }
            }
        }

        $yard->storageCells()->createMany($cellsToGenerate);
    }


    public function getAvailableStorageCells(int $yardId, ?int $depositId = null) : array
    {
        $storageCells = StorageCell::where('sc_yard_id', $yardId)
            ->with(['deposit' => function ($query) {
                $query->whereNull('dep_departure_date');
            }])
            ->get();

        $groupedColumns = $storageCells->groupBy(['sc_cell', 'sc_row', 'sc_height']);

        foreach ($groupedColumns as $columnNumber => $column) {
            foreach ($column as $rowLetter => $row) {
                foreach ($row as $heightNumber => $height) {
                    $currentCell = $height->first();

                    $currentCell->cell_available = 1;

                    if ($currentCell->deposit) {
                        $currentCell->cell_available = 0;
                        continue;
                    }

                    if ($heightNumber > 1) {
                        $cellBelow = $groupedColumns[$columnNumber][$rowLetter][$heightNumber - 1]->first() ?? null;

                        if (!$cellBelow || !$cellBelow->deposit) {
                            $currentCell->cell_available = 0;
                            continue;
                        }

                        $depositBelow = $cellBelow->deposit;

                        if ($depositId && $depositBelow->dep_id == $depositId) {
                            $currentCell->cell_available = 0;
                            continue;
                        }
                    }
                }
            }
        }

        return $groupedColumns->toArray();
    }

}
