<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class SelectTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $this->assertComponentRenders(
            '<select name="select" class="form-select" id="select"></select>',
            '<x-select id="select" name="select" :options="[]"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $this->assertComponentRenders(
            '<select name="select" class="form-select p-4" id="select"></select>',
            '<x-select id="select" name="select" :options="[]" class="p-4"/>',
        );
    }
}
