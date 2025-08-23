<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Actions;

use TakiElias\TablarKit\Http\Resources\FileUploadResource;

class FileUploadRemoteAction extends AbstractFileUploadAction
{
    private array $remoteFiles = [];

    public static function getActionName(): string
    {
        return 'fileUploadRemote';
    }

    public function handle(): FileBrowserAction
    {
        return $this;
    }

    public function response(): FileUploadResource
    {
        return FileUploadResource::make($this->remoteFiles);
    }
}
