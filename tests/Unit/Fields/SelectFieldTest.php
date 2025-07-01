<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\SelectField;
use Illuminate\Support\Facades\View;

class SelectFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<select></select>']));
    }

    /** @test */
    public function it_can_create_select_field()
    {
        $options = ['1' => 'Option 1', '2' => 'Option 2'];
        $field = new SelectField('category', $options, 'Category');

        $this->assertEquals('category', $field->getName());
        $this->assertEquals('Category', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new SelectField('product_type', ['books' => 'Books']);

        $this->assertEquals('Product Type', $field->getLabel());
    }

    /** @test */
    public function it_can_set_options()
    {
        $options = ['active' => 'Active', 'inactive' => 'Inactive'];
        $field = new SelectField('status');
        $field->options($options);

        $this->assertInstanceOf(SelectField::class, $field);
    }

    /** @test */
    public function it_can_set_placeholder()
    {
        $field = new SelectField('country', ['us' => 'USA']);
        $field->placeholder('Select country...');

        $this->assertInstanceOf(SelectField::class, $field);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = new SelectField('priority');
        $field->options(['high' => 'High', 'low' => 'Low'])
            ->placeholder('Choose priority')
            ->required()
            ->help('Select task priority');

        $this->assertEquals('priority', $field->getName());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Select task priority', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = new SelectField('status', ['active' => 'Active']);

        $this->assertEquals('status', $field->getName());
        $this->assertEquals('Status', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new SelectField('category', ['1' => 'Cat 1']);
        $field->addClass('custom-select')
            ->id('cat-select')
            ->disabled()
            ->setAttribute('data-live-search', 'true');

        $attributes = $field->renderAttributes();

        $this->assertEquals('cat-select', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('true', $attributes['data-live-search']);
        $this->assertStringContainsString('custom-select', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new SelectField('type', ['1' => 'Type 1', '2' => 'Type 2']);
        $field->required()
            ->rules(['in:1,2']);

        $rules = $field->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('in:1,2', $rules);
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $options = ['yes' => 'Yes', 'no' => 'No'];
        $field = new SelectField('confirmed', $options);
        $field->label('Confirmed')
            ->placeholder('Select option')
            ->required()
            ->help('Please select');

        $array = $field->toArray();

        $this->assertEquals('SelectField', $array['type']);
        $this->assertEquals('confirmed', $array['name']);
        $this->assertEquals('Confirmed', $array['label']);
        $this->assertEquals('Select option', $array['placeholder']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Please select', $array['help']);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
