<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\Checkbox;

class CheckboxField extends BaseField
{
    protected $checkedValue = 1;
    protected int $uncheckedValue = 0;
    protected bool $checked = false;

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
        $this->checked = $checked;
        return $this;
    }


    public function getCheckedValue(): int
    {
        return $this->checkedValue;
    }

    public function getUncheckedValue(): int
    {
        return $this->uncheckedValue;
    }

    protected function buildComponent(): Checkbox
    {
        $isChecked = $this->checked;

        return new Checkbox(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            checked: $isChecked,
            value: (string)$this->checkedValue
        );
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $attributes = $this->renderAttributes();
        $checkboxComponent = $this->buildComponent();

        return View::make($checkboxComponent->render()->name(), $checkboxComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
