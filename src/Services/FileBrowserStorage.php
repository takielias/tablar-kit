<?php

namespace Takielias\TablarKit\Services;

use Takielias\TablarKit\Entities\PathInfo;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\File;
use Exception;

class FileBrowserStorage
{
    private FilesystemManager $fs;

    public function __construct()
    {
        $this->fs = resolve('filesystem');
    }

    public function exists(string $path): bool
    {
        return $this->fs->exists(
            $this->getFullRoot($path)
        );
    }

    public function getFullRoot(?string $path = null): string
    {
        $rootPath = config('tablar-kit.root');

        if (str_starts_with($path, $rootPath)) {
            return remove_trailing_slashes(DIRECTORY_SEPARATOR . $path);
        }
        return $rootPath . DIRECTORY_SEPARATOR . remove_trailing_slashes($path);
    }

    public function hasRootDirectory(): bool
    {
        return $this->fs->exists(
            $this->getFullRoot()
        );
    }

    public function createRootDirectory(): bool
    {
        return $this->fs->makeDirectory(
            $this->getFullRoot()
        );
    }

    /**
     * @param string $path
     * @param string|File $content
     * @param array $options
     * @return bool
     */
    public function put(string $path, File|string $content, array $options = []): bool
    {
        $path = $this->getFullRoot($path);

        return $this->fs->put($path, $content, $options);
    }

    public function copy(string $from, string $to): bool
    {
        return $this->fs->copy(
            $this->getFullRoot($from),
            $this->getFullRoot($to)
        );
    }

    public function moveFile(string $from, string $to): bool
    {
        return $this->fs->move(
            $this->getFullRoot($from),
            $this->getFullRoot($to)
        );
    }

    public function getFileTypeByPath(string $path): string
    {
        return $this->fs->mimeType(
            $this->getFullRoot($path)
        );
    }

    /**
     * @param string $from
     * @param string $to
     * @return bool
     * @throws Exception
     */
    public function moveDirectory(string $from, string $to): bool
    {
        $fromWithRoot = $this->getFullRoot($from);
        $toWithRoot = $this->getFullRoot($to);

        return $this->moveDirectoryRaw($fromWithRoot, $toWithRoot);
    }

    /**
     * @throws Exception
     */
    public function moveDirectoryRaw(string $fullPathFrom, string $fullPathTo): bool
    {
        if ($this->fs->exists($fullPathTo)) {
            throw new Exception('Destination directory is exists!');
        }

        $this->fs->makeDirectory($fullPathTo);

        foreach ($this->fs->files($fullPathFrom) as $file) {
            $fileName = $this->getNameByPath($file);

            $this->fs->move($file, $fullPathTo . DIRECTORY_SEPARATOR . $fileName);
        }

        foreach ($this->fs->directories($fullPathFrom) as $directory) {
            $dirName = $this->getNameByPath($directory);

            $this->moveDirectoryRaw($directory, $fullPathTo . DIRECTORY_SEPARATOR . $dirName);
        }

        $this->fs->deleteDirectory($fullPathFrom);

        return true;
    }

    public function getNameByPath(?string $path): string
    {
        $path = remove_trailing_slashes($path);

        if ($path && preg_match('/\/?(?<name>[^\/]+)$/', $path, $match)) {
            return $match['name'];
        }

        return config('tablar-kit.root_name');
    }

    public function makeDirectory(string $path): bool
    {
        return $this->fs->makeDirectory(
            $this->getFullRoot($path)
        );
    }

    public function removeDirectory(string $paths): bool
    {
        return $this->fs->deleteDirectory(
            $this->getFullRoot($paths)
        );
    }

    public function removeFile(string $paths): bool
    {
        return $this->fs->delete(
            $this->getFullRoot($paths)
        );
    }

    public function lastModified(string $path): int
    {
        return $this->fs->lastModified(
            $this->getFullRoot($path)
        );
    }

    public function size(string $path): int
    {
        return $this->fs->size(
            $this->getFullRoot($path)
        );
    }

    public function files(?string $path = null, bool $recursive = false): array
    {
        $files = $this->fs->files(
            $this->getFullRoot($path),
            $recursive
        );

        return $this->convertPathsFromFullToRelative($files);
    }

    private function convertPathsFromFullToRelative(array $files): array
    {
        return array_map(
            function (string $path) {
                return $this->convertPathFromFullToRelative($path);
            },
            $files
        );
    }

    private function convertPathFromFullToRelative(string $path): string
    {
        return str_replace($this->getFullRoot(), '', $path);
    }

    public function directories(?string $path = null, bool $recursive = false): array
    {
        $directories = $this->fs->directories(
            $this->getFullRoot($path),
            $recursive
        );

        return $this->convertPathsFromFullToRelative($directories);
    }

    public function getUrl(string $path): string
    {
        return $this->fs->url(
            $this->getFullRoot($path)
        );
    }

    public function makeFile(string $path, string $content, array $options = []): bool
    {
        return $this->fs->put(
            $this->getFullRoot($path),
            $content,
            $options
        );
    }

    public function renameIfExist(string $path): string
    {
        $path = $this->renameIfExistsRaw(
            $this->getFullRoot($path)
        );

        return $this->convertPathFromFullToRelative($path);
    }

    private function renameIfExistsRaw(string $path): string
    {
        if ($this->fs->exists($path)) {
            $pathInfo = pathinfo($path);

            $path = $pathInfo['dirname']
            . DIRECTORY_SEPARATOR
            . $pathInfo['filename'] . '1'
            . '.'
            . $pathInfo['extension'] ?? '';

            return $this->renameIfExistsRaw($path);
        }

        return $path;
    }

    public function getFileName(string $path): string
    {
        return PathInfo::byPath($path)->getFileName();
    }

    public function getExtension(string $path): string
    {
        return PathInfo::byPath($path)->getExtension();
    }

}
