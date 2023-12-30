<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class LitePickerTest extends ComponentTestCase
{
    /** @test */
    public function the_lite_picker_component_can_be_rendered()
    {
        $expected = <<<'HTML'
            <input name="date" type="text" id="lite-picker" placeholder="Y-m-d H:i" class="form-control litepickr" />
            HTML;

        $this->assertComponentRenders($expected, '<x-lite-picker name="date" id="lite-picker"/>');
    }

}
