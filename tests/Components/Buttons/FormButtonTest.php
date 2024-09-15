<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components;

use Illuminate\Support\Facades\Route;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class FormButtonTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        Route::post('logout', function () {
            // ...
        })->name('logout');

        $template = <<<'HTML'
            <x-form-button :action="route('logout')">
                Sign Out
            </x-form-button>
            HTML;

        $expected = <<<'HTML'
            <form method="POST" action="http://localhost/logout">
                <input type="hidden" name="_token" value="" autocomplete="off">
                <input type="hidden" name="_method" value="POST">
                <button type="submit" class="btn btn-primary">
                Sign Out </button>
            </form>
            HTML;

        $this->assertComponentRenders($expected, $template);
    }

    /** @test */
    public function the_method_and_attributes_can_be_set()
    {
        $template = <<<'HTML'
            <x-form-button method="DELETE" action="http://example.com" class="text-gray-500">
                Logout
            </x-form-button>
            HTML;

        $expected = <<<'HTML'
            <form method="POST" action="http://example.com">
                <input type="hidden" name="_token" value="" autocomplete="off">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-primary text-gray-500">
                Logout </button>
            </form>
            HTML;

        $this->assertComponentRenders($expected, $template);
    }

    /** @test */
    public function the_action_prop_is_optional()
    {
        $template = <<<'HTML'
            <x-form-button method="DELETE">
                Logout
            </x-form-button>
            HTML;

        $expected = <<<'HTML'
            <form method="POST">
                <input type="hidden" name="_token" value="" autocomplete="off">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-primary">
                Logout </button>
            </form>
            HTML;

        $this->assertComponentRenders($expected, $template);
    }
}
