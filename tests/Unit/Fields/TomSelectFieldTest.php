<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\TomSelectField;
use Illuminate\Support\Facades\View;

class TomSelectFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::shouldReceive('make')
            ->andReturn(\Mockery::mock(['render' => '<select></select>']));
    }

    /** @test */
    public function it_can_create_tom_select_field()
    {
        $field = new TomSelectField('tags', 'Tags');

        $this->assertEquals('tags', $field->getName());
        $this->assertEquals('Tags', $field->getLabel());
    }

    /** @test */
    public function it_auto_generates_label_from_name()
    {
        $field = new TomSelectField('product_categories');

        $this->assertEquals('Product Categories', $field->getLabel());
    }

    /** @test */
    public function it_can_enable_remote_data()
    {
        $field = new TomSelectField('users');
        $field->remoteData();

        $this->assertInstanceOf(TomSelectField::class, $field);
    }

    /** @test */
    public function it_can_disable_remote_data()
    {
        $field = new TomSelectField('categories');
        $field->remoteData(false);

        $this->assertInstanceOf(TomSelectField::class, $field);
    }

    /** @test */
    public function it_can_set_item_search_route()
    {
        $field = new TomSelectField('products');
        $field->itemSearchRoute('api.products.search');

        $this->assertInstanceOf(TomSelectField::class, $field);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $field = TomSelectField::make('categories')
            ->remoteData()
            ->itemSearchRoute('api.categories')
            ->required()
            ->help('Select categories');

        $this->assertEquals('categories', $field->getName());
        $this->assertTrue($field->isRequired());
        $this->assertEquals('Select categories', $field->getHelp());
    }

    /** @test */
    public function it_can_create_using_make_method()
    {
        $field = TomSelectField::make('user_tags');

        $this->assertEquals('user_tags', $field->getName());
        $this->assertEquals('User Tags', $field->getLabel());
    }

    /** @test */
    public function it_can_set_attributes()
    {
        $field = new TomSelectField('skills');
        $field->addClass('tom-select-custom')
            ->id('skills-select')
            ->setAttribute('data-placeholder', 'Choose skills');

        $attributes = $field->renderAttributes();

        $this->assertEquals('skills-select', $attributes['id']);
        $this->assertEquals('Choose skills', $attributes['data-placeholder']);
        $this->assertStringContainsString('tom-select-custom', $attributes['class'] ?? '');
    }

    /** @test */
    public function it_returns_correct_array_representation()
    {
        $field = new TomSelectField('tags');
        $field->label('Tags')
            ->remoteData()
            ->itemSearchRoute('api.tags')
            ->required()
            ->help('Select relevant tags');

        $array = $field->toArray();

        $this->assertEquals('TomSelectField', $array['type']);
        $this->assertEquals('tags', $array['name']);
        $this->assertEquals('Tags', $array['label']);
        $this->assertTrue($array['required']);
        $this->assertEquals('Select relevant tags', $array['help']);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
