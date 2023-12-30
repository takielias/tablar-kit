<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Http\Resources\FileUploadResource;

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
