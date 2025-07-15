<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Cards;

use TakiElias\TablarKit\Components\TablarComponent;

abstract class CardBase extends TablarComponent
{
    public $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function render()
    {
        return view('tablar-kit::components.cards.' . str_replace('\\', '.', get_class($this)));
    }
}
