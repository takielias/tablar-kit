<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Cards;

class CardButton extends CardBase
{
    public $href;
    public $text;
    public $variant;

    public function render()
    {
        return view('tablar-kit::components.cards.button');
    }
}
