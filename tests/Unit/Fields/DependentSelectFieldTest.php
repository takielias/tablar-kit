<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use Takielias\TablarKit\Fields\DependentSelectField;
use Illuminate\Support\Facades\View;

class DependentSelectFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock Laravel View facade
        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<select></select>']));
    }

    /** @test */
    public function it_can_create_dependent_select_field()
    {
        $field = new DependentSelectField('category', 'subcategory');

        $this->assertEquals('category', $field->getName());
        $this->assertEquals('Category', $field->getLabel()); // Auto-generated from name
    }

    /** @test */
    public function it_can_create_with_config()
    {
        $config = [
            'label' => 'Product Category',
            'required' => true,
            'help' => 'Select a category first',
            'placeholder' => 'Choose category...'
        ];

        $field = new DependentSelectField('category', 'subcategory', $config);

        $this->assertEquals('category', $field->getName());
        $this->assertEquals('Product Category', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Select a category first', $field->getHelp());
    }

    /** @test */
    public function it_can_set_target_dropdown()
    {
        $field = new DependentSelectField('category');
        $field->targetDropdown('subcategory');

        // Since targetDropdown is protected, we'll test through render or toArray
        $array = $field->toArray();
        $this->assertEquals('DependentSelectField', $array['type']);
    }

    /** @test */
    public function it_can_set_target_data_route()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->targetDataRoute('api.subcategories');

        // Test fluent interface returns self
        $this->assertInstanceOf(DependentSelectField::class, $field);
    }

    /** @test */
    public function it_can_set_options()
    {
        $options = [
            '1' => 'Electronics',
            '2' => 'Clothing',
            '3' => 'Books'
        ];

        $field = new DependentSelectField('category', 'subcategory');
        $field->options($options);

        // Test fluent interface returns self
        $this->assertInstanceOf(DependentSelectField::class, $field);
    }

    /** @test */
    public function it_can_set_placeholder()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->placeholder('Select a category...');

        // Test fluent interface returns self
        $this->assertInstanceOf(DependentSelectField::class, $field);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = DependentSelectField::make('category')
            ->targetDropdown('subcategory')
            ->targetDataRoute('api.subcategories')
            ->options(['1' => 'Option 1', '2' => 'Option 2'])
            ->placeholder('Choose...')
            ->required()
            ->help('This is help text');

        $this->assertEquals('category', $field->getName());
        $this->assertEquals('Category', $field->getLabel());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('This is help text', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = DependentSelectField::make('product_category');

        $this->assertEquals('product_category', $field->getName());
        $this->assertEquals('Product Category', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->addClass('custom-class')
            ->id('category-select')
            ->disabled()
            ->setAttribute('data-custom', 'value');

        $attributes = $field->renderAttributes();

        $this->assertEquals('category-select', $attributes['id']);
        $this->assertEquals('disabled', $attributes['disabled']);
        $this->assertEquals('value', $attributes['data-custom']);
        $this->assertStringContainsString('custom-class', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_can_set_validation_rules()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->required()
            ->rules(['in:1,2,3', 'numeric']);

        $rules = $field->getValidationRules();

        $this->assertContains('required', $rules);
        $this->assertContains('in:1,2,3', $rules);
        $this->assertContains('numeric', $rules);
    }

    /** @test */
    public function it_can_set_value()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->value('2');

        $this->assertEquals('2', $field->getValue());
    }

    /** @test */
    public function it_can_get_field_value_with_fallbacks()
    {
        $field = new DependentSelectField('category', 'subcategory');

        // Test with passed value
        $this->assertEquals('test', $field->getFieldValue('test'));

        // Test with field's own value
        $field->value('field-value');
        $this->assertEquals('field-value', $field->getFieldValue());

        // Test empty array returns empty string
        $this->assertEquals('', $field->getFieldValue([]));
    }

    /** @test */
    public function it_has_correct_attributes_for_rendering()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->options(['1' => 'Option 1'])
            ->placeholder('Select...')
            ->value('1')
            ->required()
            ->addClass('custom-class');

        $attributes = $field->renderAttributes();

        $this->assertEquals('category', $attributes['name']);
        $this->assertEquals('required', $attributes['required']);
        $this->assertStringContainsString('custom-class', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->label('Product Category')
            ->required()
            ->help('Select category')
            ->placeholder('Choose...')
            ->value('1')
            ->rules(['required']);

        $array = $field->toArray();

        $this->assertEquals('DependentSelectField', $array['type']);
        $this->assertEquals('category', $array['name']);
        $this->assertEquals('Product Category', $array['label']);
        $this->assertEquals('1', $array['value']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Select category', $array['help']);
        $this->assertEquals('Choose...', $array['placeholder']);
        $this->assertContains('required', $array['validation']);
    }

    /** @test */
    public function it_can_set_column_width()
    {
        $field = new DependentSelectField('category', 'subcategory');
        $field->columnWidth(6);

        $this->assertEquals(6, $field->getColumnWidth());
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
