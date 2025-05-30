<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class LitePicker extends Input
{
    /** @var string */
    public string $format;

    /** @var string */
    public string $placeholder;

    /** @var array */
    public array $options;

    public function __construct(
        string  $name,
        string  $id = null,
        ?string $value = '',
        string  $format = 'YYYY-MM-DD',
        string  $placeholder = null,
        array   $options = []
    )
    {
        $this->id = $id ?? 'id_' . uniqid();

        parent::__construct($name, $this->id, 'text', $value);

        $this->format = $format;
        $this->placeholder = $placeholder ?? $format;
        $this->options = $options;
    }

    public function options(): array
    {
        return array_merge([
            'format' => $this->format,
        ], $this->options);
    }

    public function jsonOptions(): string
    {
        if (empty($this->options())) {
            return '';
        }

        return json_encode((object)$this->options());
    }

    public function render(): View
    {
        return view('tablar-kit::components.forms.inputs.lite-picker');
    }

    public function data(): array
    {
        return [
            'name' => $this->name,
            'id' => $this->id,
            'value' => $this->value,
            'format' => $this->format,
            'placeholder' => $this->placeholder,
            'options' => $this->options,
            'jsonOptions' => $this->jsonOptions(),
        ];
    }

}
