<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Facades\View;
use Illuminate\View\ComponentAttributeBag;
use TakiElias\TablarKit\Components\Forms\Inputs\TomSelect;
use TakiElias\TablarKit\Traits\FieldTrait;

class TomSelectField extends BaseField
{
    use FieldTrait;

    protected bool $remoteData = false;
    protected ?string $itemSearchRoute = null;

    public string $id;

    protected array $options = [];
    protected ?string $placeholder = null;
    protected ?string $valueField = null;
    protected ?string $searchField = null;
    protected ?string $labelField = null;
    protected ?string $sortField = null;
    protected ?bool $create = null;
    protected ?int $maxItems = null;
    protected ?bool $hideSelected = null;
    protected ?bool $allowEmptyOption = null;
    protected ?bool $createOnBlur = null;

    public function __construct(string $name, string $label = '', array $config = [])
    {
        if (empty($label)) {
            $label = ucwords(str_replace(['_', '-'], ' ', $name));
        }
        parent::__construct($name, $label, $config);
    }

    public function remoteData(bool $remote = true): self
    {
        $this->remoteData = $remote;
        return $this;
    }

    public function itemSearchRoute(string $route): self
    {
        $this->itemSearchRoute = $route;
        return $this;
    }

    public function render($value = null, array $globalConfig = []): string
    {
        $fieldValue = $this->getFieldValue($value);
        $attributes = $this->renderAttributes();

        $component = new TomSelect(
            name: $this->name,
            value: $fieldValue,
            options: $this->options ?? [],
            placeholder: $this->placeholder,
            remoteData: $this->remoteData,
            itemSearchRoute: $this->itemSearchRoute,
            create: $this->create ?? false,
            maxItems: $this->maxItems,
            valueField: $this->valueField,
            searchField: $this->searchField,
            labelField: $this->labelField,
            sortField: $this->sortField,
            hideSelected: $this->hideSelected ?? false,
            allowEmptyOption: $this->allowEmptyOption ?? false,
            createOnBlur: $this->createOnBlur ?? false,
        );

        // Get the tomSelectOptions from the component's render method
        $componentView = $component->render();
        $tomSelectOptions = $componentView->getData()['tomSelectOptions'] ?? [];

        return View::make($componentView->name(), $component->data())
            ->with([
                'tomSelectOptions' => $tomSelectOptions,
                'attributes' => new ComponentAttributeBag($attributes)
            ])
            ->render();
    }
}

