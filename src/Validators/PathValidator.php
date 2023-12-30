<?php

namespace Takielias\TablarKit\Validators;

use Closure;

class PathValidator extends AbstractPathValidator
{
    public function passes($attribute, $value): bool
    {
        $backs = substr_count($value, '..');

        $dirs = $this->getDirNesting($value);

        return $dirs >= $backs;
    }

    public function message(): string
    {
        return ':attribute - error! ' . __('Can\'t write file below root directory');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $backs = substr_count($value, '..');
        $dirs = $this->getDirNesting($value);

        if ($dirs < $backs) {
            $fail("The {$attribute} has invalid directory nesting.");
        }
    }

}
