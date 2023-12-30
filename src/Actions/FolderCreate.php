<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Http\Resources\FileActionErrorResource;
use Takielias\TablarKit\Http\Resources\FolderCreatedResource;
use Takielias\TablarKit\Validators\DirectoryNestingValidator;

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
