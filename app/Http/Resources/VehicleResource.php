<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $reviews = $this->reviews;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image_path' => $this->images->first()?->image_path,
            'reviews' => [
                'average' => round($reviews->avg('rating'), 2),
                'count' => $reviews->count(),
            ],
            'minimum_price' => round($this->prices->first()?->price),
        ];
    }
}
