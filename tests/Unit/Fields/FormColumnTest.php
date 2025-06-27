<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\FormColumn;
use TakiElias\TablarKit\Fields\InputField;
use Illuminate\Support\Facades\View;

class FormColumnTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<div class="col-md-6"></div>']));
    }

    /** @test */
    public function it_can_create_form_column()
    {
        $column = new FormColumn(6);

        $this->assertEquals('', $column->getName());
        $this->assertEquals('', $column->getLabel());
    }

    /** @test */
    public function it_can_create_with_config()
    {
        $config = ['class' => 'custom-column'];
        $column = new FormColumn(12, $config);

        $this->assertInstanceOf(FormColumn::class, $column);
    }

    /** @test */
    public function it_can_set_fields()
    {
        $fields = collect([
            new InputField('name'),
            new InputField('email')
        ]);

        $column = new FormColumn(6);
        $column->setFields($fields);

        $this->assertInstanceOf(FormColumn::class, $column);
    }

    /** @test */
    public function it_can_set_empty_fields_collection()
    {
        $column = new FormColumn(12);
        $column->setFields(collect());

        $this->assertInstanceOf(FormColumn::class, $column);
    }

    /** @test */
    public function it_handles_different_column_widths()
    {
        $column4 = new FormColumn(4);
        $column8 = new FormColumn(8);
        $column12 = new FormColumn(12);

        // Test that different instances can be created with different widths
        $this->assertInstanceOf(FormColumn::class, $column4);
        $this->assertInstanceOf(FormColumn::class, $column8);
        $this->assertInstanceOf(FormColumn::class, $column12);
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $config = ['class' => 'special-column'];
        $column = new FormColumn(4, $config);

        $array = $column->toArray();

        $this->assertEquals('FormColumn', $array['type']);
        $this->assertEquals('', $array['name']);
        $this->assertEquals('', $array['label']);
        $this->assertEquals($config, $array['config']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $column = new FormColumn(6);
        $column->columnWidth(3);

        $this->assertEquals(3, $column->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
