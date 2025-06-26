<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Illuminate\Support\Facades\View;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\CheckboxField;
use TakiElias\TablarKit\Tests\Fake\FakeCheckboxField;

class CheckboxFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $mockView = \Mockery::mock(\Illuminate\Contracts\View\View::class);
        $mockView->shouldReceive('with')->andReturnSelf();
        $mockView->shouldReceive('render')->andReturn('<input type="checkbox">');

        View::shouldReceive('make')->andReturn($mockView);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }


    /** @test */
    public function it_can_create_checkbox_field()
    {
        $field = new CheckboxField('agree', 'I Agree');

        $this->assertEquals('agree', $field->getName());
        $this->assertEquals('I Agree', $field->getLabel());
        $this->assertEquals(1, $field->getCheckedValue());

        $field->checked(false);

        $this->assertEquals(0, $field->getValue());
    }

    /** @test */
    public function it_can_create_checkbox_with_auto_generated_label()
    {
        $field = new CheckboxField('terms_conditions');

        $this->assertEquals('Terms Conditions', $field->getLabel());
    }

    /** @test */
    public function it_can_set_checked_value()
    {
        $field = new CheckboxField('status');
        $field->checked();

        $this->assertEquals(1, $field->getCheckedValue());
    }

    /** @test */
    public function it_can_set_checked_state()
    {
        $field = new CheckboxField('enabled');
        $field->checked(true);

        $this->assertEquals(1, $field->getValue());
    }

    /** @test */
    public function it_can_set_unchecked_state()
    {
        $field = new CheckboxField('enabled');
        $field->checked(false);

        $this->assertEquals(0, $field->getValue());
    }

    /** @test */
    public function it_can_render_checkbox()
    {
        $field = new FakeCheckboxField('agree');
        $rendered = $field->render(true);
        $this->assertIsString($rendered);
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = CheckboxField::make('newsletter_subscribe', 'Subscribe to Newsletter');

        $this->assertEquals('newsletter_subscribe', $field->getName());
        $this->assertEquals('Subscribe to Newsletter', $field->getLabel());
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = new CheckboxField('active');

        $result = $field->checked()
            ->addClass('form-check-input')
            ->required();

        $this->assertSame($field, $result);
        $this->assertEquals(1, $field->getCheckedValue());
        $this->assertTrue($field->isRequired());
    }

    /** @test */
    public function it_can_set_custom_checked_and_unchecked_values()
    {
        $field = new CheckboxField('status');
        $field->checked(false);

        $this->assertEquals(0, $field->getValue());
    }

    /** @test */
    public function it_renders_with_correct_attributes()
    {
        $field = new CheckboxField('terms');
        $field->addClass('custom-checkbox')
            ->required()
            ->disabled();

        $attributes = $field->renderAttributes();

        $this->assertEquals('terms', $attributes['name']);
        $this->assertStringContainsString('custom-checkbox', $attributes['class']);
        $this->assertEquals('required', $attributes['required']);
        $this->assertEquals('disabled', $attributes['disabled']);
    }

    /** @test */
    public function it_handles_empty_label_in_constructor()
    {
        $field = new CheckboxField('accept_terms', '');

        $this->assertEquals('Accept Terms', $field->getLabel());
    }
}
