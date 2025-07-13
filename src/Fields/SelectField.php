<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\Select;
use TakiElias\TablarKit\Traits\FieldTrait;

class SelectField extends BaseField
{
    use FieldTrait;

    protected array $options;
    protected ?string $placeholder = null;

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
        parent::__construct($name, $label, $config);
        $this->options = [];
    }

    public function options(array $options): self
    {
        $this->options = $options;
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

        $component = new Select(
            name: $this->name,
            value: $fieldValue,
            options: $this->options,
            placeholder: $this->placeholder ?? ''
        );

        return View::make($component->render()->name(), $component->data())
            ->with([
                'placeholder' => $this->placeholder ?? '',
                'options' => $this->options,
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();

    }
}

