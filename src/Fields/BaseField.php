<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Str;
use TakiElias\TablarKit\Components\TablarComponent;

abstract class BaseField extends TablarComponent
{
    protected string $name;
    protected ?string $label;
    protected array $config;
    protected $value;
    protected array $validationRules;
    protected array $validationMessages;
    protected bool $required;
    protected ?string $help;
    protected ?string $topGap;
    protected ?string $placeholder;
    protected array $conditions;
    protected ?int $columnWidth = null;

    protected $max = null;

    public function __construct(string $name = '', string $label = '', array $config = [])
    {
        $this->name = $name;
        $this->label = $label ?: Str::title(str_replace('_', ' ', $name));
        $this->attributes = [];
        $this->config = $config;
        $this->value = null;
        $this->validationRules = [];
        $this->validationMessages = [];
        $this->required = false;
        $this->help = null;
        $this->topGap = null;
        $this->placeholder = null;
        $this->conditions = [];

        $this->initializeFromConfig();
    }

    protected function buildComponent(): mixed
    {
        return null;
    }

    protected function initializeFromConfig(): void
    {
        if (!empty($this->config)) {
            $this->label = $this->config['label'] ?? $this->label;
            $this->required = $this->config['required'] ?? false;
            $this->help = $this->config['help'] ?? null;
            $this->topGap = $this->config['topGap'] ?? null;
            $this->placeholder = $this->config['placeholder'] ?? null;
            $this->attributes = array_merge($this->attributes, $this->config['attributes'] ?? []);
            $this->validationRules = $this->config['rules'] ?? [];
            $this->value = $this->config['value'] ?? null;
        }
    }

    // In BaseField class
    public static function make(string $name, string $label = '', array $config = []): static
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }

        return new static($name, $label, $config);
    }

    /**
     * Set a single attribute
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function setAttribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * Set multiple attributes at once
     *
     * @param array $attributes
     * @return self
     */
    public function setAttributes(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    // Fluent interface methods
    public function label(string|null $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function hasLabel(): bool
    {
        return $this->label ?? false;
    }

    public function required(bool $required = true): self
    {
        $this->required = $required;
        if ($required && !in_array('required', $this->validationRules)) {
            $this->validationRules[] = 'required';
        }
        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        $this->attributes['placeholder'] = $placeholder;
        return $this;
    }

    public function help(string $help): self
    {
        $this->help = $help;
        return $this;
    }

    public function value($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    public function addClass(string $class): self
    {
        $currentClass = $this->attributes['class'] ?? '';
        $this->attributes['class'] = trim($currentClass . ' ' . $class);
        return $this;
    }

    public function id(string $id): self
    {
        $this->attributes['id'] = $id;
        return $this;
    }

    public function disabled(bool $disabled = true): self
    {
        if ($disabled) {
            $this->attributes['disabled'] = 'disabled';
        } else {
            unset($this->attributes['disabled']);
        }
        return $this;
    }

    public function readonly(bool $readonly = true): self
    {
        if ($readonly) {
            $this->attributes['readonly'] = 'readonly';
        } else {
            unset($this->attributes['readonly']);
        }
        return $this;
    }

    public function rules($rules): self
    {
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }
        $this->validationRules = array_merge($this->validationRules, (array)$rules);
        return $this;
    }

    // Getter methods
    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string|null
    {
        return $this->label;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getValidationRules(): array
    {
        return $this->validationRules;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    abstract public function render($value = null, array $globalConfig = []): string;

    public function getHelp(): ?string
    {
        return $this->help ?? null;
    }

    public function setTopGap(string $topGap): self
    {
        $this->topGap = $topGap;
        return $this;
    }

    public function getTopGap(): string
    {
        return $this->topGap ?? 'mb-3';
    }

    public function renderAttributes(): array
    {
        $attributes = $this->attributes;
        $attributes['name'] = $this->name;

        if ($this->required) {
            $attributes['required'] = 'required';
        }

        return $attributes;
    }

    public function getId(): string
    {
        return $this->id ?? $this->name . '_' . uniqid();
    }

    public function getFieldValue($value = null): string
    {
        $fieldValue = $value ?? $this->value ?? old($this->name) ?? '';
        return is_array($fieldValue) ? '' : (string)$fieldValue;
    }

    public function getColumnWidth(): ?int
    {
        return $this->columnWidth ?? null;
    }

    public function columnWidth(int $width): self
    {
        $this->columnWidth = $width;
        return $this;
    }

    public function max($max): self
    {
        $this->max = $max;
        return $this;
    }

    public function getValidationMessages(): array
    {
        return [];
    }

    public function toArray(): array
    {
        return [
            'type' => class_basename(static::class),
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'attributes' => $this->attributes,
            'required' => $this->required,
            'help' => $this->help,
            'placeholder' => $this->placeholder,
            'validation' => $this->validationRules,
            'config' => $this->config,
        ];
    }
}

