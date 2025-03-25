<?php

namespace App\Models;

use App\Enums\DispositionStatusEnum;
use App\Enums\OperationRelationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransshipmentCard extends Model
{
    use HasFactory;

    public $table = 'transshipment_cards';
    public $primaryKey = 'tc_id';

    protected $guarded = ['tc_id'];
    protected $casts = [
        'tc_relation_from' => OperationRelationEnum::class,
        'tc_relation_to' => OperationRelationEnum::class,
        'tc_status' => DispositionStatusEnum::class,
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'tc_created_by_user', 'id');
    }

    public function storageYard()
    {
        return $this->belongsTo(StorageYard::class, 'tc_yard_id', 'sy_id');
    }

    public function units()
    {
        return $this->hasMany(TransshipmentCardUnit::class, 'tcu_tc_id', 'tc_id');
    }

    public function arrivalDeposits()
    {
        return $this->hasMany(Deposit::class, 'dep_arrival_card_id', 'tc_id');
    }

    public function departureDeposits()
    {
        return $this->hasMany(Deposit::class, 'dep_departure_card_id', 'tc_id');
    }
}
