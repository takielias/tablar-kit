<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class FormTest extends ComponentTestCase
{

    /** @test */
    public function the_component_can_be_rendered()
    {
        $view = $this->blade(
            '<x-form action="http://example.com">Form fields...</x-form>'
        );

        $view->assertSee('form', false) // Check for the 'form' tag
        ->assertSee('method="POST"', false)
            ->assertSee('action="http://example.com"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="_method"', false)
            ->assertSee('Form fields...', false);
    }

    /** @test */
    public function it_can_enable_file_uploads()
    {
        $view = $this->blade(
            '<x-form action="http://example.com" has-files>Form fields...</x-form>'
        );

        $view->assertSee('form', false) // Check for the 'form' tag
        ->assertSee('method="POST"', false)
            ->assertSee('action="http://example.com"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('enctype="multipart/form-data"', false)
            ->assertSee('name="_method"', false)
            ->assertSee('Form fields...', false);
    }

}
