<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\FlatPicker;
use TakiElias\TablarKit\Traits\FieldTrait;

class FlatPickerField extends BaseField
{
    use FieldTrait;

    protected array $options = [];
    protected ?string $placeholder = null;

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

    public function enableTime(bool $enable = true): self
    {
        $this->options['enableTime'] = $enable;
        return $this;
    }

    public function dateFormat(string $format): self
    {
        $this->options['dateFormat'] = $format;
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
            format: $this->options['dateFormat'] ?? 'Y-m-d H:i',
            placeholder: $this->placeholder ?? null,
            options: $this->options
        );

        return View::make($flatPickerComponent->render()->name(), $flatPickerComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes),
                'jsonOptions' => $flatPickerComponent->jsonOptions()
            ])
            ->render();
    }
}

