<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Forms\Inputs;

use TakiElias\TablarKit\Components\Forms\Inputs\Email;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class EmailTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        $component = new Email();

        $this->assertEquals('email', $component->name);
        $this->assertEquals('email', $component->id);
        $this->assertEquals('email', $component->type);
    }

    /** @test */
    public function component_accepts_custom_name_and_id()
    {
        $component = new Email('email_address', 'emailAddress');

        $this->assertEquals('email_address', $component->name);
        $this->assertEquals('emailAddress', $component->id);
    }

    /** @test */
    public function inputs_can_have_old_values()
    {
        $this->flashOld(['email' => 'test@example.com']);

        $component = new Email();

        $this->assertEquals('test@example.com', $component->value);
    }
}
