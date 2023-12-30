<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class InputTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $this->assertComponentRenders(
            '<input name="search" type="text" id="search" class="form-control" />',
            '<x-input id="search" name="search" />',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $this->assertComponentRenders(
            '<input name="confirm_password" type="password" id="confirmPassword" class="form-control p-4" />',
            '<x-input name="confirm_password" id="confirmPassword" type="password" class="p-4" />',
        );
    }

    /** @test */
    public function inputs_can_have_old_values()
    {
        $this->flashOld(['search' => 'Eloquent']);

        $this->assertComponentRenders(
            '<input name="search" type="text" id="search" value="Eloquent" class="form-control" />',
            '<x-input id="search" name="search" />',
        );
    }
}
