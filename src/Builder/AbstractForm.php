<?php

namespace TakiElias\TablarKit\Builder;

use TakiElias\TablarKit\Fields\CardField;

abstract class AbstractForm
{
    protected FormBuilder $form;
    protected array $data = [];
    protected array $rules = [];
    protected array $messages = [];

    public function __construct()
    {
        $this->form = FormBuilder::create();
        $this->configure($this->form);
        $this->buildFields();
    }

    /**
     * Configure the form (action, method, attributes, etc.)
     */
    abstract protected function configure(FormBuilder $form): void;

    /**
     * Define the form fields
     */
    abstract protected function fields(): array;

    /**
     * Build fields from the fields() method
     */
    protected function buildFields(): void
    {
        foreach ($this->fields() as $field) {
            if ($field instanceof CardField) {
                $this->form->enableCard();
                $field->setData($this->data);
            }
            $this->form->addField($field);
        }
    }

    /**
     * Set form data
     */
    public function fill(array $data): self
    {
        $this->data = $data;
        $this->form->data($data);
        return $this;
    }

    /**
     * Set validation rules
     */
    public function rules(array $rules): self
    {
        $this->rules = $rules;
        $this->form->rules($rules);
        return $this;
    }

    /**
     * Set validation messages
     */
    public function messages(array $messages): self
    {
        $this->messages = $messages;
        return $this;
    }

    /**
     * Get the form builder instance
     */
    public function getForm(): FormBuilder
    {
        return $this->form;
    }

    /**
     * Get validation rules
     */
    public function getValidationRules(): array
    {
        return array_merge($this->rules, $this->form->getValidationRules());
    }

    /**
     * Get validation messages
     */
    public function getValidationMessages(): array
    {
        return array_merge($this->messages, $this->form->getValidationMessages());
    }

    /**
     * Render the form
     */
    public function render(): string
    {
        return $this->form->render();
    }

    /**
     * Convert to string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Static factory method
     */
    public static function make(): static
    {
        return new static();
    }
}
