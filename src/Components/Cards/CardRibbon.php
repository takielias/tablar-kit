<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Cards;

class CardRibbon extends CardBase
{
    public $content;
    public $color;
    public $position;

    public function render()
    {
        return view('tablar-kit::components.cards.ribbon');
    }
}
