<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Editors;

use Illuminate\Contracts\View\View;
use TakiElias\TablarKit\Components\TablarComponent;

class Jodit extends TablarComponent
{
    public string $name;

    public string $id;

    public array $options;

    public ?string $value = null;

    public function __construct(string $name, ?string $id = null, array $options = [])
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->options = $options;
        $this->value = old($name, '');
    }

    public function render(): View
    {
        return view('tablar-kit::components.editors.jodit');
    }

    public function getData(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'options' => $this->options,
            'value' => $this->value,
        ];
    }
}
