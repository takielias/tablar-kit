<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class LabelTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $expected = <<<'HTML'
            <label for="first_name" class="form-label">
                First name
            </label>
            HTML;

        $this->assertComponentRenders($expected, '<x-label for="first_name"/>');
    }
}
