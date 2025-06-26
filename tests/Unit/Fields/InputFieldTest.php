<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\InputField;
use Illuminate\Support\Facades\View;

class InputFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock Laravel View facade
        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input>']));
    }

    /** @test */
    public function it_can_create_input_field()
    {
        $field = new InputField('email', 'Email Address');

        $this->assertEquals('email', $field->getName());
        $this->assertEquals('Email Address', $field->getLabel());
        $this->assertEquals('text', $field->getType());
    }

    /** @test */
    public function it_can_set_input_type()
    {
        $field = new InputField('email');
        $field->type('email');

        $this->assertEquals('email', $field->getType());
    }

    /** @test */
    public function it_can_set_email_type()
    {
        $field = new InputField('email');
        $field->type('email');

        $this->assertEquals('email', $field->getType());
    }

    /** @test */
    public function it_can_set_password_type()
    {
        $field = new InputField('password');
        $field->type('password');

        $this->assertEquals('password', $field->getType());
    }

    /** @test */
    public function it_can_set_number_type()
    {
        $field = new InputField('age');
        $field->type('number');

        $this->assertEquals('number', $field->getType());
    }

    /** @test */
    public function it_can_set_url_type()
    {
        $field = new InputField('website');
        $field->type('url');

        $this->assertEquals('url', $field->getType());
    }

    /** @test */
    public function it_can_set_tel_type()
    {
        $field = new InputField('phone');
        $field->type('tel');

        $this->assertEquals('tel', $field->getType());
    }

    /** @test */
    public function it_can_set_date_type()
    {
        $field = new InputField('birthday');
        $field->type('date');

        $this->assertEquals('date', $field->getType());
    }

    /** @test */
    public function it_can_set_time_type()
    {
        $field = new InputField('meeting_time');
        $field->type('time');

        $this->assertEquals('time', $field->getType());
    }


    /** @test */
    public function it_can_set_number_attributes()
    {
        $field = new InputField('price');
        $field->type('number')
            ->min(0)
            ->max(1000)
            ->step(0.01);

        $attributes = $field->renderAttributes();

        $this->assertEquals('0', $attributes['min']);
        $this->assertEquals('1000', $attributes['max']);
        $this->assertEquals('0.01', $attributes['step']);
    }

    /** @test */
    public function it_can_set_text_length_attributes()
    {
        $field = new InputField('username');
        $field->min(3)
            ->maxLength(20);

        $attributes = $field->renderAttributes();;
        $this->assertEquals('3', $attributes['min']);
        $this->assertEquals('20', $attributes['maxlength']);

    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = InputField::make('user_email');

        $this->assertEquals('user_email', $field->getName());
        $this->assertEquals('User Email', $field->getLabel());
    }

}
