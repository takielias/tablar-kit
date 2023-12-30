<?php
declare(strict_types=1);

namespace Takielias\TablarKit\Factories;

use Takielias\TablarKit\Actions\FileBrowserAction;
use Takielias\TablarKit\Dto\FileUploadDto;

class FileUploadFactory
{
    /**
     * @throws NotFoundActionException
     */
    public function create(FileUploadDto $dto): FileBrowserAction
    {
        $action = $dto->getAction();

        foreach ($this->getActions() as $actionClass) {
            if ($actionClass::getActionName() === $action) {
                return new $actionClass($dto);
            }
        }

        throw new NotFoundActionException('Not found handler for this file upload action!');
    }

    /**
     * @return FileBrowserAction[]
     */
    private function getActions(): array
    {
        return config('tablar-kit.upload_actions');
    }
}
