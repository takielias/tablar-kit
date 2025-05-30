<?php

namespace Takielias\TablarKit\Fields;

class FileField extends BaseField
{
    protected array $acceptedTypes = [];
    protected ?int $maxSize = null;
    protected bool $multiple = false;

    public function __construct(string $name, string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
    }

    public function accept(array $types): self
    {
        $this->acceptedTypes = $types;
        return $this;
    }

    public function maxSize(int $sizeInKb): self
    {
        $this->maxSize = $sizeInKb;
        return $this;
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.file', [
            'field' => $this,
            'attributes' => $attributes,
            'acceptedTypes' => $this->acceptedTypes,
            'maxSize' => $this->maxSize,
            'multiple' => $this->multiple,
        ])->render();
    }
}

