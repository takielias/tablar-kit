<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Editors;

use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class JoditTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $expected = <<<'HTML'
            <textarea name="editor" id="editor"></textarea>
            HTML;

        $this->assertComponentRenders($expected, '<x-jodit name="editor"/>');
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $this->assertComponentRenders(
            '<textarea name="editor" id="editor" class="p-4"></textarea>',
            '<x-jodit name="editor" class="p-4"/>',
        );
    }
}
