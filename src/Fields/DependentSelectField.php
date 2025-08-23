<?php

namespace TakiElias\TablarKit\Fields;


use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\DependentSelect;
use TakiElias\TablarKit\Traits\FieldTrait;

class DependentSelectField extends BaseField
{
    use FieldTrait;
    protected string $targetDropdown;
    protected ?string $targetDataRoute = null;
    protected array $options;
    protected ?string $placeholder = null;


    public function __construct(string $name, string $targetDropdown = '', array $config = [])
    {
        parent::__construct($name, '', $config);
        $this->targetDropdown = $targetDropdown;
        $this->options = [];
    }

    public function targetDropdown(string $target): self
    {
        $this->targetDropdown = $target;
        return $this;
    }

    public function targetDataRoute(string $route): self
    {
        $this->targetDataRoute = $route;
        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $dependentComponent = new DependentSelect(
            name: $this->name,
            targetDropdown: $this->targetDropdown,
            targetDataRoute: $this->targetDataRoute,
            value: $fieldValue,
            options: $this->options,
            placeholder: $this->placeholder ?? ''
        );

        return View::make($dependentComponent->render()->name(), $dependentComponent->data())
            ->with([
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}
