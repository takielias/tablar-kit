<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Password;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class PasswordTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Password();

        $this->assertEquals('password', $component->name);
        $this->assertEquals('password', $component->id);
        $this->assertEquals('password', $component->type);
    }

    /** @test */
    public function component_accepts_custom_name_and_id()
    {
        $component = new Password('confirm_password', 'confirmPassword');

        $this->assertEquals('confirm_password', $component->name);
        $this->assertEquals('confirmPassword', $component->id);
    }

    /** @test */
    public function inputs_cannot_have_old_values()
    {
        $this->flashOld(['password' => 'secret']);

        $component = new Password();

        $this->assertEquals('', $component->value);
    }
}
