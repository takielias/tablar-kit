<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\LitePicker;

class LitePickerField extends BaseField
{
    protected array $pickerConfig = [];
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
        $this->pickerConfig = array_merge($this->pickerConfig, $config);
        return $this;
    }

    public function singleMode(bool $single = true): self
    {
        $this->pickerConfig['singleMode'] = $single;
        return $this;
    }

    public function format(string $format): self
    {
        $this->pickerConfig['format'] = $format;
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
            format: $this->pickerConfig['format'] ?? 'YYYY-MM-DD',
            placeholder: $this->placeholder ?? null,
            options: $this->pickerConfig
        );

        return View::make($litePickerComponent->render()->name(), $litePickerComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes),
                'jsonOptions' => $litePickerComponent->jsonOptions()
            ])
            ->render();
    }
}
