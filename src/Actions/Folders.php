<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Dto\FolderDto;
use Takielias\TablarKit\Http\Resources\DirectoryResource;

class Folders extends AbstractFileBrowserAction
{

    private FolderDto $folder;

    public static function getActionName(): string
    {
        return 'folders';
    }

    public function handle(): FileBrowserAction
    {
        $path = $this->getPath();

        $this->mapFolders($path);

        return $this;
    }

    private function mapFolders(string $path): void
    {
        $folders = empty($path) ? ['.'] : ['..'];

        foreach ($this->fileBrowser->directories($path) as $directory) {
            $folders[] = $this->fileBrowser->getNameByPath($directory);
        }

        $name = $this->fileBrowser->getNameByPath($path);
        $baseUrl = $this->fileBrowser->getUrl($path);
        $this->folder = FolderDto::byParams($name, $baseUrl, $folders, [], $path);
    }

    public function response(): DirectoryResource
    {
        return DirectoryResource::make($this->folder);
    }
}
