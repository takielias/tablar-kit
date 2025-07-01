<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\DependentSelect;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class DependentSelectTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new DependentSelect(
            'product_list',
            'dependent_product_1',
            'get.product.target.list',
            'dependent-select'
        );

        $this->assertEquals('product_list', $component->name);
        $this->assertEquals('dependent-select', $component->id);
        $this->assertEquals('dependent_product_1', $component->targetDropdown);
        $this->assertEquals('get.product.target.list', $component->targetDataRoute);
        $this->assertIsArray($component->options);
    }

    /** @test */
    public function component_returns_correct_data()
    {
        $component = new DependentSelect(
            'test',
            'target',
            'route.name',
            'test-id',
            'value',
            ['key' => 'option'],
            'Select...'
        );

        $data = $component->data();

        $this->assertEquals('test', $data['name']);
        $this->assertEquals('target', $data['targetDropdown']);
        $this->assertEquals('route.name', $data['targetDataRoute']);
        $this->assertEquals('Select...', $data['placeholder']);
        $this->assertEquals(['key' => 'option'], $data['options']);
    }
}
