<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms;

use Takielias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class Label extends TablarComponent
{
    /** @var string */
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
