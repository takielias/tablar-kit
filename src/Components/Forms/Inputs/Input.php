<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Support\Str;
use Takielias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;

class Input extends TablarComponent
{
    /** @var string */
    public string $name;

    /** @var string */
    public string $id;

    /** @var string */
    public string $type;

    /** @var string */
    public $value;
    public function __construct(
        string  $name,
        string  $id = null,
        string  $type = 'text',
        ?string $value = '',
    )
    {
        $this->name = $name;
        $this->id = $id ?? Str::random();
        $this->type = $type;
        $this->value = old($name, $value ?? '');
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.input');
    }
}
