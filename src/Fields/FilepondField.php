<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\FilePond;
use TakiElias\TablarKit\Traits\FieldTrait;

class FilepondField extends BaseField
{
    use FieldTrait;

    protected array $options = [];

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
        parent::__construct($name, $label, $config);
    }

    public function config(array $config): self
    {
        $this->options = array_merge($this->options, $config);
        return $this;
    }

    public function allowMultiple(bool $multiple = true): self
    {
        $this->options['allowMultiple'] = $multiple;
        return $this;
    }

    public function acceptedFileTypes(array $types): self
    {
        $this->options['acceptedFileTypes'] = $types;
        return $this;
    }

    public function maxFileSize(string $size): self
    {
        $this->options['maxFileSize'] = $size;
        return $this;
    }

    public function imageEditor(bool $enable = true): self
    {
        $this->options['allowImageEdit'] = $enable;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $filepondComponent = new FilePond(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            type: 'file',
            chunkUpload: $this->options['chunkUpload'] ?? false,
            imageManipulation: $this->options['allowImageEdit'] ?? true,
            value: $fieldValue
        );

        return View::make($filepondComponent->render()->name(), $filepondComponent->data())
            ->with([
                'config' => $this->options,
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
