<?php

namespace TakiElias\TablarKit\Components\Modals;

use Illuminate\View\Component;
use Illuminate\View\View;

class ConfirmModal extends Component
{
    public function render(): View
    {
        return view('tablar-kit::components.modals.confirm-modal');
    }
}
