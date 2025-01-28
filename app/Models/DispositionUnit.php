<?php

namespace App\Models;

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
        return $this->belongsTo(Disposition::class, 'dis_id', 'disu_dis_id');
    }
}
