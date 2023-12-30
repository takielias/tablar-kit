<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class FlatPickerTest extends ComponentTestCase
{
    /** @test */
    public function the_flat_picker_component_can_be_rendered()
    {
        $expected = <<<'HTML'
            <input name="date" type="text" id="flat-picker" placeholder="Y-m-d H:i" class="form-control flatpickr" />
            HTML;

        $this->assertComponentRenders($expected, '<x-flat-picker name="date" id="flat-picker"/>');
    }

}
