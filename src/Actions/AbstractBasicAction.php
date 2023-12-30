<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Http\Resources\FileActionErrorResource;
use Takielias\TablarKit\Services\FileBrowserStorage;

abstract class AbstractBasicAction
{
    protected array $errors = [];

    protected FileBrowserStorage $fileBrowser;

    public function __construct()
    {
        $this->fileBrowser = resolve(FileBrowserStorage::class);
    }

    public function hasErrors(): bool
    {
        return (bool)count($this->errors);
    }

    protected function getPath(): string
    {
        return remove_trailing_slashes(
            $this->dto->getPath()
        );
    }

    protected function addError(string $message): self
    {
        $this->errors[] = $message;

        return $this;
    }

    protected function getErrorResponse(): FileActionErrorResource
    {
        return FileActionErrorResource::make($this->getErrors());
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function checkPathExists(string $path): void
    {
        if ($this->fileBrowser->exists($path)) {
            if (config('tablar-kit.duplicate_file')) {
                $this->fileBrowser->renameIfExist($path);
            } else {
                $this->errors[] = $path . ' - ' . __('is already exists!');
            }
        }
    }
}
