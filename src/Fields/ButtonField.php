<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Buttons\Button;

class ButtonField extends BaseField
{
    protected string $text;
    protected string $type = 'submit';

    public function __construct(string $text = 'Click', string $label = 'Button', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $text));
        }

        parent::__construct('', $label, $config);
        $this->label = $label;
        $this->text = $text;
    }

    protected function buildComponent(): Button
    {
        return new Button(
            id: $this->attributes['id'] ?? null,
            type: $this->type,
            value: $this->text,
        );
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

    public function render($value = null, array $globalConfig = []): string
    {
        $attributes = $this->renderAttributes();
        $buttonComponent = $this->buildComponent();

        return View::make($buttonComponent->render()->name(), $buttonComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }

}

