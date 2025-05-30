<?php

namespace Takielias\TablarKit\Components;

class TablarComponentWrapper
{
    protected string $component;
    protected array $config;

    public function __construct(string $component, array $config = [])
    {
        $this->component = $component;
        $this->config = $config;
    }

    public function render(): string
    {
        return view("tablar-kit::components.{$this->component}", $this->config)->render();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function toArray(): array
    {
        return [
            'type' => 'tablar_component',
            'component' => $this->component,
            'config' => $this->config,
        ];
    }
}
