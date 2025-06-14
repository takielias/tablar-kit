<?php

namespace Tests\Unit\TablarKit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\TextareaField;
use Illuminate\Support\Facades\View;

class TextareaFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock Laravel View facade
        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<textarea></textarea>']));
    }

    /** @test */
    public function it_can_create_textarea_field()
    {
        $field = new TextareaField('description', 'Product Description');

        $this->assertEquals('description', $field->getName());
        $this->assertEquals('Product Description', $field->getLabel());
    }

    /** @test */
    public function it_can_set_rows()
    {
        $field = new TextareaField('description');
        $field->rows(5);

        $attributes = $field->renderAttributes();

        $this->assertEquals('5', $attributes['rows']);

    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = TextareaField::make('product_description');

        $this->assertEquals('product_description', $field->getName());
        $this->assertEquals('Product Description', $field->getLabel());
    }

    /** @test */
    public function it_can_chain_multiple_attributes()
    {
        $field = new TextareaField('description');
        $field->rows(10)
            ->placeholder('Enter description')
            ->required();

        $attributes = $field->renderAttributes();

        $this->assertEquals('10', $attributes['rows']);
        $this->assertEquals('Enter description', $attributes['placeholder']);
        $this->assertEquals('required', $attributes['required']);
        $this->assertEquals('description', $attributes['name']);

    }
}
