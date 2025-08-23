<?php

namespace TakiElias\TablarKit\Builder;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use TakiElias\TablarKit\Fields\BaseField;
use TakiElias\TablarKit\Fields\ButtonField;
use TakiElias\TablarKit\Fields\CheckboxField;
use TakiElias\TablarKit\Fields\DependentSelectField;
use TakiElias\TablarKit\Fields\FileField;
use TakiElias\TablarKit\Fields\FilepondField;
use TakiElias\TablarKit\Fields\FlatPickerField;
use TakiElias\TablarKit\Fields\FormButtonField;
use TakiElias\TablarKit\Fields\FormColumn;
use TakiElias\TablarKit\Fields\FormRow;
use TakiElias\TablarKit\Fields\HiddenField;
use TakiElias\TablarKit\Fields\InputField;
use TakiElias\TablarKit\Fields\JoditField;
use TakiElias\TablarKit\Fields\LitePickerField;
use TakiElias\TablarKit\Fields\PasswordField;
use TakiElias\TablarKit\Fields\RadioField;
use TakiElias\TablarKit\Fields\RepeaterField;
use TakiElias\TablarKit\Fields\SelectField;
use TakiElias\TablarKit\Fields\TextareaField;
use TakiElias\TablarKit\Fields\ToggleField;
use TakiElias\TablarKit\Fields\TomSelectField;

class FormBuilder
{
    protected Collection $fields;
    protected array $attributes;
    protected array $data;
    protected string $method;
    protected ?string $action;
    protected bool $multipart;
    protected bool $hasCard = false;
    protected array $sections;
    protected array $tabs;
    protected string $theme;
    protected array $globalConfig;
    protected array $customComponents;
    protected array $validationRules;
    protected array $conditionalRules;

    public function __construct()
    {
        $this->fields = collect();
        $this->attributes = ['class' => 'tablar-form space-y'];
        $this->data = [];
        $this->method = 'POST';
        $this->action = null;
        $this->multipart = false;
        $this->sections = [];
        $this->tabs = [];
        $this->theme = 'default';
        $this->globalConfig = [];
        $this->customComponents = [];
        $this->validationRules = [];
        $this->conditionalRules = [];
    }

    public static function create(): self
    {
        return new static();
    }

    public function action(string $action): self
    {
        $this->action = $action;
        return $this;
    }

    public function setFields(Collection $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function method(string $method): self
    {
        $this->method = strtoupper($method);
        return $this;
    }

    public function multipart(bool $multipart = true): self
    {
        $this->multipart = $multipart;
        return $this;
    }

    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    public function data(array $data): self
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    public function theme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    public function config(array $config): self
    {
        $this->globalConfig = array_merge($this->globalConfig, $config);
        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'placeholder')) {
            $lastField->placeholder($placeholder);
        }

