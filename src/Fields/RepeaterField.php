<?php

namespace Takielias\TablarKit\Fields;

class RepeaterField extends BaseField
{
    protected $callback;
    protected array $items = [];
    protected int $minItems = 0;
    protected ?int $maxItems = null;
    protected string $addButtonText = 'Add Item';
    protected string $removeButtonText = 'Remove';
    protected bool $sortable = false;

    public function __construct(string $name, callable $callback, array $config = [])
    {
        parent::__construct($name, '', $config);
        $this->callback = $callback;
    }

    public function items(array $items): self
    {
        $this->items = $items;
        return $this;
    }

    public function minItems(int $min): self
    {
        $this->minItems = $min;
        return $this;
    }

    public function maxItems(int $max): self
    {
        $this->maxItems = $max;
        return $this;
    }

    public function addButtonText(string $text): self
    {
        $this->addButtonText = $text;
        return $this;
    }

    public function removeButtonText(string $text): self
    {
        $this->removeButtonText = $text;
        return $this;
    }

    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $items = $value ?? $this->items ?? [];

        return view('tablar-kit::form-builder.fields.repeater', [
            'field' => $this,
            'items' => $items,
            'callback' => $this->callback,
            'minItems' => $this->minItems,
            'maxItems' => $this->maxItems,
            'addButtonText' => $this->addButtonText,
            'removeButtonText' => $this->removeButtonText,
            'sortable' => $this->sortable,
            'globalConfig' => $globalConfig,
        ])->render();
    }
}
