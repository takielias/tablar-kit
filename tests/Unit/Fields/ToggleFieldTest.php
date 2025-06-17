<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\ToggleField;
use Illuminate\Support\Facades\View;

class ToggleFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<input type="checkbox">']));
    }

    /** @test */
    public function it_can_create_toggle_field()
    {
        $field = new ToggleField('active', 'Is Active');

        $this->assertEquals('active', $field->getName());
        $this->assertEquals('Is Active', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new ToggleField('is_featured');

        $this->assertEquals('Is Featured', $field->getLabel());
    }

    /** @test */
    public function it_can_set_checked_value()
    {
        $field = new ToggleField('status');
        $field->checkedValue(2);

        $this->assertInstanceOf(ToggleField::class, $field);
    }

    /** @test */
    public function it_can_set_checked_state()
    {
        $field = new ToggleField('enabled');
        $field->checked();

        $this->assertEquals(1, $field->getValue());
    }

    /** @test */
    public function it_can_set_unchecked_state()
    {
        $field = new ToggleField('enabled');
        $field->checked(false);

        $this->assertEquals(0, $field->getValue());
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = ToggleField::make('published')
            ->checkedValue(10)
            ->checked()
            ->required()
            ->help('Toggle publication status');

        $this->assertEquals('published', $field->getName());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Toggle publication status', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = ToggleField::make('newsletter');

        $this->assertEquals('newsletter', $field->getName());
        $this->assertEquals('Newsletter', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new ToggleField('active');
        $field->addClass('toggle-switch')
            ->id('active-toggle')
            ->disabled()
            ->setAttribute('data-toggle', 'switch');

        $attributes = $field->renderAttributes();

        $this->assertEquals('active-toggle', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('switch', $attributes['data-toggle']);
        $this->assertStringContainsString('toggle-switch', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new ToggleField('published');
        $field->label('Published')
            ->checkedValue(5)
            ->checked()
            ->help('Toggle publish state');

        $array = $field->toArray();

        $this->assertEquals('ToggleField', $array['type']);
        $this->assertEquals('published', $array['name']);
        $this->assertEquals('Published', $array['label']);
        $this->assertEquals('Toggle publish state', $array['help']);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
