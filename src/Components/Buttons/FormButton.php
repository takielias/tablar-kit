<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Buttons;

use Illuminate\Contracts\View\View;
use TakiElias\TablarKit\Components\TablarComponent;

class FormButton extends TablarComponent
{
    public ?string $action;

    public string $method;

    public function __construct(?string $action = null, string $method = 'POST')
    {
        $this->action = $action;
        $this->method = strtoupper($method);
    }

    public function render(): View
    {
        return view('tablar-kit::components.buttons.form-button');
    }
}
