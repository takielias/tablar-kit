<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Takielias\TablarKit\Dto\FileDto;
use Takielias\TablarKit\Dto\FolderDto;
use Takielias\TablarKit\Http\Resources\DirectoryResource;

class Files extends AbstractFileBrowserAction
{
    protected FolderDto $folder;

    public static function getActionName(): string
    {
        return 'files';
    }

    public function handle(): FileBrowserAction
    {
        $path = $this->getPath();

        $this->mapFiles($path);

        return $this;
    }

    protected function mapFiles(string $path): void
    {
        $files = [];

        foreach ($this->fileBrowser->files($path) as $filePath) {
            $files[] = FileDto::byAttributes(
                $this->getAttributesByPath($filePath)
            );
        }

        $this->folder = FolderDto::byParams(
            $this->fileBrowser->getNameByPath($path),
            $this->fileBrowser->getUrl('/'),
            [],
            $files,
            $path);
    }

    protected function getAttributesByPath(string $filePath): array
    {
        return Cache::remember(
            config('tablar-kit.cache.key') . $filePath,
            config('tablar-kit.cache.duration'),
            function () use ($filePath): array {
                return [
                    'fileName' => $this->getNameByFilePath($filePath),
                    'thumb' => !$this->isImage($filePath)
                        ? $this->getThumbByFilePath($filePath)
                        : null,
                    'changed' => $this->getChangedTimeByFilePath($filePath),
                    'size' => $this->getSizeByFilePath($filePath),
                ];
            }
        );
    }

    protected function getNameByFilePath(string $filePath): string
    {
        return $this->fileBrowser->getNameByPath($filePath);
    }

    private function isImage($filePath): bool
    {
        return isImage(
            $this->fileBrowser->getExtension($filePath)
        );
    }

    protected function getThumbByFilePath(string $path): string
    {
        $extension = $this->fileBrowser->getExtension($path);

        $url = config('tablar-kit.thumb.dir_url');

        if (in_array($extension, config('tablar-kit.thumb.exists'))) {
            return $url . sprintf(config('tablar-kit.thumb.mask'), $extension);
        }

        return $url . config('tablar-kit.thumb.unknown_extension');
    }

    protected function getChangedTimeByFilePath(string $filePath): Carbon
    {
        return Carbon::createFromTimestamp(
            $this->fileBrowser->lastModified($filePath)
        );
    }

    protected function getSizeByFilePath(string $filePath): int
    {
        return $this->fileBrowser->size($filePath);
    }

    public function response(): DirectoryResource
    {
        return DirectoryResource::make($this->folder);
    }
}
