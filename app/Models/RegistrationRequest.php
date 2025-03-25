<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\RegistrationRequestStatusEnum;

class RegistrationRequest extends Model
{

    public $table = 'registration_requests';
    public $primaryKey = 'rr_id';
    
    protected $guarded = ['rr_id'];
    protected $casts = [
        'rr_status' => RegistrationRequestStatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'rr_user_id', 'id');
    }
}
