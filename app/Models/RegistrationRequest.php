<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\RegistrationRequestStatusEnum;

/**
 * 
 *
 * @property int $rr_id
 * @property int|null $rr_user_id ID of user created with the request
 * @property RegistrationRequestStatusEnum $rr_status 0 = Pending, 1 = Approved, 2 = Rejected
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest whereRrId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest whereRrStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest whereRrUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegistrationRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
