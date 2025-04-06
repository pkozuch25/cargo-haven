<?php

namespace App\Models;

use App\Enums\DispositionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use App\Enums\DispositionUnitStatusEnum;

class DispositionUnit extends Model
{

    public $table = 'disposition_units';
    public $primaryKey = 'disu_id';

    protected $guarded = ['disu_id'];
    protected $casts = [
        'disu_status' => DispositionUnitStatusEnum::class,
    ];

    public function disposition()
    {
        return $this->belongsTo(Disposition::class, 'disu_dis_id', 'dis_id');
    }

    public function transshipmentCardUnit()
    {
        return $this->belongsTo(TransshipmentCardUnit::class, 'disu_cardunit_id', 'tcu_id');
    }

    public function scopeForOperator($query, $operatorId)
    {
        return $query->whereHas('disposition.operators', function ($query) use ($operatorId) {
            $query->where('disope_user_id', $operatorId);
        })->whereHas('disposition', function ($query) {
            $query->where('dis_status', DispositionStatusEnum::PROCESSING->value);
        });
    }
}
