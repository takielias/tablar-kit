<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Select;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class SelectTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Select('select', 'select');

        $this->assertEquals('select', $component->name);
        $this->assertEquals('select', $component->id);
        $this->assertIsArray($component->options);
        $this->assertEmpty($component->options);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $options = ['key1' => 'Option 1', 'key2' => 'Option 2'];
        $component = new Select('test', 'test-id', 'key1', $options, 'Choose...');
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertEquals($options, $data['options']);
        $this->assertEquals('Choose...', $data['placeholder']);
        $this->assertEquals('key1', $data['value']);
    }
}
