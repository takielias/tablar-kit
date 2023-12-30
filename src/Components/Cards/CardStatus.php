<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Cards;

class CardStatus extends CardBase
{
    public $color;
    public $position;

    public function render()
    {
        return view('tablar-kit::components.cards.status');
    }
}
