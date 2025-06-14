<?php

namespace Tests\Unit\TablarKit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\ButtonField;
use Illuminate\Support\Facades\View;
use TakiElias\TablarKit\Tests\Fake\FakeButtonField;

class ButtonFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock Laravel View facade
        $mockView = \Mockery::mock(\Illuminate\Contracts\View\View::class);
        $mockView->shouldReceive('with')->andReturnSelf();
        $mockView->shouldReceive('render')->andReturn('<button>');

        View::shouldReceive('make')->andReturn($mockView);

    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }


    /** @test */
    public function it_can_create_button_field()
    {
        $field = new ButtonField('Save', 'Save Button');

        $this->assertEquals('Save Button', $field->getLabel());
        $this->assertEquals('submit', $field->getType());
    }

    /** @test */
    public function it_can_create_button_with_auto_generated_label()
    {
        $field = new ButtonField();

        $this->assertEquals('Button', $field->getLabel());
    }

    /** @test */
    public function it_can_set_button_type()
    {
        $field = new ButtonField('Click Me');
        $field->type('button');

        $this->assertEquals('button', $field->getType());
    }

    /** @test */
    public function it_can_render_button()
    {
        $field = new FakeButtonField('Save');
        $rendered = $field->render();

        $this->assertIsString($rendered);
        $this->assertStringContainsString('<button', $rendered);
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = ButtonField::make('create_user', 'Create User');

        $this->assertEquals('Create User', $field->getLabel());
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = new ButtonField('Submit');

        $result = $field->type('submit')
            ->addClass('btn btn-primary')
            ->disabled();

        $this->assertSame($field, $result);
        $this->assertEquals('submit', $field->getType());
    }

    /** @test */
    public function it_handles_empty_label_in_constructor()
    {
        $field = new ButtonField('save_user', '');

        $this->assertEquals('Save User', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new ButtonField('Save');
        $field->addClass('btn-primary')
            ->disabled();

        $attributes = $field->renderAttributes();

        $this->assertArrayHasKey('class', $attributes);
        $this->assertStringContainsString('btn-primary', $attributes['class']);
        $this->assertEquals('disabled', $attributes['disabled']);
    }
}
