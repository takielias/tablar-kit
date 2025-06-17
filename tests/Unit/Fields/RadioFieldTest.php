<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\RadioField;
use Illuminate\Support\Facades\View;

class RadioFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input type="radio">']));
    }

    /** @test */
    public function it_can_create_radio_field()
    {
        $options = ['1' => 'Option 1', '2' => 'Option 2'];
        $field = new RadioField('choice', $options, 'Select Choice');

        $this->assertEquals('choice', $field->getName());
        $this->assertEquals('Select Choice', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new RadioField('user_type', ['admin' => 'Admin']);

        $this->assertEquals('User Type', $field->getLabel());
    }

    /** @test */
    public function it_can_set_options()
    {
        $options = ['yes' => 'Yes', 'no' => 'No'];
        $field = new RadioField('confirmed');
        $field->options($options);

        $this->assertInstanceOf(RadioField::class, $field);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = new RadioField('status');
        $field->options(['active' => 'Active', 'inactive' => 'Inactive'])
            ->required()
            ->help('Select status');

        $this->assertEquals('status', $field->getName());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Select status', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = new RadioField('gender', ['m' => 'Male', 'f' => 'Female']);

        $this->assertEquals('gender', $field->getName());
        $this->assertEquals('Gender', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new RadioField('choice', ['1' => 'One']);
        $field->addClass('radio-group')
            ->setAttribute('data-toggle', 'radio');

        $attributes = $field->renderAttributes();

        $this->assertEquals('radio', $attributes['data-toggle']);
        $this->assertStringContainsString('radio-group', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new RadioField('choice', ['1' => 'One', '2' => 'Two']);
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
        $field = new RadioField('confirmed', $options);
        $field->label('Confirmed')
            ->required()
            ->help('Please confirm');

        $array = $field->toArray();

        $this->assertEquals('RadioField', $array['type']);
        $this->assertEquals('confirmed', $array['name']);
        $this->assertEquals('Confirmed', $array['label']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Please confirm', $array['help']);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
