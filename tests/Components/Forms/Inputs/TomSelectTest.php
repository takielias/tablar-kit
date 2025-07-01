<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\TomSelect;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class TomSelectTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new TomSelect('select', 'select');

        $this->assertEquals('select', $component->name);
        $this->assertEquals('select', $component->id);
        $this->assertIsArray($component->options);
        $this->assertFalse($component->remoteData);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $options = ['1' => 'Option 1', '2' => 'Option 2'];
        $component = new TomSelect(
            'test',
            'test-id',
            'value',
            $options,
            'Choose...',
            true,
            'search.route',
            true,
            5
        );

        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals($options, $data['options']);
        $this->assertTrue($data['remoteData']);
        $this->assertEquals('Choose...', $data['placeholder']);
        $this->assertEquals(5, $data['maxItems']);
    }
}
