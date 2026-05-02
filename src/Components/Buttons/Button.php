<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Buttons;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use TakiElias\TablarKit\Components\TablarComponent;

class Button extends TablarComponent
{
    public string $id;

    public string $type;

    public string $value;

    public function __construct(
        ?string $id = null,
        string $type = 'submit',
        ?string $value = '',
    ) {
        $this->id = $id ?? Str::random();
        $this->type = $type;
        $this->value = $value;
    }

    public function render(): View
    {
        return view('tablar-kit::components.buttons.button');
    }

    public function getData(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
        ];
    }
}
