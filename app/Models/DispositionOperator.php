<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositionOperator extends Model
{
    public $table = 'disposition_operators';
    public $primaryKey = 'disope_id';

    protected $guarded = ['disope_id'];

    public function disposition()
    {
        return $this->belongsTo(Disposition::class, 'disope_dis_id', 'dis_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'disope_user_id', 'id');
    }
}
