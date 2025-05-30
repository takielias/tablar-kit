<?php

namespace Takielias\TablarKit\Fields;

class HiddenField extends BaseField
{
    public function __construct(string $name, $value = '', array $config = [])
    {
        parent::__construct($name, '', $config);
        $this->value = $value;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.hidden', [
            'field' => $this,
            'value' => $fieldValue,
            'attributes' => $attributes,
        ])->render();
    }
}

