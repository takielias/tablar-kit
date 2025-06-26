<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\Input;

class PasswordField extends BaseField
{
    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
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

        $passwordComponent = new Input(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            type: 'password',
            value: $fieldValue
        );

        return View::make($passwordComponent->render()->name(), $passwordComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
