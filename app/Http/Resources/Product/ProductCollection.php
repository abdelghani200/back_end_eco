<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return 
        [
            'name' => $this->name,
            'totalPrice' => round((1 -($this->discount/100)) * $this->price, 2),
            'rating' => $this->reviews->count() > 0 ? round($this->reviews->sum('star') / $this->reviews->count(), 0) : 'No rating yet',
            'href' =>[
                'link' => route('products.show',$this->id)
            ]
        ];
    }
}
