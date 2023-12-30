<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Http\Resources\SuccessActionResource;
use Exception;

class FolderRename extends AbstractFileBrowserAction
{

    public static function getActionName(): string
    {
        return 'folderRename';
    }

    /**
     * @return $this|FileBrowserAction
     * @throws Exception
     */
    public function handle(): FileBrowserAction
    {
        $path = $this->getPath();
        $from = $path . DIRECTORY_SEPARATOR . $this->getName();

        $newName = $this->getNewName();
        $to = $path . DIRECTORY_SEPARATOR . $newName;

        $this->checkPathExists($to);

        if ($this->hasErrors()) {
            return $this;
        }

        $this->fileBrowser->moveDirectory($from, $to);

        return $this;
    }

    public function response()
    {
        if ($this->hasErrors()) {
            return $this->getErrorResponse();
        }

        return SuccessActionResource::make([]);
    }
}
