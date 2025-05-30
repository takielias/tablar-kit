<?php

namespace Takielias\TablarKit\Fields;

class JoditField extends BaseField
{
    protected array $editorConfig = [];

    public function __construct(string $name, string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
    }

    public function config(array $config): self
    {
        $this->editorConfig = array_merge($this->editorConfig, $config);
        return $this;
    }

    public function height(int $height): self
    {
        $this->editorConfig['height'] = $height;
        return $this;
    }

    public function toolbar(array $buttons): self
    {
        $this->editorConfig['buttons'] = $buttons;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.jodit', [
            'field' => $this,
            'value' => $fieldValue,
            'attributes' => $attributes,
            'config' => $this->editorConfig,
        ])->render();
    }
}
