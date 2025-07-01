<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\View\ComponentAttributeBag;

class FileField extends BaseField
{
    protected array $acceptedTypes = [];
    protected ?int $maxSize = null;
    protected bool $multiple = false;

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
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

    public function acceptedFileTypes(array $types): self
    {
        $this->attributes['accept'] = implode(',', $types);
        return $this;
    }

    public function maxFileSize(string $size): self
    {
        // This is typically handled by validation, not HTML attributes
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $attributes = $this->renderAttributes();

        return view('tablar-kit::form-builder.fields.file', [
            'field' => $this,
            'acceptedTypes' => $this->acceptedTypes,
            'maxSize' => $this->maxSize,
            'multiple' => $this->multiple,
            'attributes' => new ComponentAttributeBag($attributes)
        ])->render();
    }
}

