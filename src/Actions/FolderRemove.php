<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Http\Resources\SuccessActionResource;

class FolderRemove extends AbstractFileBrowserAction
{

    public static function getActionName(): string
    {
        return 'folderRemove';
    }

    public function handle(): FileBrowserAction
    {
        $path = $this->getPath();
        $removedFolderPath = $path . DIRECTORY_SEPARATOR . $this->getName();
        $this->fileBrowser->removeDirectory($removedFolderPath);

        return $this;
    }

    public function response(): SuccessActionResource
    {
        return SuccessActionResource::make([]);
    }
}
