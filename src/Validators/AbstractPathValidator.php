<?php

namespace Takielias\TablarKit\Validators;

use Illuminate\Contracts\Validation\ValidationRule as Rule;

abstract class AbstractPathValidator implements Rule
{
    protected function getDirNesting(string $value): int
    {
        $dirs = 0;

        if (preg_match_all('/\/?(?<dir>[^.^\/]+)\/?/', $value, $match)) {
            $dirs = count($match['dir']);
        }

        return $dirs;
    }
}
