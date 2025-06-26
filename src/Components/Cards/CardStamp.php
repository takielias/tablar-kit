<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Cards;

class CardStamp extends CardBase
{
    public $icon;
    public $color;

    public function render()
    {
        return view('tablar-kit::components.cards.stamp');
    }
}
