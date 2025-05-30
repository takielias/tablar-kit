<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Forms\Inputs\Input;

class PasswordField extends BaseField
{
    public function __construct(string $name, string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
    }

    public function confirmation(): self
    {
        $this->rules('confirmed');
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $component = new Input(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            type: 'password',
            value: $fieldValue
        );

        return View::make($component->render()->name(), $component->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
