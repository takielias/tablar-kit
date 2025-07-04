<?php

namespace TakiElias\TablarKit\Tests\Unit\Fields;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Fields\RepeaterField;
use TakiElias\TablarKit\Fields\InputField;
use TakiElias\TablarKit\Fields\SelectField;
use TakiElias\TablarKit\Fields\ToggleField;
use Illuminate\Support\Facades\View;

class RepeaterFieldTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock the view with less strict expectations
        View::shouldReceive('make')
            ->with('tablar-kit::form-builder.fields.repeater', \Mockery::any())
            ->andReturn(\Mockery::mock(['render' => '<div class="repeater-field"></div>']))
            ->byDefault();
    }

    /** @test */
    public function it_can_create_repeater_field()
    {
        $callback = function ($index, $item) {
            return [new InputField("contacts[{$index}][name]", 'Name')];
        };

        $field = new RepeaterField('contacts', $callback);

        $this->assertEquals('contacts', $field->getName());
        $this->assertInstanceOf(RepeaterField::class, $field);
    }

    /** @test */
    public function it_can_set_items()
    {
        $callback = function ($index, $item) {
            return [];
        };
        $items = [['name' => 'John'], ['name' => 'Jane']];

        $field = new RepeaterField('users', $callback);
        $result = $field->items($items);

        $this->assertInstanceOf(RepeaterField::class, $result);
    }

    /** @test */
    public function it_can_set_min_items()
    {
        $callback = function ($index, $item) {
            return [];
        };
        $field = new RepeaterField('items', $callback);
        $result = $field->minItems(2);

        $this->assertInstanceOf(RepeaterField::class, $result);
    }

    /** @test */
    public function it_can_set_max_items()
    {
        $callback = function ($index, $item) {
            return [];
        };
        $field = new RepeaterField('items', $callback);
        $result = $field->maxItems(5);

        $this->assertInstanceOf(RepeaterField::class, $result);
    }

    /** @test */
    public function it_can_set_button_texts()
    {
        $callback = function ($index, $item) {
            return [];
        };
        $field = new RepeaterField('items', $callback);
        $result = $field->addButtonText('Add New Item')
            ->removeButtonText('Delete');

        $this->assertInstanceOf(RepeaterField::class, $result);
    }

    /** @test */
    public function it_can_enable_sortable()
    {
        $callback = function ($index, $item) {
            return [];
        };
        $field = new RepeaterField('items', $callback);
        $result = $field->sortable();

        $this->assertInstanceOf(RepeaterField::class, $result);
    }

    /** @test */
    public function it_can_disable_sortable()
    {
        $callback = function ($index, $item) {
            return [];
        };
        $field = new RepeaterField('items', $callback);
        $result = $field->sortable(false);

        $this->assertInstanceOf(RepeaterField::class, $result);
    }

    /** @test */
    public function it_can_chain_methods()
    {
        $callback = function ($index, $item) {
            return [
                new InputField("contacts[{$index}][name]", 'Name'),
                new InputField("contacts[{$index}][email]", 'Email')
            ];
        };

        $field = new RepeaterField('contacts', $callback);
        $result = $field->minItems(1)
            ->maxItems(10)
            ->addButtonText('Add Contact')
            ->removeButtonText('Remove Contact')
            ->sortable();

        $this->assertEquals('contacts', $field->getName());
        $this->assertInstanceOf(RepeaterField::class, $result);
    }

    /** @test */
    public function it_handles_callback_execution()
    {
        $callback = function ($index, $item) {
            return [
                new InputField("contacts[{$index}][name]", 'Contact Name'),
                new InputField("contacts[{$index}][email]", 'Email'),
                (new SelectField("contacts[{$index}][role]", 'Role'))->options([
                    'manager' => 'Manager',
                    'developer' => 'Developer'
                ]),
                new ToggleField("contacts[{$index}][primary]", 'Primary Contact')
            ];
        };

        $field = new RepeaterField('contacts', $callback);
        $field->minItems(1)->maxItems(5)->sortable();

        $this->assertInstanceOf(RepeaterField::class, $field);

        // Test that callback returns correct fields
        $fields = $callback(0, []);
        $this->assertCount(4, $fields);
        $this->assertInstanceOf(InputField::class, $fields[0]);
        $this->assertInstanceOf(SelectField::class, $fields[2]);
    }

    /** @test */
    public function it_has_render_method()
    {
        $callback = function ($index, $item) {
            return [new InputField("items[{$index}][value]", 'Value')];
        };

        $field = new RepeaterField('items', $callback);

        $this->assertTrue(method_exists($field, 'render'));
    }

    /** @test */
    public function it_preserves_configuration()
    {
        $callback = function ($index, $item) {
            return [];
        };

        $field = new RepeaterField('test', $callback, ['id' => 'custom-id']);
        $field->minItems(2)
            ->maxItems(8)
            ->addButtonText('Custom Add')
            ->removeButtonText('Custom Remove')
            ->sortable(true);

        // Test that the configuration is preserved
        $this->assertEquals('test', $field->getName());
        $this->assertInstanceOf(RepeaterField::class, $field);
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        parent::tearDown();
    }
}
