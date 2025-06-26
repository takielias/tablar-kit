<?php

namespace TakiElias\TablarKit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use TakiElias\TablarKit\Dto\FolderDto;

/**
 * @property FolderDto resource
 */
class DirectoryResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $folder = $this->resource;

        return [
            'success' => true,
            'time' => now(),
            'code' => 220,
            'data' => [
                'sources' => [
                    $folder->toArray(),
                ],
            ],
        ];
    }
}
