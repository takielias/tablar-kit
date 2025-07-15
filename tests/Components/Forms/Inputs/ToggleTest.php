<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Toggle;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class ToggleTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Toggle('toogle', 'toogle', false, '', 'Toogle');

        $this->assertEquals('toogle', $component->name);
        $this->assertEquals('toogle', $component->id);
        $this->assertFalse($component->checked);
        $this->assertEquals('Toogle', $component->label);
    }

    /** @test */
    public function component_can_be_checked()
    {
        $component = new Toggle('toogle', 'toogle', true, '', 'Toogle');

        $this->assertTrue($component->checked);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new Toggle('test', 'test-id', false, 'value', 'Label');
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertFalse($data['checked']);
        $this->assertEquals('Label', $data['label']);
    }
}
