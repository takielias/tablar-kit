<?php

namespace Takielias\TablarKit\Fields;

class LitePickerField extends BaseField
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

    public function singleMode(bool $single = true): self
    {
        $this->pickerConfig['singleMode'] = $single;
        return $this;
    }

    public function format(string $format): self
    {
        $this->pickerConfig['format'] = $format;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.lite-picker', [
            'field' => $this,
            'value' => $fieldValue,
            'attributes' => $attributes,
            'config' => $this->pickerConfig,
        ])->render();
    }
}
