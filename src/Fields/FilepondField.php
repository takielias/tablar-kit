<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Forms\Inputs\FilePond;

class FilepondField extends BaseField
{
    protected array $filepondConfig = [];

    public function __construct(string $name, string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
    }

    public function config(array $config): self
    {
        $this->filepondConfig = array_merge($this->filepondConfig, $config);
        return $this;
    }

    public function allowMultiple(bool $multiple = true): self
    {
        $this->filepondConfig['allowMultiple'] = $multiple;
        return $this;
    }

    public function acceptedFileTypes(array $types): self
    {
        $this->filepondConfig['acceptedFileTypes'] = $types;
        return $this;
    }

    public function maxFileSize(string $size): self
    {
        $this->filepondConfig['maxFileSize'] = $size;
        return $this;
    }

    public function imageEditor(bool $enable = true): self
    {
        $this->filepondConfig['allowImageEdit'] = $enable;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $component = new FilePond(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            type: 'file',
            chunkUpload: $this->filepondConfig['chunkUpload'] ?? false,
            imageManipulation: $this->filepondConfig['allowImageEdit'] ?? true,
            value: $fieldValue
        );

        return View::make($component->render()->name(), $component->data())
            ->with([
                'config' => $this->filepondConfig,
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
