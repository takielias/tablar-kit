<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Takielias\TablarKit\Fields\BaseField;

class TestableBaseField extends BaseField
{
    public function render($value = null, array $globalConfig = []): string
    {
        return '<input name="' . $this->getName() . '" value="' . $this->getFieldValue($value) . '">';
    }
}
