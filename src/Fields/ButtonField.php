<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Buttons\Button;

class ButtonField extends BaseField
{
    protected string $text;
    protected string $type = 'submit';

    public function __construct(string $text = 'Click', array $config = [])
    {
        parent::__construct('', '', $config);
        $this->text = $text;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $attributes = $this->renderAttributes();

        $buttonComponent = new Button(
            id: $this->attributes['id'] ?? null,
            type: $this->type,
            value: $this->text,
        );

        return View::make($buttonComponent->render()->name(), $buttonComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}

