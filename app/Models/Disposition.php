<?php

namespace App\Models;

use App\Enums\DispositionStatusEnum;
use App\Enums\OperationRelationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    use HasFactory;

    public $table = 'dispositions';
    public $primaryKey = 'dis_id';

    protected $guarded = ['dis_id'];
    protected $casts = [
        'dis_status' => DispositionStatusEnum::class,
        'dis_relation_from' => OperationRelationEnum::class,
        'dis_relation_to' => OperationRelationEnum::class,
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'dis_created_by_id', 'id');
    }

    public function units()
    {
        return $this->hasMany(DispositionUnit::class, 'disu_dis_id', 'dis_id');
    }

    public function operators()
    {
        return $this->belongsToMany(User::class, 'disposition_operators', 'disope_dis_id', 'disope_user_id');
    }

    public function storageYard()
    {
        return $this->belongsTo(StorageYard::class, 'dis_yard_id', 'sy_id');
    }

}
