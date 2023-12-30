<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Cards;

class Card extends CardBase
{
    public function render()
    {
        return view('tablar-kit::components.cards.card');
    }
}
