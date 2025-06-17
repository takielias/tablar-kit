<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\HiddenField;
use Illuminate\Support\Facades\View;

class HiddenFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input type="hidden">']));
    }

    /** @test */
    public function it_can_create_hidden_field()
    {
        $field = new HiddenField('user_id', '123');

        $this->assertEquals('user_id', $field->getName());
        $this->assertEquals('123', $field->getValue());
        $this->assertEquals('User Id', $field->getLabel()); // Auto-generated from name
    }

    /** @test */
    public function it_can_create_without_value()
    {
        $field = new HiddenField('token');

        $this->assertEquals('token', $field->getName());
        $this->assertEquals('', $field->getValue());
    }

    /** @test */
    public function it_can_create_with_config()
    {
        $config = ['attributes' => ['data-field' => 'hidden']];
        $field = new HiddenField('id', '456', $config);

        $this->assertEquals('456', $field->getValue());
    }

    /** @test */
    public function it_can_set_value()
    {
        $field = new HiddenField('status');
        $field->value('active');

        $this->assertEquals('active', $field->getValue());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new HiddenField('secret', 'value');
        $field->id('hidden-secret')
            ->setAttribute('data-encrypted', 'true');

        $attributes = $field->renderAttributes();

        $this->assertEquals('hidden-secret', $attributes['id']);
        $this->assertEquals('true', $attributes['data-encrypted']);
        $this->assertEquals('secret', $attributes['name']);
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = HiddenField::make('csrf_token', 'abc123');

        $this->assertEquals('csrf_token', $field->getName());
        $this->assertEquals('abc123', $field->getValue());
        $this->assertEquals('Csrf Token', $field->getLabel()); // Auto-generated but not used
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new HiddenField('user_id', '789');
        $field->setAttribute('data-type', 'id');

        $array = $field->toArray();

        $this->assertEquals('HiddenField', $array['type']);
        $this->assertEquals('user_id', $array['name']);
        $this->assertEquals('789', $array['value']);
        $this->assertEquals('id', $array['attributes']['data-type']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $field = new HiddenField('data');
        $field->columnWidth(0); // Hidden fields typically don't need column width

        $this->assertEquals(0, $field->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
