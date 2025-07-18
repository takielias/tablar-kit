<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\Toggle;

class ToggleField extends BaseField
{
    protected int $checkedValue = 1;
    protected int $uncheckedValue = 0;

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
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

        $component = new Toggle(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            checked: $isChecked,
            value: $fieldValue,
            label: $this->label
        );

        return View::make($component->render()->name(), $component->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}

