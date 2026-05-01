<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms;

use Illuminate\Contracts\View\View;
use TakiElias\TablarKit\Components\TablarComponent;

class Form extends TablarComponent
{
    public ?string $action;

    public string $method;

    public bool $hasFiles;

    public function __construct(?string $action = null, string $method = 'POST', bool $hasFiles = false)
    {
        $this->action = $action;
        $this->method = strtoupper($method);
        $this->hasFiles = $hasFiles;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.form');
    }
}
