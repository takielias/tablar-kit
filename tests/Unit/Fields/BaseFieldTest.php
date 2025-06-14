<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;

class BaseFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_create_field_with_name_and_label()
    {
        $field = new TestableBaseField('test_name', 'Test Label');

        $this->assertEquals('test_name', $field->getName());
        $this->assertEquals('Test Label', $field->getLabel());
    }

    /** @test */
    public function it_generates_automatic_label_from_name()
    {
        $field = new TestableBaseField('product_name');

        $this->assertEquals('Product Name', $field->getLabel());
    }

    /** @test */
    public function it_generates_random_id_if_not_set()
    {
        $field = new TestableBaseField('test_name');

        $this->assertNotEmpty($field->getId());
        $this->assertStringContainsString('test_name', $field->getId());
    }

    /** @test */
    public function it_can_add_attributes()
    {
        $field = new TestableBaseField('test_name');
        $field->addClass('custom-class')
            ->placeholder('Enter text')
            ->required();

        $attributes = $field->renderAttributes();

        $attributesString = http_build_query($attributes, '', ' ');

        $this->assertStringContainsString('class=custom-class', $attributesString);
        $this->assertArrayHasKey('placeholder', $attributes);
        $this->assertEquals('Enter text', $attributes['placeholder']);
        $this->assertStringContainsString('required=required', $attributesString);

    }

    /** @test */
    public function it_can_set_help_text()
    {
        $field = new TestableBaseField('test_name');
        $field->help('This is help text');

        $this->assertEquals('This is help text', $field->getHelp());
    }

    /** @test */
    public function it_can_check_if_required()
    {
        $field = new TestableBaseField('test_name');

        $this->assertFalse($field->isRequired());

        $field->required();

        $this->assertTrue($field->isRequired());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = TestableBaseField::make('product_name');

        $this->assertEquals('product_name', $field->getName());
        $this->assertEquals('Product Name', $field->getLabel());
    }

    /** @test */
    public function it_can_create_using_make_method_with_custom_label()
    {
        $field = TestableBaseField::make('product_name', 'Custom Label');

        $this->assertEquals('product_name', $field->getName());
        $this->assertEquals('Custom Label', $field->getLabel());
    }

    /** @test */
    public function it_can_get_field_value_from_provided_value()
    {
        $field = new TestableBaseField('test_name');

        $value = $field->getFieldValue('test_value');

        $this->assertEquals('test_value', $value);
    }

    /** @test */
    public function it_can_get_field_value_from_default_value()
    {
        $field = new TestableBaseField('test_name');
        $field->value('default_value');

        $value = $field->getFieldValue(null);

        $this->assertEquals('default_value', $value);
    }
}

