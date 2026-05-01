<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Buttons;

use Illuminate\Contracts\View\View;
use TakiElias\TablarKit\Components\TablarComponent;

class Logout extends TablarComponent
{
    public string $action;

    public function __construct(?string $action = null)
    {
        $this->action = $action ?? route('logout');
    }

    public function render(): View
    {
        return view('tablar-kit::components.buttons.logout');
    }
}
