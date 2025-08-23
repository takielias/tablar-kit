<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Radio;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class RadioTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Radio('radio', 'radio');

        $this->assertEquals('radio', $component->name);
        $this->assertEquals('radio', $component->id);
        $this->assertEquals('radio', $component->type);
        $this->assertFalse($component->checked);
    }

    /** @test */
    public function component_can_be_checked()
    {
        $component = new Radio('radio', 'radio', true);

        $this->assertTrue($component->checked);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new Radio('test', 'test-id', false, 'value');
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertEquals('value', $data['value']);
        $this->assertFalse($data['checked']);
    }
}
