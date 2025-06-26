<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Checkbox;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class CheckboxTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Checkbox('remember_me');

        $this->assertEquals('remember_me', $component->name);
        $this->assertEquals('remember_me', $component->id);
        $this->assertFalse($component->checked);
        $this->assertEquals('', $component->value);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new Checkbox('test', 'test-id', true, 'yes');
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertTrue($data['checked']);
        $this->assertEquals('yes', $data['value']);
    }

    /** @test */
    public function inputs_can_have_old_values()
    {
        $this->flashOld(['remember_me' => '1']);

        $component = new Checkbox('remember_me');

        $this->assertTrue($component->checked);
    }
}
