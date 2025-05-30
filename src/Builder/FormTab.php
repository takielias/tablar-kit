<?php

namespace Takielias\TablarKit\Builder;

use Illuminate\Support\Collection;

class FormTab
{
    protected string $title;
    protected Collection $fields;
    protected array $config;

    public function __construct(string $title, array $config = [])
    {
        $this->title = $title;
        $this->fields = collect();
        $this->config = $config;
    }

    public function setFields(Collection $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function render(array $globalConfig = []): string
    {
        return view('tablar-kit::form-builder.layouts.tab', [
            'title' => $this->title,
            'fields' => $this->fields,
            'config' => $this->config,
            'globalConfig' => $globalConfig,
        ])->render();
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'fields' => $this->fields->map(fn($field) => method_exists($field, 'toArray') ? $field->toArray() : [])->toArray(),
            'config' => $this->config,
        ];
    }
}

