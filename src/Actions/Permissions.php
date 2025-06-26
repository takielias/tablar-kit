<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Actions;

use TakiElias\TablarKit\Http\Resources\PermissionsResource;

class Permissions extends AbstractFileBrowserAction
{
    public static function getActionName(): string
    {
        return 'permissions';
    }

    public function handle(): FileBrowserAction
    {
        return $this;
    }

    public function response(): PermissionsResource
    {
        return PermissionsResource::make([]);
    }
}
