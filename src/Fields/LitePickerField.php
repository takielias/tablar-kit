<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\LitePicker;
use TakiElias\TablarKit\Traits\FieldTrait;

class LitePickerField extends BaseField
{
    use FieldTrait;

    protected array $options = [];
    protected ?string $placeholder = null;

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

    public function singleMode(bool $single = true): self
    {
        $this->options['singleMode'] = $single;
        return $this;
    }

    public function format(string $format): self
    {
        $this->options['format'] = $format;
        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $litePickerComponent = new LitePicker(
            name: $this->name,
            id: $this->getId(),
            value: $fieldValue,
            format: $this->options['format'] ?? 'YYYY-MM-DD',
            placeholder: $this->placeholder ?? null,
            options: $this->options
        );

        return View::make($litePickerComponent->render()->name(), $litePickerComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes),
                'jsonOptions' => $litePickerComponent->jsonOptions()
            ])
            ->render();
    }
}
