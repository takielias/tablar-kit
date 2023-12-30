<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Cards;

use Takielias\TablarKit\Components\TablarComponent;

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
