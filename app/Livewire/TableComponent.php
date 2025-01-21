<?php

namespace App\Livewire;

use Livewire\WithPagination;
use App\Livewire\MainComponent;

class TableComponent extends MainComponent
{
    use WithPagination;

    public $perPage = 10, $archive,  $searchTerm = [], $sortColumn = 'created_at', $sortDirection = 'desc', $columnSelectOptions = [], $columnDisplay = [], $showTableSettings = false;

    public function backRefresh($page)
    {
        $this->gotoPage($page, 'page');
    }

    public function updating()
    {
        $this->resetPage();
        $this->dispatch('refreshSelect2');
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'desc' ? 'asc' : 'desc';
        $this->dispatch('refreshSelect2');
    }

    protected function tableRefresh($query)
    {

        $query->where(function ($query) {

            if ($this->searchTerm != "") {

                foreach ($this->searchTerm as $type => $value) {
                    if ($type == 'text') {
                        foreach ($value as $key => $value) {
                            if ($value != '') {
                                $query->where($key, 'like', '%' . $value . '%');
                            }
                        }
                    }
                    if ($type == 'select') {
                        foreach ($value as $key => $value) {
                            if ($value != '') {
                                $query->where($key, $value);
                            }
                        }
                    }
                    if ($type == 'selectMultiple') {
                        foreach ($value as $key => $value) {
                            if (!empty($value)) {
                                $query->whereIn($key, $value);
                            }
                        }
                    }
                    if ($type == 'date') {
                        foreach ($value as $key => $value) {
                            if ($value != '') {
                                $query->whereBetween($key, [substr($value, 0, 10) . ' 00:00:00', substr($value, 14, 10) . ' 23:59:59']);
                            }
                        }
                    }

                    if ($type == 'relationText') {
                        foreach ($value as $relationName => $relation) {
                            foreach ($relation as $field => $fieldValue) {
                                if ($fieldValue != '') {
                                    $query->whereRelation($relationName, $field, 'like', '%' . trim($fieldValue) . '%');
                                }
                            }
                        }
                    }

                    if ($type == 'relationSelect') {

                        foreach ($value as $relationName => $relation) {
                            foreach ($relation as $field => $fieldValue) {
                                if ($fieldValue != '') {
                                    $query->whereRelation($relationName, $field, $fieldValue);
                                }
                            }
                        }
                    }
                    if ($type == 'relationSelectMultiple') {
                        foreach ($value as $relationName => $relation) {
                            foreach ($relation as $field => $fieldValue) {
                                if ($fieldValue != '') {
                                    $query->whereRelation($relationName, function ($q) use ($field, $fieldValue) {
                                        $q->whereIn($field, $fieldValue);
                                    });
                                }
                            }
                        }
                    }
                }
            }
        })
            ->orderBy($this->sortColumn, $this->sortDirection);

        if ($this->perPage != '') {
            return $query->paginate($this->perPage);
        }

        return $query->get();
    }

    public function showColumn($column)
    {
        return in_array($column, $this->columnDisplay);
    }
}
