<?php

namespace App\Models;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quantity',
        'product_id',
        'total',
        'archived',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_produit', 'order_id', 'produit_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


   
}
