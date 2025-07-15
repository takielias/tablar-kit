<?php

namespace TakiElias\TablarKit\Traits;

trait FieldTrait
{
    public function setOptions(array $options): self
    {
        foreach ($options as $name => $value) {
            $this->options[$name] = $value;
        }
        return $this;
    }
}
