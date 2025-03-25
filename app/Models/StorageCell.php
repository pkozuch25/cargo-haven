<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageCell extends Model
{
    use HasFactory;

    public $table = 'storage_cells';
    public $primaryKey = 'sc_id';

    protected $guarded = ['sc_id'];

    public function storageYard()
    {
        return $this->belongsTo(StorageYard::class, 'sc_yard_id', 'sy_id');
    }

    public function getCellTextAttribute()
    {
        return $this->sc_yard_name_short . '/' . $this->sc_cell . '/' . $this->sc_row . '/' . $this->sc_height;
    }
}
