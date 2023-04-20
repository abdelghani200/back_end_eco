<?php

namespace App\Http\Resources\Categorie;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            // 'links' =>[
            //     'products' => route('categories.products.index', $this->id)
            // ],
            // 'href' => [
            //     'link' => route('categories.show', ['category' => $this->id])
            // ]
            // 'href' => [
            //     'products' => route('categories.index', $this->id)
            // ]
            'href' =>[
                'link' => route('categories.showCategory',$this->id)
            ]
        ];
    }
}



