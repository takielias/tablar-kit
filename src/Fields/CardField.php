<?php

namespace TakiElias\TablarKit\Fields;

use Illuminate\Support\Collection;
use TakiElias\TablarKit\Components\Cards\Card;

class CardField extends BaseField
{
    protected string $title;
    protected Collection $fields;
    protected ?string $header = null;
    protected ?string $stamp = null;
    protected ?string $ribbon = null;
    protected ?string $footer = null;

    public function __construct(string $name = '', string $label = '', array $config = [])
    {
        parent::__construct($name, $label, $config);
        $this->title = $label ?: $name;
        $this->fields = collect();
    }

    public function setFields(Collection $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function header(string $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function stamp(bool $stamp): self
    {
        $this->stamp = $stamp;
        return $this;
    }

    public function ribbon(bool $ribbon): self
    {
        $this->ribbon = $ribbon;
        return $this;
    }

    public function footer(string $footer): self
    {
        $this->footer = $footer;
        return $this;
    }

    protected function buildComponent(): Card
    {
        return new Card();
    }

    public function render($value = null, array $globalConfig = []): string
    {
        return view('tablar-kit::form-builder.fields.card', [
            'title' => $this->title,
            'fields' => $this->fields,
            'value' => $value,
            'globalConfig' => $globalConfig,
        ])->render();
    }

    /**
     * Static factory method that matches BaseField pattern
     * Note: We override this to maintain compatibility but use label as title
     */
    public static function make(string $name, string $label = '', array $config = []): static
    {
        return new static($name, $label, $config);
    }
}
