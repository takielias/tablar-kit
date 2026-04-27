<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Components\Forms;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;
use TakiElias\TablarKit\Components\TablarComponent;

class Error extends TablarComponent
{
    public string $field;

    public string $bag;

    public function __construct(string $field, string $bag = 'default')
    {
        $this->field = $field;
        $this->bag = $bag;
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.error');
    }

    public function messages(ViewErrorBag $errors): array
    {
        $bag = $errors->getBag($this->bag);

        return $bag->has($this->field) ? $bag->get($this->field) : [];
    }
}
