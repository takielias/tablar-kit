<?php

declare(strict_types=1);

namespace Takielias\TablarKit\Components\Alerts;

use Takielias\TablarKit\Components\TablarComponent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;

class Alert extends TablarComponent
{
    /** @var string */
    public string $type;

    public function __construct(string $type = 'alert')
    {
        $this->type = $type;
    }

    public function render(): View
    {
        return view('tablar-kit::components.alerts.alert');
    }

    public function message(): string
    {
        return (string) Arr::first($this->messages());
    }

    public function messages(): array
    {
        return (array) session()->get($this->type);
    }

    public function exists(): bool
    {
        return session()->has($this->type) && ! empty($this->messages());
    }
}
