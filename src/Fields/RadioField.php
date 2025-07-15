<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\Radio;
use TakiElias\TablarKit\Traits\FieldTrait;

class RadioField extends BaseField
{
    use FieldTrait;

    protected array $options;

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

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $radioButtons = '';
        foreach ($this->options as $optionValue => $optionLabel) {
            $radio = new Radio(
                name: $this->name,
                id: $this->getId() . '_' . $optionValue,
                checked: $fieldValue == $optionValue,
                value: $optionValue
            );

            $radioButtons .= View::make($radio->render()->name(), $radio->data())
                ->with([
                    'label' => $optionLabel,
                    'attributes' => new ComponentAttributeBag($attributes)
                ])
                ->render();
        }

        return $radioButtons;
    }
}
