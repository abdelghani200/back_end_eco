<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Categorie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'price',
        'stock',
        'code_bare',
        'old_price',
        'discount',
        'categorie_id'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'carts', 'product_id', 'user_id')->withPivot('quantity');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_produit', 'produit_id', 'order_id')->withPivot('quantite');
    }


   
}
