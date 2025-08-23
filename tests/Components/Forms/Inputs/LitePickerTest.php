<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\LitePicker;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class LitePickerTest extends ComponentTestCase
{
    /** @test */
    public function the_lite_picker_component_can_be_rendered()
    {
        $component = new LitePicker('date', 'lite-picker');

        $this->assertEquals('date', $component->name);
        $this->assertEquals('lite-picker', $component->id);
        $this->assertEquals('YYYY-MM-DD', $component->format);
        $this->assertEquals('YYYY-MM-DD', $component->placeholder);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new LitePicker('test', 'test-id', 'value', 'YYYY-MM', 'Select month');
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertEquals('YYYY-MM', $data['format']);
        $this->assertEquals('Select month', $data['placeholder']);
    }
}
