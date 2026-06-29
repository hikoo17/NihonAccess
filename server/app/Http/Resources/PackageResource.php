<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'level' => $this->level,
            'price' => $this->price,
            'price_formatted' => $this->price_formatted,
            'duration_days' => $this->duration_days,
            'duration_label' => $this->duration_label,
        ];
    }
}
