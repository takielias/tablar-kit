<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class TextareaTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $this->assertComponentRenders(
            '<textarea name="about" id="about" rows="3" class="form-control"></textarea>',
            '<x-textarea name="about"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $this->assertComponentRenders(
            '<textarea name="about" id="aboutMe" rows="5" class="form-control p-4" cols="8">About me text</textarea>',
            '<x-textarea name="about" id="aboutMe" rows="5" cols="8" class="p-4">About me text</x-textarea>',
        );
    }

    /** @test */
    public function inputs_can_have_old_values()
    {
        $this->flashOld(['about' => 'About me text']);

        $this->assertComponentRenders(
            '<textarea name="about" id="about" rows="3" class="form-control">About me text</textarea>',
            '<x-textarea name="about"/>',
        );
    }
}
