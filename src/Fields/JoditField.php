<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use Takielias\TablarKit\Components\Editors\Jodit;

class JoditField extends BaseField
{
    protected array $editorConfig = [];

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
        parent::__construct($name, $label, $config);
    }

    public function config(array $config): self
    {
        $this->editorConfig = array_merge($this->editorConfig, $config);
        return $this;
    }

    public function height(int $height): self
    {
        $this->editorConfig['height'] = $height;
        return $this;
    }

    public function toolbar(array $buttons): self
    {
        $this->editorConfig['buttons'] = $buttons;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $jodit = new Jodit(
            name: $this->name,
            id: $this->getId(),
            options: $this->editorConfig
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
