<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\View\ComponentAttributeBag;

class FormButtonField extends BaseField
{
    protected string $text;
    protected string $action;

    public function __construct(string $text, string $action = '', array $config = [])
    {
        parent::__construct('', '', $config);
        $this->text = $text;
        $this->action = $action;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.form-button', [
            'text' => $this->text,
            'action' => $this->action,
            'attributes' => new ComponentAttributeBag($attributes),
            'config' => $this->config,
        ])->render();

    }
}

