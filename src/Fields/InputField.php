<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Forms\Inputs\Input;

class InputField extends BaseField
{
    protected string $type = 'text';

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }

        parent::__construct($name, $label, $config);
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function maxLength(int $length): self
    {
        $this->attributes['maxlength'] = $length;
        $this->rules("max:{$length}");
        return $this;
    }

    public function minLength(int $length): self
    {
        $this->attributes['minlength'] = $length;
        $this->rules("min:{$length}");
        return $this;
    }


    public function min($min): self
    {
        $this->attributes['min'] = $min;
        $this->rules("min:{$min}");
        return $this;
    }

    public function max($max): self
    {
        $this->attributes['max'] = $max;
        $this->rules("max:{$max}");
        return $this;
    }

    public function step($step): self
    {
        $this->attributes['step'] = $step;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $inputComponent = new Input(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            type: $this->type,
            value: $fieldValue
        );

        return View::make($inputComponent->render()->name(), $inputComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}

