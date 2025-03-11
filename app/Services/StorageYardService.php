<?php

namespace App\Services;

use App\Models\StorageYard;

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
}
