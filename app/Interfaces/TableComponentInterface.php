<?php

namespace App\Interfaces;

/**
 * @property string $sortColumn
 */
Interface TableComponentInterface {
    public function queryRefresh();
    public function render();
}