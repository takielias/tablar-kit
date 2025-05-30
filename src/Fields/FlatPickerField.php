<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Forms\Inputs\FlatPicker;

class FlatPickerField extends BaseField
{
    protected array $pickerConfig = [];
    protected ?string $placeholder = null;

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

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $flatPickerComponent = new FlatPicker(
            name: $this->name,
            id: $this->getId(),
            value: $fieldValue,
            format: $this->pickerConfig['dateFormat'] ?? 'Y-m-d H:i',
            placeholder: $this->placeholder ?? null,
            options: $this->pickerConfig
        );

        return View::make($flatPickerComponent->render()->name(), $flatPickerComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes),
                'jsonOptions' => $flatPickerComponent->jsonOptions()
            ])
            ->render();
    }
}

