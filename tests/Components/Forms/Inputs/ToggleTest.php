<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class ToggleTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $template = <<<'HTML'
            <label class="form-check form-switch">
                <input name="toogle" class="form-check-input" type="checkbox" id="toogle">
                <span class="form-check-label">Toogle</span>

            </label>
            HTML;

        $this->assertComponentRenders(
            $template,
            '<x-toggle id="toogle" label="Toogle" name="toogle"/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_added()
    {
        $template = <<<'HTML'
            <label class="form-check form-switch">
                <input name="toogle" class="form-check-input" type="checkbox" checked id="toogle">
                <span class="form-check-label">Toogle</span>

            </label>
            HTML;

        $this->assertComponentRenders(
            $template,
            '<x-toggle id="toogle" label="Toogle" name="toogle" checked/>',
        );
    }

    /** @test */
    public function specific_attributes_can_be_overwritten()
    {
        $template = <<<'HTML'
            <label class="form-check form-switch">
                <input name="toogle" class="form-check-input p-4" type="checkbox" id="toogle">
                <span class="form-check-label">Toogle</span>

            </label>
            HTML;

        $this->assertComponentRenders(
            $template,
            '<x-toggle id="toogle" class="p-4" label="Toogle" name="toogle"/>',
        );
    }
}
