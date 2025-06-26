<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Editors;

use TakiElias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class Jodit extends TablarComponent
{
    /** @var string */
    public string $name;

    /** @var string */
    public string $id;

    /** @var array */
    public array $options;

    public ?string $value = null;

    public function __construct(string $name, string $id = null, array $options = [])
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->options = $options;
    }

    public function render(): View
    {
        return view('tablar-kit::components.editors.jodit');
    }

    public function data(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'options' => $this->options,
            'value' => $this->value ?? '',
        ];
    }
}
