<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Collection;

class CardField extends BaseField
{
    protected string $title;
    protected Collection $fields;
    protected ?string $header = null;
    protected ?string $stamp = null;
    protected ?string $ribbon = null;
    protected ?string $footer = null;

    public function __construct(string $title = '', array $config = [])
    {
        parent::__construct('', '', $config);
        $this->title = $title;
        $this->fields = collect();
    }

    public function setFields(Collection $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function header(string $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function stamp(bool $stamp): self
    {
        $this->stamp = $stamp;
        return $this;
    }

    public function ribbon(bool $ribbon): self
    {
        $this->ribbon = $ribbon;
        return $this;
    }

    public function footer(string $footer): self
    {
        $this->footer = $footer;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        return view('tablar-kit::form-builder.fields.card', [
            'title' => $this->title,
            'fields' => $this->fields,
            'value' => $value,
            'globalConfig' => $globalConfig,
        ])->render();
    }
}
