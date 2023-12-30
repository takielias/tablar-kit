<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class Select extends Input
{
    /** @var array */
    public array $options;
    public ?string $placeholder;

    public function __construct(string $name, string $id = null, ?string $value = '', array $options = [], ?string $placeholder = '')
    {
        parent::__construct($name, $id, 'select', $value);

        $this->options = $options;
        $this->placeholder = $placeholder;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.select');
    }

    public function options(): array
    {
        return $this->options;
    }

}
