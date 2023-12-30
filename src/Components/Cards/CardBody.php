<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Cards;

class CardBody extends CardBase
{
    public $body;

    public function render()
    {
        return view('tablar-kit::components.cards.body');
    }
}
