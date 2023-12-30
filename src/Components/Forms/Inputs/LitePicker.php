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
        string  $format = 'Y-m-d H:i',
        string  $placeholder = null,
        array   $options = []
    )
    {
        $this->id = $id ?? 'id_' . uniqid();

        parent::__construct($name, $id, 'text', $value);

        $this->format = $format;
        $this->placeholder = $placeholder ?? $format;
        $this->options = $options;
    }

    public function options(): array
    {
        return array_merge([
            'dateFormat' => $this->format,
            'altInput' => true,
            'enableTime' => true,
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
}
