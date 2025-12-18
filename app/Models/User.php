<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Crypt;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    // ...

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }

    public function setPhoneAttribute($value)
    {
    $this->attributes['phone'] = $value
        ? Crypt::encryptString($value)
        : null;
    }   

    public function setAddressAttribute($value)
    {
    $this->attributes['address'] = $value
        ? Crypt::encryptString($value)
        : null;
    }

// DECRYPT saat diambil dari database
    public function getPhoneAttribute($value)
    {
    return $value ? Crypt::decryptString($value) : null;
    }

    public function getAddressAttribute($value)
    {
    return $value ? Crypt::decryptString($value) : null;
    }
}
