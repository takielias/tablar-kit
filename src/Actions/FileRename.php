<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Http\Resources\SuccessActionResource;
use Exception;

class FileRename extends AbstractFileBrowserAction
{
    public static function getActionName(): string
    {
        return 'fileRename';
    }

    /**
     * @return $this|FileBrowserAction
     * @throws Exception
     */
    public function handle(): FileBrowserAction
    {
        $path = $this->getPath();
        $from = $path . DIRECTORY_SEPARATOR . $this->getName();

        $fileName = $this->getNewName();
        $to = $path . DIRECTORY_SEPARATOR . $fileName;

        $this->checkPathExists($to);

        if ($this->hasErrors()) {
            return $this;
        }

        $this->fileBrowser->moveFile($from, $to);

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
