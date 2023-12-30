<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Buttons;

use Takielias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class FormButton extends TablarComponent
{
    /** @var string|null */
    public ?string $action;

    /** @var string */
    public string $method;

    public function __construct(string $action = null, string $method = 'POST')
    {
        $this->action = $action;
        $this->method = strtoupper($method);
    }

    public function render(): View
    {
        return view('tablar-kit::components.buttons.form-button');
    }
}
