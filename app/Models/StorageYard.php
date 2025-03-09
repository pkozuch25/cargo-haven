<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorageYard extends Model
{

    public $table = 'storage_yards';
    public $primaryKey = 'sy_id';

    protected $guarded = ['sy_id'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'storage_yard_users_relation', 'sy_id', 'user_id');
    }

    public function disposition()
    {
        return $this->belongsTo(Disposition::class, 'sy_id', 'dis_yard_id');
    }
}
