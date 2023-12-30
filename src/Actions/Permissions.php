<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Actions;

use Takielias\TablarKit\Http\Resources\PermissionsResource;

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
