<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Actions;

use TakiElias\TablarKit\Http\Resources\FileActionErrorResource;
use TakiElias\TablarKit\Http\Resources\FolderCreatedResource;
use TakiElias\TablarKit\Validators\DirectoryNestingValidator;

class FolderCreate extends AbstractFileBrowserAction
{
    public static function getActionName(): string
    {
        return 'folderCreate';
    }

    public function handle(): FileBrowserAction
    {
        $path = $this->getPath();

        $directoryNestingValidator = new DirectoryNestingValidator(config('tablar-kit.nesting_limit'));
        if (!$directoryNestingValidator->passes('path', $path)) {
            $this->addError($directoryNestingValidator->message());

            return $this;
        }

        $newFolderPath = $path . DIRECTORY_SEPARATOR . $this->getName();
        $this->fileBrowser->makeDirectory($newFolderPath);

        return $this;
    }

    public function response()
    {
        if ($this->hasErrors()) {
            return FileActionErrorResource::make($this->getErrors());
        }

        return FolderCreatedResource::make([]);
    }
}
