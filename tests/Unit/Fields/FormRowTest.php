<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\FormRow;
use Takielias\TablarKit\Fields\InputField;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Collection;

class FormRowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<div class="row"></div>']));
    }

    /** @test */
    public function it_can_create_form_row()
    {
        $row = new FormRow();

        $this->assertEquals('', $row->getName());
        $this->assertEquals('', $row->getLabel());
    }

    /** @test */
    public function it_can_create_with_config()
    {
        $config = ['class' => 'custom-row'];
        $row = new FormRow($config);

        $this->assertInstanceOf(FormRow::class, $row);
    }

    /** @test */
    public function it_can_set_fields()
    {
        $fields = collect([
            new InputField('name'),
            new InputField('email')
        ]);

        $row = new FormRow();
        $row->setFields($fields);

        $this->assertInstanceOf(FormRow::class, $row);
    }

    /** @test */
    public function it_can_set_empty_fields_collection()
    {
        $row = new FormRow();
        $row->setFields(collect());

        $this->assertInstanceOf(FormRow::class, $row);
    }

    /** @test */
    public function it_handles_multiple_fields()
    {
        $fields = collect([
            new InputField('first_name'),
            new InputField('last_name'),
            new InputField('email')
        ]);

        $row = new FormRow();
        $row->setFields($fields);

        $this->assertInstanceOf(FormRow::class, $row);
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $config = ['class' => 'special-row'];
        $row = new FormRow($config);

        $array = $row->toArray();

        $this->assertEquals('FormRow', $array['type']);
        $this->assertEquals('', $array['name']);
        $this->assertEquals('', $array['label']);
        $this->assertEquals($config, $array['config']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $row = new FormRow();
        $row->columnWidth(8);

        $this->assertEquals(8, $row->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
