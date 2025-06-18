<?php

namespace Takielias\TablarKit\Builder;

use Takielias\TablarKit\Fields\FormColumn;
use Takielias\TablarKit\Fields\FormRow;
use Takielias\TablarKit\Fields\InputField;
use Takielias\TablarKit\Fields\SelectField;
use Takielias\TablarKit\Fields\TextareaField;
use Takielias\TablarKit\Fields\ToggleField;

class RepeaterBuilder
{
    private array $fields = [];
    private string $prefix;
    private int $index;

    public function __construct(string $prefix, int $index)
    {
        $this->prefix = $prefix;
        $this->index = $index;
    }

    public function input(string $name, string $label = ''): InputField
    {
        $field = new InputField("{$this->prefix}[{$this->index}][{$name}]", $label);
        $this->fields[] = $field;
        return $field;
    }

    public function select(string $name, array $options = [], string $label = ''): SelectField
    {
        $field = new SelectField("{$this->prefix}[{$this->index}][{$name}]", $options, $label);
        $this->fields[] = $field;
        return $field;
    }

    public function textarea(string $name, string $label = ''): TextareaField
    {
        $field = new TextareaField("{$this->prefix}[{$this->index}][{$name}]", $label);
        $this->fields[] = $field;
        return $field;
    }

    public function toggle(string $name, string $label = ''): ToggleField
    {
        $field = new ToggleField("{$this->prefix}[{$this->index}][{$name}]", $label);
        $this->fields[] = $field;
        return $field;
    }

    public function row(callable $callback): RepeaterBuilder
    {
        $row = new FormRow();
        $builder = new RepeaterBuilder($this->prefix, $this->index);
        $callback($builder);
        $row->setFields(collect($builder->getFields()));
        $this->fields[] = $row;
        return $this;
    }

    public function column(int $size, callable $callback): RepeaterBuilder
    {
        $column = new FormColumn($size);
        $builder = new RepeaterBuilder($this->prefix, $this->index);
        $callback($builder);
        $column->setFields(collect($builder->getFields()));
        $this->fields[] = $column;
        return $this;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
