<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatusEnum::class
        ];
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function dispositions()
    {
        return $this->belongsToMany(Disposition::class, 'disposition_operators', 'disope_user_id', 'disope_dis_id');
    }

    public function storageYards()
    {
        return $this->belongsToMany(StorageYard::class, 'storage_yard_users_relation', 'user_id', 'sy_id');
    }

    public function scopeOperator($query)
    {
        return $query->role('Operator');
    }

    public function scopeAdmin($query)
    {
        return $query->role('Admin');
    }

    public function scopeActive($query)
    {
        return $query->where('status', UserStatusEnum::ACTIVE);
    }

    public function createdTransshipmentCards()
    {
        return $this->hasMany(TransshipmentCard::class, 'tc_created_by_user', 'id');
    }

    public function operatedTransshipmentCardUnits()
    {
        return $this->hasMany(TransshipmentCardUnit::class, 'tcu_operator_id', 'id');
    }
}
