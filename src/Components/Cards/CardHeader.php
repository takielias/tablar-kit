<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Cards;

class CardHeader extends CardBase
{
    public $title;

    public function render()
    {
        return view('tablar-kit::components.cards.header');
    }
}
