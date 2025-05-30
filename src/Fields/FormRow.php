<?php

namespace Takielias\TablarKit\Fields;

use Illuminate\Support\Collection;

class FormRow extends BaseField
{
    protected Collection $fields;
    protected array $columnClasses = [];

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

    public function render($value = null, array $globalConfig = []): string
    {
        return view('tablar-kit::form-builder.layouts.row', [
            'fields' => $this->fields,
            'globalConfig' => $globalConfig,
        ])->render();
    }
}
