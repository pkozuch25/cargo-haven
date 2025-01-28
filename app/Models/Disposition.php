<?php

namespace App\Models;

use App\Enums\DispositionStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{

    public $table = 'dispositions';
    public $primaryKey = 'dis_id';
    
    protected $guarded = ['dis_id'];
    protected $casts = [
        'dis_status' => DispositionStatusEnum::class,
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'dis_created_by_id', 'id');
    }

    public function units()
    {
        return $this->hasMany(DispositionUnit::class, 'disu_dis_id', 'dis_id');
    }
}
