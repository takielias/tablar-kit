<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Editors\Jodit;
use TakiElias\TablarKit\Traits\FieldTrait;

class JoditField extends BaseField
{
    use FieldTrait;

    protected array $options = [];

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

    public function height(int $height): self
    {
        $this->options['height'] = $height;
        return $this;
    }

    public function toolbar(array $buttons): self
    {
        $this->options['buttons'] = $buttons;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $jodit = new Jodit(
            name: $this->name,
            id: $this->getId(),
            options: $this->options
        );

        return View::make($jodit->render()->name(), $jodit->data())
            ->with([
                'value' => $fieldValue,
                'slot' => $fieldValue,
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
