<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class RadioTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $template = <<<'HTML'
            <input name="radio" type="radio" id="radio" class="form-check-input" />
            HTML;

        $this->assertComponentRenders(
            $template,
            '<x-radio id="radio" name="radio"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_added()
    {
        $template = <<<'HTML'
            <input name="radio" type="radio" id="radio" checked class="form-check-input" />
            HTML;

        $this->assertComponentRenders(
            $template,
            '<x-radio id="radio" checked name="radio"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $template = <<<'HTML'
            <input name="radio" type="radio" id="radio" class="form-check-input p-4" />
            HTML;

        $this->assertComponentRenders(
            $template,
            '<x-radio id="radio" class="p-4" name="radio"/>',
        );
    }
}
