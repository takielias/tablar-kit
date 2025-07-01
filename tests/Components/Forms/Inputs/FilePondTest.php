<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\FilePond;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class FilePondTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new FilePond('file', 'file');

        $this->assertEquals('file', $component->name);
        $this->assertEquals('file', $component->id);
        $this->assertEquals('file', $component->type);
        $this->assertFalse($component->chunk_upload);
        $this->assertTrue($component->image_manipulation);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new FilePond('test', 'test-id', 'file', true, false);
        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('test-id', $data['id']);
        $this->assertTrue($data['chunk_upload']);
        $this->assertFalse($data['image_manipulation']);
    }
}
