<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Cards;


use TakiElias\TablarKit\Tests\ComponentTestCase;

class ModalTest extends ComponentTestCase
{

    /** @test */
    public function the_component_can_be_rendered()
    {
        $view = $this->blade('<x-modal status="success" title="Create User"/>'
        );

        $view->assertSee('class="modal modal-blur fade"', false)
            ->assertSee('class="modal-status bg-success"', false)
            ->assertSee('class="modal-content"', false);
    }

    /** @test */
    public function can_render_danger()
    {
        $view = $this->blade('<x-modal status="danger" title="Create User"/>'
        );

        $view->assertSee('class="modal modal-blur fade"', false)
            ->assertSee('class="modal-status bg-danger"', false)
            ->assertSee('class="modal-content"', false);
    }

}
