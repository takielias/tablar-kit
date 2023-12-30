<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class DependentSelect extends Input
{
    /** @var array */
    public array $options;
    public ?string $targetDropdown;
    public ?string $targetDataRoute;
    public ?string $placeholder;

    public function __construct(string $name, string $targetDropdown = null, string $targetDataRoute = null, string $id = null, ?string $value = '', array $options = [], ?string $placeholder = '')
    {
        parent::__construct($name, $id, 'select', $value);

        $this->options = $options;
        $this->targetDropdown = $targetDropdown;
        $this->targetDataRoute = $targetDataRoute;
        $this->placeholder = $placeholder;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.dependent-select');
    }

    public function options(): array
    {
        return $this->options;
    }

}
