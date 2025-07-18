<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\View\ComponentAttributeBag;

class HiddenField extends BaseField
{
    public function __construct(string $name, $value = '', array $config = [])
    {
        parent::__construct($name, '', $config);
        $this->value = $value;
        $this->label = null;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.hidden', [
            'field' => $this,
            'value' => $fieldValue,
            'attributes' => new ComponentAttributeBag($attributes)
        ])->render();
    }
}

