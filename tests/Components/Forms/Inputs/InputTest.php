<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Input;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class InputTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Input('search', 'search');

        $this->assertEquals('search', $component->name);
        $this->assertEquals('search', $component->id);
        $this->assertEquals('text', $component->type);
    }

    /** @test */
    public function component_accepts_custom_type()
    {
        $component = new Input('confirm_password', 'confirmPassword', 'password');

        $this->assertEquals('confirm_password', $component->name);
        $this->assertEquals('confirmPassword', $component->id);
        $this->assertEquals('password', $component->type);
    }

    /** @test */
    public function inputs_can_have_old_values()
    {
        $this->flashOld(['search' => 'Eloquent']);

        $component = new Input('search');

        $this->assertEquals('Eloquent', $component->value);
    }
}
