<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Dto;

use Takielias\TablarKit\Http\Requests\FileBrowserRequest;

final class FileBrowserDto
{
    private string $action;

    private string $source;

    private ?string $path;

    private ?string $from;

    private ?string $name;

    private ?string $newName;

    private function __construct(
        string  $action,
        string  $source,
        ?string $path = null,
        ?string $from = null,
        ?string $name = null,
        ?string $newName = null
    )
    {
        $this->action = $action;
        $this->source = $source;
        $this->path = $path;
        $this->from = $from;
        $this->name = $name;
        $this->newName = $newName;
    }

    public static function byRequest(FileBrowserRequest $request): self
    {
        return new self(
            $request->action,
            $request->source,
            $request->path,
            $request->from,
            $request->name,
            $request->newname
        );
    }

    public static function byParams(
        string  $action,
        string  $source = 'default',
        ?string $path = null,
        ?string $from = null,
        ?string $name = null,
        ?string $newName = null
    ): self
    {
        return new self(
            $action,
            $source,
            $path,
            $from,
            $name,
            $newName
        );
    }

    public function hasName(): bool
    {
        return (bool)$this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getPath(): string
    {
        return $this->path ?? '';
    }

    public function hasPath(): bool
    {
        return (bool)$this->path;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getFrom(): string
    {
        return $this->from;
    }
}
