<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Cards;

class CardFooter extends CardBase
{
    public $children;

    public function render()
    {
        return view('tablar-kit::components.cards.footer');
    }
}
