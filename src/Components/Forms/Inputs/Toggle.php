<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class Toggle extends Input
{
    /** @var bool */
    public bool $checked;
    public ?string $label = '';

    public function __construct(string $name, string $id = null, bool $checked = false, ?string $value = '', ?string $label = '')
    {
        parent::__construct($name, $id, 'checkbox', $value);

        $this->checked = (bool)old($name, $checked);
        $this->label = $label;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.toggle');
    }

}