        return $this;
    }

    public function required(bool $required = true): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'required')) {
            $lastField->required($required);
        }

        return $this;
    }

    public function type(string $type): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'type')) {
            $lastField->type($type);
        }

        return $this;
    }

    public function checked(bool $checked = true): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'checked')) {
            $lastField->checked($checked);
        }

        return $this;
    }

    public function confirmation(): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'confirmation')) {
            $lastField->confirmation();
        }

        return $this;
    }

    public function remoteData(bool $isEnabled): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'remoteData')) {
            $lastField->remoteData($isEnabled);
        }

        return $this;
    }

    public function enableTime(bool $enable = true): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'enableTime')) {
            $lastField->enableTime($enable);
        }

        return $this;
    }

    public function singleMode(bool $single = true): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'singleMode')) {
            $lastField->singleMode($single);
        }

        return $this;
    }

    public function dateFormat(string $format): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'dateFormat')) {
            $lastField->dateFormat($format);
        }

        return $this;
    }

    public function format(string $format): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'format')) {
            $lastField->format($format);
        }

        return $this;
    }

    public function enableCard(): self
    {
        $this->hasCard = true;
        return $this;
    }

    public function hasCard(): bool
    {
        return $this->hasCard ?? false;
    }

    public function itemSearchRoute(string $route): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'itemSearchRoute')) {
            $lastField->itemSearchRoute($route);
        }

        return $this;
    }

    public function targetDataRoute(string $route): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'targetDataRoute')) {
            $lastField->targetDataRoute($route);
        }

        return $this;
    }

    public function options(array $options): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'options')) {
            $lastField->options($options);
        }

        return $this;
    }

    public function repeater(string $name, callable $callback = null): RepeaterField
    {
        $field = new RepeaterField($name, $callback);
        $this->addField($field);
        return $field;
    }

    public function id(string $id): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'id')) {
            $lastField->id($id);
        }

        return $this;
    }

    public function addClass(string $class): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'addClass')) {
            $lastField->addClass($class);
        }

        return $this;
    }

    public function input(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config));
        return $this;
    }

    public function password(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new PasswordField($name, $label, $config));
        return $this;
    }

    public function checkbox(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new CheckboxField($name, $label, $config));
        return $this;
    }

    public function toggle(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new ToggleField($name, $label, $config));
        return $this;
    }

    public function radio(string $name, array $options = [], string $label = '', array $config = []): self
    {
        $this->addField(new RadioField($name, $options, $label, $config));
        return $this;
    }

    public function button(string $text = 'Click', string $label = 'Button', array $config = []): self
    {
        $this->addField(new ButtonField($text, $label, $config));
        return $this;
    }

    public function formButton(string $text, string $action = '', array $config = []): self
    {
        $this->addField(new FormButtonField($text, $action, $config));
        return $this;
    }

    public function rows(int $rows): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'rows')) {
            $lastField->rows($rows);
        }

        return $this;
    }

    public function cols(int $cols): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'cols')) {
            $lastField->cols($cols);
        }

        return $this;
    }

    public function allowMultiple(bool $allow = true): self
    {
        $lastField = $this->fields->last();
        if ($lastField && method_exists($lastField, 'allowMultiple')) {
            $lastField->allowMultiple($allow);
        }
        return $this;
    }

    public function height(int $height): self
    {
        $lastField = $this->fields->last();
        if ($lastField && method_exists($lastField, 'height')) {
            $lastField->height($height);
        }
        return $this;
    }

    public function toolbar(array $toolbar): self
    {
        $lastField = $this->fields->last();
        if ($lastField && method_exists($lastField, 'toolbar')) {
            $lastField->toolbar($toolbar);
        }
        return $this;
    }

    public function acceptedFileTypes(array $types): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'acceptedFileTypes')) {
            $lastField->acceptedFileTypes($types);
        }

        return $this;
    }

    public function imageEditor(bool $enabled = true): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'imageEditor')) {
            $lastField->imageEditor($enabled);
        }

        return $this;
    }

    public function maxFileSize(string $size): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'maxFileSize')) {
            $lastField->maxFileSize($size);
        }

        return $this;
    }

    public function max($max): self
    {
        $lastField = $this->fields->last();
        if ($lastField && method_exists($lastField, 'max')) {
            $lastField->max($max);
        }
        return $this;
    }

    public function help(string $helpText): self
    {
        $lastField = $this->fields->last();
        if ($lastField && method_exists($lastField, 'help')) {
            $lastField->help($helpText);
        }
        return $this;
    }

    public function min(mixed $min): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'min')) {
            $lastField->min($min);
        }

        return $this;
    }

    public function step(float|int|string $step): self
    {
        $lastField = $this->fields->last();

        if ($lastField && method_exists($lastField, 'step')) {
            $lastField->step($step);
        }

        return $this;
    }

    public function card(string $title = '', array $config = []): CardBuilder
    {
        return new CardBuilder($title, $config, $this);
    }

    public function select(string $name, array $options = [], string $label = '', array $config = []): self
    {
        $selectedField = new SelectField($name, $label, $config);
        $this->addField($selectedField->options($options));
        return $this;
    }

    public function tomSelect(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new TomSelectField($name, $label, $config));
        return $this;
    }

    public function dependentSelect(string $name, string $targetDropdown = '', array $config = []): self
    {
        $this->addField(new DependentSelectField($name, $targetDropdown, $config));
        return $this;
    }

    public function flatPicker(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new FlatPickerField($name, $label, $config));
        return $this;
    }

    public function litePicker(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new LitePickerField($name, $label, $config));
        return $this;
    }

    public function filepond(string $name, string $label = '', array $config = []): self
    {
        $this->multipart = true;
        $this->addField(new FilepondField($name, $label, $config));
        return $this;
    }

    public function editor(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new JoditField($name, $label, $config));
        return $this;
    }

    public function textarea(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new TextareaField($name, $label, $config));
        return $this;
    }

    public function email(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('email')->rules('email');
        return $this;
    }

    public function number(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('number');
        return $this;
    }

    public function url(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('url')->rules('url');
        return $this;
    }

    public function tel(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('tel');
        return $this;
    }

    public function date(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('date');
        return $this;
    }

    public function time(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('time');
        return $this;
    }

    public function datetime(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('datetime-local');
        return $this;
    }

    public function color(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('color');
        return $this;
    }

    public function range(string $name, string $label = '', array $config = []): self
    {
        $this->addField(new InputField($name, $label, $config))->type('range');
        return $this;
    }

    public function file(string $name, string $label = '', array $config = []): self
    {
        $this->multipart = true;
        $this->addField(new FileField($name, $label, $config));
        return $this;
    }

    public function hidden(string $name, $value = '', array $config = []): self
    {
        $this->addField(new HiddenField($name, $value, $config));
        return $this;
    }

    /**
     * Add a custom component that extends BaseField
     *
     * @param string $componentClass Fully qualified class name that extends BaseField
     * @param array $config Configuration array for the component
     * @return BaseField Returns the component instance for method chaining
     * @throws InvalidArgumentException If class doesn't exist or doesn't extend BaseField
     */
    public function setComponent(string $componentClass, array $config = []): BaseField
    {
        // Validate class exists
        if (!class_exists($componentClass)) {
            throw new \InvalidArgumentException("Component class '{$componentClass}' does not exist.");
        }

        // Validate extends BaseField
        if (!is_subclass_of($componentClass, BaseField::class)) {
            throw new \InvalidArgumentException("Component class '{$componentClass}' must extend BaseField.");
        }

        // Create and add component
        $component = new $componentClass($config);
        $this->fields->push($component);

        return $component;
    }

    /**
     * Alternative method that returns FormBuilder for chaining
     */
    public function addComponent(string $componentClass, array $config = []): self
    {
        $this->setComponent($componentClass, $config);
        return $this;
    }

    public function registerComponent(string $name, string $class): self
    {
        $this->customComponents[$name] = $class;
        return $this;
    }

    public function component(string $name, array $config = []): BaseField
    {
        if (!isset($this->customComponents[$name])) {
            throw new \InvalidArgumentException("Component '{$name}' is not registered.");
        }

        $class = $this->customComponents[$name];
        return $this->addField(new $class($config));
    }

    // ===== LAYOUT & ORGANIZATION =====

    public function section(string $title, callable $callback, array $config = []): self
    {
        $section = new FormSection($title, $config);
        $builder = new static();
        $callback($builder);
        $section->setFields($builder->getFields());
        $this->sections[] = $section;
        return $this;
    }

    public function tab(string $title, callable $callback, array $config = []): self
    {
        $tab = new FormTab($title, $config);
        $builder = new static();
        $callback($builder);
        $tab->setFields($builder->getFields());
        $this->tabs[] = $tab;
        return $this;
    }

    public function row(callable $callback, array $config = []): self
    {
        $row = new FormRow($config);
        $builder = new static();
        $callback($builder);
        $row->setFields($builder->getFields());
        $this->addField($row);
        return $this;
    }

    public function column(int $width, callable $callback, array $config = []): self
    {
        $column = new FormColumn($width, $config);
        $builder = new static();
        $callback($builder);
        $column->setFields($builder->getFields());
        $this->addField($column);
        return $this;
    }

    // ===== VALIDATION =====

    public function rules(array $rules): self
    {
        $this->validationRules = array_merge($this->validationRules, $rules);
        return $this;
    }

    public function when(string $field, $value, callable $callback): self
    {
        $conditionalBuilder = new static();
        $callback($conditionalBuilder);

        $this->conditionalRules[] = [
            'field' => $field,
            'value' => $value,
            'builder' => $conditionalBuilder
        ];

        return $this;
    }

    // ===== CONFIGURATION METHODS =====

    public function fromArray(array $config): self
    {
        foreach ($config as $field) {
            $type = $field['type'] ?? 'input';
            $name = $field['name'] ?? '';
            $label = $field['label'] ?? '';
            $fieldConfig = $field['config'] ?? [];
            $options = $field['options'] ?? [];

            if (method_exists($this, $type)) {
                if (in_array($type, ['select', 'radio', 'dependentSelect'])) {
                    $this->$type($name, $options, $label, $fieldConfig);
                } else {
                    $this->$type($name, $label, $fieldConfig);
                }
            } elseif (isset($this->customComponents[$type])) {
                $this->component($type, array_merge(['name' => $name, 'label' => $label], $fieldConfig));
            }
        }

        return $this;
    }

    public function fromJson(string $json): self
    {
        return $this->fromArray(json_decode($json, true));
    }

    public function toArray(): array
    {
        return [
            'fields' => $this->fields->map(fn($field) => method_exists($field, 'toArray') ? $field->toArray() : [])->toArray(),
            'attributes' => $this->attributes,
            'method' => $this->method,
            'action' => $this->action,
            'multipart' => $this->multipart,
            'sections' => array_map(fn($section) => $section->toArray(), $this->sections),
            'tabs' => array_map(fn($tab) => $tab->toArray(), $this->tabs),
            'validation' => $this->validationRules,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function render(): string
    {
        return view('tablar-kit::form-builder.form', [
            'form' => $this,
            'fields' => $this->fields,
            'attributes' => $this->getFormAttributes(),
            'sections' => $this->sections,
            'tabs' => $this->tabs,
            'data' => $this->data,
            'theme' => $this->theme,
            'config' => $this->globalConfig,
        ])->render();
    }

    public function renderField(string $name): string
    {
        $field = $this->fields->first(fn($field) => method_exists($field, 'getName') && $field->getName() === $name);

        if (!$field) {
            throw new \InvalidArgumentException("Field '{$name}' not found.");
        }

        if (method_exists($field, 'render')) {
            return $field->render($this->data[$field->getName()] ?? null, $this->globalConfig);
        }

        return (string)$field;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    // ===== HELPER METHODS =====

    public function addField($field): mixed
    {
        $this->fields->push($field);
        return $field;
    }

    protected function getFormAttributes(): array
    {
        $attributes = $this->attributes;

        if ($this->action) {
            $attributes['action'] = $this->action;
        }

        $attributes['method'] = $this->method;

        if ($this->multipart) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return $attributes;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function value($value): self
    {
        $lastField = $this->fields->last();
        if ($lastField && method_exists($lastField, 'value')) {
            $lastField->value($value);
        }
        return $this;
    }

    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function getValidationRules(): array
    {
        $rules = $this->validationRules;

        foreach ($this->fields as $field) {
            if (method_exists($field, 'getValidationRules')) {
                $fieldRules = $field->getValidationRules();
                if ($fieldRules && method_exists($field, 'getName')) {
                    $rules[$field->getName()] = $fieldRules;
                }
            }
        }

        return $rules;
    }

    public function getValidationMessages(): array
    {
        $messages = [];

        foreach ($this->fields as $field) {
            if (method_exists($field, 'getValidationMessages')) {
                $fieldMessages = $field->getValidationMessages();
                if ($fieldMessages) {
                    $messages = array_merge($messages, $fieldMessages);
                }
            }
        }

        return $messages;
    }

    // ===== FLUENT INTERFACE HELPERS =====

    public function if(bool $condition, callable $callback): self
    {
        if ($condition) {
            $callback($this);
        }
        return $this;
    }

    public function unless(bool $condition, callable $callback): self
    {
        if (!$condition) {
            $callback($this);
        }
        return $this;
    }
}
