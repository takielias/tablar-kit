<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class FilePondTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $this->assertComponentRenders(
            '<input name="file" type=file id="file" class="form-control" />',
            ' <x-filepond id="file" name="file"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $this->assertComponentRenders(
            '<input name="file" type=file id="file" class="form-control p-4" />',
            ' <x-filepond id="file" class="p-4" name="file"/>',
        );
    }

}
