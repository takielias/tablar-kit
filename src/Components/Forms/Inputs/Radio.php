<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class Radio extends Input
{
    /** @var bool */
    public bool $checked;

    public function __construct(string $name, string $id = null, bool $checked = false, ?string $value = '')
    {
        parent::__construct($name, $id, 'radio', $value);

        $this->checked = (bool)old($name, $checked);
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.radio');
    }

    public function data(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'value' => $this->value,
            'checked' => $this->checked,
        ];
    }

}
