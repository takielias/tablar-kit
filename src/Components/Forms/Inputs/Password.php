<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class Password extends Input
{
    public function __construct(string $name = 'password', string $id = null)
    {
        parent::__construct($name, $id, 'password');
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.password');
    }
}
