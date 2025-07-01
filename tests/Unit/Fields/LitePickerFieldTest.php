<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\LitePickerField;
use Illuminate\Support\Facades\View;

class LitePickerFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input>']));
    }

    /** @test */
    public function it_can_create_litepicker_field()
    {
        $field = new LitePickerField('date', 'Select Date');

        $this->assertEquals('date', $field->getName());
        $this->assertEquals('Select Date', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new LitePickerField('created_at');

        $this->assertEquals('Created At', $field->getLabel());
    }

    /** @test */
    public function it_can_set_picker_config()
    {
        $config = ['singleMode' => false, 'numberOfMonths' => 2];
        $field = new LitePickerField('date');
        $field->config($config);

        $this->assertInstanceOf(LitePickerField::class, $field);
    }

    /** @test */
    public function it_can_merge_config_values()
    {
        $field = new LitePickerField('date');
        $field->config(['singleMode' => true])
            ->config(['numberOfColumns' => 2]);

        $this->assertInstanceOf(LitePickerField::class, $field);
    }

    /** @test */
    public function it_can_set_single_mode()
    {
        $field = new LitePickerField('date');
        $field->singleMode();

        $this->assertInstanceOf(LitePickerField::class, $field);
    }

    /** @test */
    public function it_can_disable_single_mode()
    {
        $field = new LitePickerField('date_range');
        $field->singleMode(false);

        $this->assertInstanceOf(LitePickerField::class, $field);
    }

    /** @test */
    public function it_can_set_format()
    {
        $field = new LitePickerField('date');
        $field->format('DD/MM/YYYY');

        $this->assertInstanceOf(LitePickerField::class, $field);
    }

    /** @test */
    public function it_can_set_placeholder()
    {
        $field = new LitePickerField('date');
        $field->placeholder('Choose date...');

        $this->assertInstanceOf(LitePickerField::class, $field);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = LitePickerField::make('event_date')
            ->singleMode()
            ->format('YYYY-MM-DD')
            ->placeholder('Select event date')
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
        $field = LitePickerField::make('appointment_date');

        $this->assertEquals('appointment_date', $field->getName());
        $this->assertEquals('Appointment Date', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new LitePickerField('date');
        $field->addClass('litepicker-custom')
            ->id('date-picker')
            ->disabled()
            ->setAttribute('data-min-date', 'today');

        $attributes = $field->renderAttributes();

        $this->assertEquals('date-picker', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('today', $attributes['data-min-date']);
        $this->assertStringContainsString('litepicker-custom', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new LitePickerField('date');
        $field->required()
            ->rules(['date', 'after:today']);

        $rules = $field->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('date', $rules);
        $this->assertContains('after:today', $rules);
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new LitePickerField('meeting_date');
        $field->label('Meeting Date')
            ->singleMode()
            ->format('YYYY-MM-DD')
            ->placeholder('Select meeting date')
            ->required()
            ->help('Choose when the meeting will occur')
            ->rules(['required', 'date']);

        $array = $field->toArray();

        $this->assertEquals('LitePickerField', $array['type']);
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
        $field = new LitePickerField('date');
        $field->columnWidth(6);

        $this->assertEquals(6, $field->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
