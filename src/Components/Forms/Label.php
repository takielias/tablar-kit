<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use TakiElias\TablarKit\Components\TablarComponent;

class Label extends TablarComponent
{
    public string $for;

    public function __construct(string $for)
    {
        $this->for = $for;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.label');
    }

    public function fallback(): string
    {
        return Str::ucfirst(str_replace('_', ' ', $this->for));
    }
}
