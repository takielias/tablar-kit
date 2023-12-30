<?php

namespace Takielias\TablarKit\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FolderCreatedResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'time' => now(),
            'code' => 220,
            'data' => [
                'messages' => [
                    __('Directory created successfully'),
                ],
            ],
        ];
    }
}
