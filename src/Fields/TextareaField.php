<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Forms\Inputs\Textarea;

class TextareaField extends BaseField
{
    protected int $rows = 3;

    public function __construct(string $name, string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
    }

    public function rows(int $rows): self
    {
        $this->rows = $rows;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $component = new Textarea(
            name: $this->name,
            id: $this->attributes['id'] ?? null,
            rows: $this->rows
        );

        return View::make($component->render()->name(), $component->data())
            ->with([
                'slot' => $fieldValue,
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}

