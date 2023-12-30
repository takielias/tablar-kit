<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class CheckboxTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $this->assertComponentRenders(
            '<input name="remember_me" type="checkbox" id="remember_me" class="form-check-input" />',
            '<x-checkbox id="remember_me" name="remember_me"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $this->assertComponentRenders(
            '<input name="remember_me" type="checkbox" id="rememberMe" class="form-check-input p-4" />',
            '<x-checkbox name="remember_me" id="rememberMe" class="p-4" />',
        );
    }

    /** @test */
    public function inputs_can_have_old_values()
    {
        $this->flashOld(['remember_me' => true]);

        $this->assertComponentRenders(
            '<input name="remember_me" type="checkbox" id="remember_me" value="1" checked class="form-check-input" />',
            '<x-checkbox id="remember_me" name="remember_me"/>',
        );
    }
}
