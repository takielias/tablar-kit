<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Buttons;

use Illuminate\Support\Str;
use Takielias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class Button extends TablarComponent
{

    /** @var string */
    public string $id;

    /** @var string */
    public string $type;

    /** @var string */
    public string $value;

    public function __construct(
        string  $id = null,
        string  $type = 'submit',
        ?string $value = '',
    )
    {
        $this->id = $id ?? Str::random();
        $this->type = $type;
        $this->value = $value;
    }

    public function render(): View
    {
        return view('tablar-kit::components.buttons.button');
    }
}
