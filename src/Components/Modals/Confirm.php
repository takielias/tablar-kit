<?php

namespace TakiElias\TablarKit\Components\Modals;

use Illuminate\View\Component;
use Illuminate\View\View;

class Confirm extends Component
{
    public function __construct(
        public string $url,
        public string $method = 'POST',
        public string $title = 'Are you sure?',
        public string $message = '',
        public string $button = 'Confirm',
        public string $status = 'danger',
        public ?string $event = null,
        public ?string $redirect = null,
        public bool $reload = true,
        public string $confirmClass = 'btn btn-danger',
    ) {
        $this->method = strtoupper($this->method);
    }

    public function render(): View
    {
        return view('tablar-kit::components.modals.confirm');
    }
}
