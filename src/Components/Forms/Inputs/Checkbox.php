<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class Checkbox extends Input
{
    /** @var bool */
    public bool $checked;

    public function __construct(string $name, string $id = null, bool $checked = false, ?string $value = '')
    {
        parent::__construct($name, $id, 'checkbox', $value);

        $this->checked = (bool) old($name, $checked);
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.checkbox');
    }
}
