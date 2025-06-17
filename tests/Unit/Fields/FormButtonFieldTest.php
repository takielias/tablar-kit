<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\FormButtonField;
use Illuminate\Support\Facades\View;

class FormButtonFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<button>Test</button>']));
    }

    /** @test */
    public function it_can_create_form_button_field()
    {
        $field = new FormButtonField('Submit', '/submit');

        $this->assertEquals('', $field->getName()); // FormButtonField has empty name
        $this->assertEquals('', $field->getLabel()); // FormButtonField has empty label
    }

    /** @test */
    public function it_can_create_button_without_action()
    {
        $field = new FormButtonField('Save');

        $this->assertInstanceOf(FormButtonField::class, $field);
    }

    /** @test */
    public function it_can_create_with_config()
    {
        $config = [
            'class' => 'btn-success',
            'type' => 'button'
        ];

        $field = new FormButtonField('Submit', '/action', $config);

        $this->assertInstanceOf(FormButtonField::class, $field);
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new FormButtonField('Click Me');
        $field->addClass('custom-btn')
            ->id('submit-btn')
            ->disabled()
            ->setAttribute('data-action', 'submit');

        $attributes = $field->renderAttributes();

        $this->assertEquals('submit-btn', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('submit', $attributes['data-action']);
        $this->assertStringContainsString('custom-btn', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_renders_with_view()
    {
        $field = new FormButtonField('Submit', '/submit');

        View::shouldReceive('make')
            ->with('tablar-kit::form-builder.form-button', \Mockery::type('array'))
            ->andReturnSelf();

        View::shouldReceive('render')
            ->andReturn('<button type="submit">Submit</button>');

        $html = $field->render();

        $this->assertIsString($html);
        $this->assertStringContainsString('button', $html);
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $config = ['class' => 'btn-danger', 'type' => 'reset'];
        $field = new FormButtonField('Reset', '', $config);

        $array = $field->toArray();

        $this->assertEquals('FormButtonField', $array['type']);
        $this->assertEquals('', $array['name']);
        $this->assertEquals('', $array['label']);
        $this->assertEquals($config, $array['config']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $field = new FormButtonField('Submit');
        $field->columnWidth(4);

        $this->assertEquals(4, $field->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
