<?php

namespace Takielias\TablarKit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionsResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'success' => true,
            'time' => now(),
            'code' => 220,
            'data' => [
                'permissions' => [
                    'allowFiles' => true,
                    'allowFileMove' => true,
                    'allowFileUpload' => true,
                    'allowFileUploadRemote' => true,
                    'allowFileRemove' => true,
                    'allowFileRename' => true,
                    'allowFolders' => true,
                    'allowFolderMove' => true,
                    'allowFolderCreate' => true,
                    'allowFolderRemove' => true,
                    'allowFolderRename' => true,
                    'allowImageResize' => true,
                    'allowImageCrop' => true
                ],
            ],
        ];
    }
}
