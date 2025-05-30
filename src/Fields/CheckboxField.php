<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Forms\Inputs\Checkbox;

class CheckboxField extends BaseField
{
    protected $checkedValue = 1;
    protected $uncheckedValue = 0;

    public function __construct(string $name, string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
    }

    public function checkedValue($value): self
    {
        $this->checkedValue = $value;
        return $this;
    }

    public function checked(bool $checked = true): self
    {
        $this->value = $checked ? $this->checkedValue : $this->uncheckedValue;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $value ?? $this->value ?? old($this->name);
        $isChecked = $fieldValue == $this->checkedValue;
        $attributes = $this->renderAttributes();

        $component = new Checkbox(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            checked: $isChecked,
            value: (string) $this->checkedValue
        );

        return View::make($component->render()->name(), $component->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
