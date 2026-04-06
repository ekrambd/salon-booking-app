<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'user_type_id',
        'role',
        'email',
        'phone',
        'password',
        'image',
        'status',
        'remember_token',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function specialities()
    {
        return $this->belongsToMany(
            Speciality::class,
            'user_speciality',
            'user_id',
            'speciality_id'
        );
    }

    public function services()
    {
        return $this->belongsToMany(
            Service::class,
            'user_service',
            'user_id',
            'service_id'
        );
    }

}
