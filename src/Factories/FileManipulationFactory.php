<?php
declare(strict_types=1);

namespace Takielias\TablarKit\Factories;

use Takielias\TablarKit\Actions\FileBrowserAction;
use Takielias\TablarKit\Dto\FileBrowserDto;

class FileManipulationFactory
{
    /**
     * @throws NotFoundActionException
     */
    public function create(FileBrowserDto $dto): FileBrowserAction
    {
        $action = $dto->getAction();

        foreach ($this->getActions() as $actionClass) {
            if ($actionClass::getActionName() === $action) {
                return new $actionClass($dto);
            }
        }

        throw new NotFoundActionException('Not found handler for with file browser action!');
    }

    /**
     * @return FileBrowserAction[]
     */
    private function getActions(): array
    {
        return config('tablar-kit.file_actions');
    }
}
