<?php

namespace TakiElias\TablarKit\Fields;

use TakiElias\TablarKit\Traits\FieldTrait;

class EditorField extends BaseField
{
    use FieldTrait;

    protected string $editor = 'tinymce';
    protected array $options = [];

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
        parent::__construct($name, $label, $config);
        $this->attributes['class'] = 'form-control editor';
    }

    public function editor(string $type): self
    {
        $this->editor = $type;
        return $this;
    }

    public function config(array $config): self
    {
        $this->options = array_merge($this->options, $config);
        return $this;
    }

    public function toolbar(array $tools): self
    {
        $this->options['toolbar'] = $tools;
        return $this;
    }

    public function height(int $height): self
    {
        $this->options['height'] = $height;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.editor', [
            'field' => $this,
            'value' => $fieldValue,
            'attributes' => $attributes,
            'editor' => $this->editor,
            'options' => $this->options,
        ])->render();
    }
}

