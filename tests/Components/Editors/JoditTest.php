<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Editors;

use TakiElias\TablarKit\Components\Editors\Jodit;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class JoditTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Jodit('editor');

        $this->assertEquals('editor', $component->name);
        $this->assertEquals('editor', $component->id);
        $this->assertIsArray($component->options);
    }

    /** @test */
    public function the_component_renders_textarea()
    {
        $view = $this->blade('<textarea name="editor" id="editor"></textarea>');
        $view->assertSee('name="editor"', false);
        $view->assertSee('id="editor"', false);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new Jodit('test', 'test-id', ['option' => 'value']);
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertEquals(['option' => 'value'], $data['options']);
    }
}
