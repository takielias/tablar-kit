<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components;

use Illuminate\View\Component as BladeComponent;

abstract class TablarComponent extends BladeComponent
{
    public function data()
    {
        return array_merge(parent::data(), $this->getData());
    }

    protected function getData(): array
    {
        return [];
    }
}
