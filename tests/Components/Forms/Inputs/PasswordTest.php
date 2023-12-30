<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class PasswordTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $this->assertComponentRenders(
            '<input name="password" type="password" id="password" class="form-control" />',
            '<x-password id="password"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $this->assertComponentRenders(
            '<input name="confirm_password" type="password" id="confirmPassword" class="form-control p-4" />',
            '<x-password name="confirm_password" id="confirmPassword" class="p-4" />',
        );
    }

    /** @test */
    public function inputs_cannot_have_old_values()
    {
        $this->flashOld(['password' => 'secret']);

        $this->assertComponentRenders(
            '<input name="password" type="password" id="password" class="form-control" />',
            '<x-password id=password />',
        );
    }
}
