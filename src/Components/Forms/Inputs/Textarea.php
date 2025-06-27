<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class Textarea extends TablarComponent
{
    /** @var string */
    public string $name;

    /** @var string */
    public string $id;

    /** @var int */
    public mixed $rows;

    public function __construct(string $name, string $id = null, $rows = 3)
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->rows = $rows;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.textarea');
    }

    public function getData(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'rows' => $this->rows,
        ];
    }
}
