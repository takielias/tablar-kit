<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Actions;

use TakiElias\TablarKit\Http\Resources\FileActionErrorResource;

class FolderMove extends AbstractFileBrowserAction
{

    public static function getActionName(): string
    {
        return 'folderMove';
    }

    public function handle(): FileBrowserAction
    {
        return $this->addError(__('Moving directories is not possible!'));
    }

    public function response(): FileActionErrorResource
    {
        return FileActionErrorResource::make($this->getErrors());
    }
}
