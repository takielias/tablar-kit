<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Textarea;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class TextareaTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Textarea('about');

        $this->assertEquals('about', $component->name);
        $this->assertEquals('about', $component->id);
        $this->assertEquals(3, $component->rows);
    }

    /** @test */
    public function component_accepts_custom_attributes()
    {
        $component = new Textarea('about', 'aboutMe', 5);

        $this->assertEquals('about', $component->name);
        $this->assertEquals('aboutMe', $component->id);
        $this->assertEquals(5, $component->rows);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new Textarea('test', 'test-id', 8);
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertEquals(8, $data['rows']);
    }
}
