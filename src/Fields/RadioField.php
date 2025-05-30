<?php

namespace Takielias\TablarKit\Fields;

class RadioField extends BaseField
{
    protected array $options;

    public function __construct(string $name, array $options = [], string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
        $this->options = $options;
    }

    public function options(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $value ?? $this->value ?? old($this->name);
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.radio', [
            'field' => $this,
            'value' => $fieldValue,
            'attributes' => $attributes,
            'options' => $this->options,
        ])->render();
    }
}
