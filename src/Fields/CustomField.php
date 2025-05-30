<?php

namespace Takielias\TablarKit\Fields;

class CustomField extends BaseField
{
    protected string $component;
    protected array $componentConfig;

    public function __construct(string $component, array $config = [])
    {
        $this->component = $component;
        $this->componentConfig = $config;
        $name = $config['name'] ?? '';
        $label = $config['label'] ?? '';
        parent::__construct($name, $label, $config);
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);

        return view($this->component, [
            'field' => $this,
            'value' => $fieldValue,
            'config' => $this->componentConfig,
            'globalConfig' => $globalConfig,
        ])->render();
    }
}

