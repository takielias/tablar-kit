<?php

namespace Takielias\TablarKit\Components\Modals;

use Illuminate\View\Component;

class ModalForm extends Component
{
    public $id;
    public $action;
    public $method;
    public $title;

    public function __construct($id = 'modal-form', $action = '', $method = 'POST', $title = '')
    {
        $this->id = $id;
        $this->action = $action;
        $this->method = $method;
        $this->title = $title;
    }

    public function render()
    {
        return view('tablar-kit::components.modals.form');
    }
}
