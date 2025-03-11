<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    public $table = 'deposits';
    public $primaryKey = 'dep_id';

    protected $guarded = ['dep_id'];

    public function storageCell()
    {
        return $this->belongsTo(StorageCell::class, 'dep_sc_id', 'sc_id');
    }

    public function arrivalDispositionUnit()
    {
        return $this->belongsTo(DispositionUnit::class, 'dep_arrival_disu_id', 'disu_id');
    }

    public function departureDispositionUnit()
    {
        return $this->belongsTo(DispositionUnit::class, 'dep_departure_disu_id', 'disu_id');
    }

    public function scopeAvailable($query)
    {
        return $query->whereNull('dep_departure_date');
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('dep_departure_date');
    }

}
