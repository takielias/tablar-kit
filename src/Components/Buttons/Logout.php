<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Buttons;

use TakiElias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class Logout extends TablarComponent
{
    /** @var string */
    public string $action;

    public function __construct(string $action = null)
    {
        $this->action = $action ?? route('logout');
    }

    public function render(): View
    {
        return view('tablar-kit::components.buttons.logout');
    }
}
