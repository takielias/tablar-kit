<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\FlatPickerField;
use Illuminate\Support\Facades\View;

class FlatPickerFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input>']));
    }

    /** @test */
    public function it_can_create_flatpicker_field()
    {
        $field = new FlatPickerField('date', 'Select Date');

        $this->assertEquals('date', $field->getName());
        $this->assertEquals('Select Date', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new FlatPickerField('created_at');

        $this->assertEquals('Created At', $field->getLabel());
    }

    /** @test */
    public function it_can_set_picker_config()
    {
        $config = ['enableTime' => true, 'mode' => 'range'];
        $field = new FlatPickerField('date');
        $field->config($config);

        $this->assertInstanceOf(FlatPickerField::class, $field);
    }

    /** @test */
    public function it_can_merge_config_values()
    {
        $field = new FlatPickerField('date');
        $field->config(['enableTime' => true])
            ->config(['mode' => 'range']);

        $this->assertInstanceOf(FlatPickerField::class, $field);
    }

    /** @test */
    public function it_can_enable_time()
    {
        $field = new FlatPickerField('datetime');
        $field->enableTime();

        $this->assertInstanceOf(FlatPickerField::class, $field);
    }

    /** @test */
    public function it_can_disable_time()
    {
        $field = new FlatPickerField('date');
        $field->enableTime(false);

        $this->assertInstanceOf(FlatPickerField::class, $field);
    }

    /** @test */
    public function it_can_set_date_format()
    {
        $field = new FlatPickerField('date');
        $field->dateFormat('d/m/Y');

        $this->assertInstanceOf(FlatPickerField::class, $field);
    }

    /** @test */
    public function it_can_set_placeholder()
    {
        $field = new FlatPickerField('date');
        $field->placeholder('Choose date...');

        $this->assertInstanceOf(FlatPickerField::class, $field);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = FlatPickerField::make('event_date')
            ->enableTime()
            ->dateFormat('Y-m-d H:i')
            ->placeholder('Select event date and time')
            ->required()
            ->help('Choose the event date');

        $this->assertEquals('event_date', $field->getName());
        $this->assertEquals('Event Date', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Choose the event date', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = FlatPickerField::make('appointment_date');

        $this->assertEquals('appointment_date', $field->getName());
        $this->assertEquals('Appointment Date', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new FlatPickerField('date');
        $field->addClass('flatpickr-custom')
            ->id('date-picker')
            ->disabled()
            ->setAttribute('data-min-date', 'today');

        $attributes = $field->renderAttributes();

        $this->assertEquals('date-picker', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('today', $attributes['data-min-date']);
        $this->assertStringContainsString('flatpickr-custom', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new FlatPickerField('date');
        $field->required()
            ->rules(['date', 'after:today']);

        $rules = $field->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('date', $rules);
        $this->assertContains('after:today', $rules);
    }

    /** @test */
    public function it_has_correct_attributes_for_rendering()
    {
        $field = new FlatPickerField('date');
        $field->enableTime()
            ->dateFormat('d/m/Y H:i')
            ->placeholder('Pick date and time')
            ->required()
            ->addClass('custom-picker');

        $attributes = $field->renderAttributes();

        $this->assertEquals('date', $attributes['name']);
        $this->assertEquals('required', $attributes['required']);
        $this->assertStringContainsString('custom-picker', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new FlatPickerField('meeting_date');
        $field->label('Meeting Date')
            ->enableTime()
            ->dateFormat('Y-m-d H:i')
            ->placeholder('Select meeting date')
            ->required()
            ->help('Choose when the meeting will occur')
            ->rules(['required', 'date']);

        $array = $field->toArray();

        $this->assertEquals('FlatPickerField', $array['type']);
        $this->assertEquals('meeting_date', $array['name']);
        $this->assertEquals('Meeting Date', $array['label']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Choose when the meeting will occur', $array['help']);
        $this->assertEquals('Select meeting date', $array['placeholder']);
        $this->assertContains('required', $array['validation']);
        $this->assertContains('date', $array['validation']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $field = new FlatPickerField('date');
        $field->columnWidth(6);

        $this->assertEquals(6, $field->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
