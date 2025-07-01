<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms;

use TakiElias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class Form extends TablarComponent
{
    /** @var string|null */
    public ?string $action;

    /** @var string */
    public string $method;

    /** @var bool */
    public bool $hasFiles;

    public function __construct(string $action = null, string $method = 'POST', bool $hasFiles = false)
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
