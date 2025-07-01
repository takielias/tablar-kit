<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\FlatPicker;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class FlatPickerTest extends ComponentTestCase
{
    /** @test */
    public function the_flat_picker_component_can_be_rendered()
    {
        $component = new FlatPicker('date', 'flat-picker');

        $this->assertEquals('date', $component->name);
        $this->assertEquals('flat-picker', $component->id);
        $this->assertEquals('Y-m-d H:i', $component->format);
        $this->assertEquals('Y-m-d H:i', $component->placeholder);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new FlatPicker('test', 'test-id', 'value', 'Y-m-d', 'Select date');
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertEquals('Y-m-d', $data['format']);
        $this->assertEquals('Select date', $data['placeholder']);
    }
}
