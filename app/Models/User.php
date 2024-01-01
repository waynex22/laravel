<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HandleImageTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HandleImageTrait, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'gender',
        'role_id'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function hasRole($roleName)
    {
        return $this->role->name === $roleName;
    }
    public function hasAnyRole($roleName)
    {
        return $this->role()->whereIn('name', $roleName)->count() > 0;
    }

    // public function getImagePathAttribute(){
    //     return asset($this->images->count() > 0 ? 'upload/' . $this->images->first()->url : 'upload/defaultuser.png');
    // }
}
