<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Collection;

class FormColumn extends BaseField
{
    protected int $width;
    protected Collection $fields;

    public function __construct(int $width, array $config = [])
    {
        $this->width = $width;
        $this->fields = collect();
        parent::__construct('', '', $config);
    }

    public function setFields(Collection $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $columnClass = 'col-md-' . $this->width;

        return view('tablar-kit::form-builder.layouts.column', [
            'fields' => $this->fields,
            'columnClass' => $columnClass,
            'globalConfig' => $globalConfig,
        ])->render();
    }
}

