<?php

namespace TakiElias\TablarKit\Builder;

use Illuminate\Support\Collection;
use TakiElias\TablarKit\Fields\CardField;

class CardBuilder
{
    protected string $title;
    protected array $config;
    protected FormBuilder $formBuilder;
    protected Collection $fields;

    public function __construct(string $title, array $config, FormBuilder $formBuilder)
    {
        $this->title = $title;
        $this->config = $config;
        $this->formBuilder = $formBuilder;
        $this->formBuilder->enableCard();
        $this->fields = collect();
    }

    public function fields(callable $callback): FormBuilder
    {
        $builder = new FormBuilder();
        $callback($builder);
        $this->fields = $builder->getFields();

        $cardField = new CardField($this->title, $this->title, $this->config);
        $cardField->setFields($this->fields);

        $this->formBuilder->addField($cardField);
        return $this->formBuilder;
    }
}
