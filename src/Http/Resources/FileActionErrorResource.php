<?php

namespace Takielias\TablarKit\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileActionErrorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => false,
            'data' => [
                'code' => 422,
                'messages' => $this->resource,
            ],
        ];
    }
}
