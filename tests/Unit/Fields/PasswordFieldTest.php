<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\PasswordField;
use Illuminate\Support\Facades\View;

class PasswordFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input type="password">']));
    }

    /** @test */
    public function it_can_create_password_field()
    {
        $field = new PasswordField('password', 'Password');

        $this->assertEquals('password', $field->getName());
        $this->assertEquals('Password', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new PasswordField('user_password');

        $this->assertEquals('User Password', $field->getLabel());
    }

    /** @test */
    public function it_can_set_confirmation_rule()
    {
        $field = new PasswordField('password');
        $field->confirmation();

        $rules = $field->getValidationRules();
        $this->assertContains('confirmed', $rules);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = PasswordField::make('password')
            ->confirmation()
            ->required()
            ->help('Enter secure password');

        $this->assertEquals('password', $field->getName());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Enter secure password', $field->getHelp());
        $this->assertContains('confirmed', $field->getValidationRules());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = PasswordField::make('user_password');

        $this->assertEquals('user_password', $field->getName());
        $this->assertEquals('User Password', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new PasswordField('password');
        $field->addClass('password-input')
            ->id('pwd-field')
            ->setAttribute('autocomplete', 'new-password');

        $attributes = $field->renderAttributes();

        $this->assertEquals('pwd-field', $attributes['id']);
        $this->assertEquals('new-password', $attributes['autocomplete']);
        $this->assertStringContainsString('password-input', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new PasswordField('password');
        $field->required()
            ->confirmation()
            ->rules(['min:8', 'regex:/[A-Z]/']);

        $rules = $field->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('confirmed', $rules);
        $this->assertContains('min:8', $rules);
        $this->assertContains('regex:/[A-Z]/', $rules);
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new PasswordField('password');
        $field->label('Password')
            ->confirmation()
            ->required()
            ->help('Minimum 8 characters')
            ->rules(['min:8']);

        $array = $field->toArray();

        $this->assertEquals('PasswordField', $array['type']);
        $this->assertEquals('password', $array['name']);
        $this->assertEquals('Password', $array['label']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Minimum 8 characters', $array['help']);
        $this->assertContains('confirmed', $array['validation']);
        $this->assertContains('min:8', $array['validation']);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
