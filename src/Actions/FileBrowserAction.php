<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Actions;

interface FileBrowserAction
{
    public static function getActionName(): string;

    public function handle(): self;

    public function response();
}
