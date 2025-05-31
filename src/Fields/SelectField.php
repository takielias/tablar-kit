<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Forms\Inputs\Select;

class SelectField extends BaseField
{
    protected array $options;
    protected ?string $placeholder = null;

    public function __construct(string $name, array $options = [], string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
        parent::__construct($name, $label, $config);
        $this->options = $options;
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

