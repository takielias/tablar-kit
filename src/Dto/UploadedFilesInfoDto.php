<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Dto;

final class UploadedFilesInfoDto
{
    private string $url;
    private array $files;

    private function __construct()
    {
    }

    public static function byDirUrlAndFiles(string $url, array $files): self
    {
        $self = new self();
        $self->url = $url;
        $self->files = $files;
        return $self;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return UploadedFileDto[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
