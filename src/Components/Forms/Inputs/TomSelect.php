<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Forms\Inputs;

use Illuminate\Contracts\View\View;

class TomSelect extends Input
{
    /** @var array */
    public array $options;

    public ?string $placeholder;

    public ?string $itemSearchRoute;
    public ?string $valueField;
    public ?string $searchField;
    public ?string $labelField;
    public ?string $sortField;
    public bool $remoteData = false;
    public ?bool $hideSelected = false;
    public ?bool $duplicates = true;

    public ?bool $create = false;
    public ?bool $allowEmptyOption = false;
    public ?bool $persist = false;
    public ?bool $createOnBlur = false;
    public int|null $maxItems = 3;

    public function __construct(
        string   $name,
        string   $id = null,
        ?string  $value = '',
        array    $options = [],
        ?string  $placeholder = null,
        bool     $remoteData = false,
        string   $itemSearchRoute = null,
        bool     $create = false,
        int|null $maxItems = null,
        string   $valueField = null,
        string   $searchField = null,
        string   $labelField = null,
        string   $sortField = null,
        bool     $hideSelected = false,
        bool     $allowEmptyOption = false,
        bool     $createOnBlur = false,
    )
    {
        $this->id = $id ?? 'id_' . uniqid();

        parent::__construct($name, $id, 'select', $value);

        $this->options = $options;
        $this->itemSearchRoute = $itemSearchRoute;
        $this->remoteData = $remoteData;
        $this->placeholder = $placeholder;
        $this->create = $create;
        $this->maxItems = $maxItems;
        $this->valueField = $valueField;
        $this->searchField = $searchField;
        $this->labelField = $labelField;
        $this->sortField = $sortField;
        $this->hideSelected = $hideSelected;
        $this->allowEmptyOption = $allowEmptyOption;
        $this->createOnBlur = $createOnBlur;

    }

    public function render(): View
    {
        $dynamicProperties = [
            'valueField' => $this->valueField ?? 'item_id',
            'labelField' => $this->labelField ?? 'item_name',
            'searchField' => $this->searchField ?? 'item_name',
            'persist' => $this->persist ?? false,
            'placeholder' => $this->placeholder ?? 'Select',
            'createOnBlur' => $this->createOnBlur ?? null,
            'create' => $this->create ?? null,
            'allowEmptyOption' => $this->allowEmptyOption ?? null,
            'maxItems' => $this->maxItems ?? null,
            // add other properties as needed
        ];

        $tomSelectOptions = [];
        foreach ($dynamicProperties as $key => $value) {
            if (!is_null($value) && $value !== '') {
                $tomSelectOptions[$key] = $value;
            }
        }

        return view('tablar-kit::components.forms.inputs.tom-select', compact('tomSelectOptions'));
    }


    public function options(): array
    {
        return $this->options;
    }

}
