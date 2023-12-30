<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Cards;


use TakiElias\TablarKit\Tests\Components\ComponentTestCase;

class CardTest extends ComponentTestCase
{

    /** @test */
    public function the_component_can_be_rendered()
    {
        $view = $this->blade(
            '<x-card>
                        <x-slot:header>
                            <h3 class="card-title">Card title</h3>
                        </x-slot:header>
                        <x-slot:body>
                            <div class="card-body">Simple card</div>
                        </x-slot:body>
                      </x-card>
            '
        );

        $view->assertSee('class="card"', false)
            ->assertSee('class="card-body"', false)
            ->assertSee('class="card-title"', false);
    }

    /** @test */
    public function render_stamp_card()
    {
        $view = $this->blade(
            '<x-card>
                        <x-slot:header>
                            <h3 class="card-title">Card title</h3>
                        </x-slot:header>
                        <x-slot:stamp>
                            <div class="card-stamp-icon bg-yellow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path>
                                    <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                                </svg>
                            </div>
                        </x-slot:stamp>
                        <x-slot:body>
                            <div class="card-body">Simple card</div>
                        </x-slot:body>
                    </x-card>
            '
        );

        $view->assertSee('class="card-stamp"', false)
            ->assertSee('class="card-stamp-icon bg-yellow"', false);
    }

    /** @test */
    public function render_ribbon_card()
    {
        $view = $this->blade(
            '<x-card>
                                <x-slot:header>
                                    <h3 class="card-title">Card title</h3>
                                </x-slot:header>
                                <x-slot:ribbon>
                                    <div class="ribbon bg-red">NEW</div>
                                </x-slot:ribbon>
                                <x-slot:body>
                                    <div class="card-body">Simple card</div>
                                </x-slot:body>
                       </x-card>
            '
        );

        $view->assertSee('class="ribbon bg-red"', false);
    }

    /** @test */
    public function render_card_footer()
    {
        $view = $this->blade(
            ' <x-card>
                        <x-slot:header>
                            <h3 class="card-title">Card title</h3>
                        </x-slot:header>
                        <x-slot:body>
                            <div class="card-body">Simple card</div>
                        </x-slot:body>
                        <x-slot:footer>
                            <a href="/docs/4.0/cards#" class="btn btn-primary">Go somewhere</a>
                        </x-slot:footer>
                    </x-card>
            '
        );

        $view->assertSee('class="card-footer"', false);
        $view->assertSee('href="/docs/4.0/cards#"', false);
    }

}
