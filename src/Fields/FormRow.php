<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Collection;

class FormRow extends BaseField
{
    protected Collection $fields;
    protected array $columns = [];

    public function __construct(array $config = [])
    {
        $this->fields = collect();
        parent::__construct('', '', $config);
    }

    public function setFields(Collection $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function addColumn(FormColumn $column): void
    {
        $this->columns[] = $column;
    }

    public function column(int $size, callable $callback): FormColumn
    {
        $column = new FormColumn($size);
        $callback($column);
        $this->addColumn($column);
        return $column;
    }

    public function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        return view('tablar-kit::form-builder.layouts.row', [
            'fields' => $this->fields,
            'data' => $this->data ?? [],
            'config' => $this->config ?? [],
            'globalConfig' => $globalConfig,
        ])->render();
    }
}
