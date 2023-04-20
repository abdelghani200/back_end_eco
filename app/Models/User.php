<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Order;
use App\Models\Product;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'lastName',
        'ville',
        'address',
        'numTele',
        'role',
        'password_confirmation',
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
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->tokens()->each(function ($token) {
                $token->tokenable_type = 'App\Models\User';
            });
            $user->role = 'user';
        });
    }

    public function tokens()
    {
        return $this->hasMany(PersonalAccessToken::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'carts', 'user_id', 'product_id')->withPivot('quantity');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
