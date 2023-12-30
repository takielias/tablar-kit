<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

interface FileBrowserAction
{
    public static function getActionName(): string;

    public function handle(): self;

    public function response();
}
