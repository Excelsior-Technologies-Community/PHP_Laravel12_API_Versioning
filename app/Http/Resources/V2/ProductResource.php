<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'name' => $this->name,

            'description' => $this->description,

            'price' => $this->price,

            'sku' => $this->sku,

            'stock' => $this->stock,

            'category' => $this->category,

            'is_active' => $this->is_active,

            'is_featured' => $this->is_featured,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}