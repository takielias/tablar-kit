<?php

namespace Takielias\TablarKit\Fields;

class FlatPickerField extends BaseField
{
    protected array $pickerConfig = [];

    public function __construct(string $name, string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
    }

    public function config(array $config): self
    {
        $this->pickerConfig = array_merge($this->pickerConfig, $config);
        return $this;
    }

    public function enableTime(bool $enable = true): self
    {
        $this->pickerConfig['enableTime'] = $enable;
        return $this;
    }

    public function dateFormat(string $format): self
    {
        $this->pickerConfig['dateFormat'] = $format;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.flat-picker', [
            'field' => $this,
            'value' => $fieldValue,
            'attributes' => $attributes,
            'config' => $this->pickerConfig,
        ])->render();
    }
}

