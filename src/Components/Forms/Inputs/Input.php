<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class Input extends TablarComponent
{
    public string $name;

    public string $id;

    public string $type;

    public ?string $value;

    public function __construct(
        string  $name,
        string  $id = null,
        string  $type = 'text',
        ?string $value = '',
    )
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->type = $type;
        $this->value = old($name, $value ?? '');
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.input');
    }

    protected function getData(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'type' => $this->type,
            'value' => $this->value,
        ];
    }

}
