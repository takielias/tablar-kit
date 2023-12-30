<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;


use Illuminate\Support\Facades\Route;
use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class DependentSelectTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        Route::post('get-product-target-list', function () {
            // ...
        })->name('get.product.target.list');

        $this->assertComponentRenders(
            '<select name="product_list" class="form-select mt-3" id="dependent-select" data-child="dependent_product_1"></select>',
            '<x-dependent-select name="product_list" id="dependent-select"
                        target-dropdown="dependent_product_1"
                        class="mt-3"
                        target-data-route="get.product.target.list"
                        :options="[]">
                     </x-dependent-select>',
        );
    }

}
