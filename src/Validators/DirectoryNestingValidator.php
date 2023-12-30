<?php

namespace Takielias\TablarKit\Validators;

use Closure;

class DirectoryNestingValidator extends AbstractPathValidator
{
    private int $maxNesting;

    public function __construct(int $maxNesting)
    {
        $this->maxNesting = $maxNesting;
    }

    public function passes($attribute, $value): bool
    {
        return $this->maxNesting > $this->getDirNesting($value);
    }

    public function message()
    {
        return __('Maximum directory nesting is :count', ['count' => $this->maxNesting]);
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->maxNesting <= $this->getDirNesting($value)) {
            $fail("The {$attribute} exceeds the maximum allowed directory nesting level.");
        }
    }

}
