<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransshipmentCardUnit extends Model
{
    use HasFactory;

    public $table = 'transshipment_cards_units';
    public $primaryKey = 'tcu_id';

    protected $guarded = ['tcu_id'];

    public function transshipmentCard()
    {
        return $this->belongsTo(TransshipmentCard::class, 'tcu_tc_id', 'tc_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'tcu_operator_id', 'id');
    }

    public function dispositionUnit()
    {
        return $this->belongsTo(DispositionUnit::class, 'tcu_disp_id', 'disu_id');
    }

    public function arrivalDeposit()
    {
        return $this->hasOne(Deposit::class, 'dep_arrival_cardunit_id', 'tcu_id');
    }

    public function departureDeposit()
    {
        return $this->hasOne(Deposit::class, 'dep_departure_cardunit_id', 'tcu_id');
    }
}
