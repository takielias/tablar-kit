<?php

namespace Takielias\TablarKit\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SuccessActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'time' => now(),
            'data' => [
                'code' => 220,
            ],
        ];
    }
}
