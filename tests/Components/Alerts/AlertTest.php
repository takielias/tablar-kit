<?php

declare(strict_types=1);

namespace TakiElias\TablarKit\Tests\Components\Alerts;

use Gajus\Dindent\Exception\InvalidArgumentException;
use Gajus\Dindent\Exception\RuntimeException;
use TakiElias\TablarKit\Tests\ComponentTestCase;

class AlertTest extends ComponentTestCase
{
    /** @test */
    public function the_component_can_be_rendered()
    {
        session()->flash('alert', 'Form was successfully submitted.');

        $expected = <<<'HTML'
            <div role="alert">
                Form was successfully submitted.
            </div>
            HTML;

        try {
            $this->assertComponentRenders($expected, '<x-alert/>');
        } catch (InvalidArgumentException|RuntimeException $e) {
        }
    }

    /** @test */
    public function we_can_specify_a_type()
    {
        session()->flash('error', 'Form contains some errors.');

        $expected = <<<'HTML'
            <div role="alert">
                Form contains some errors.
            </div>
            HTML;

        $this->assertComponentRenders($expected, '<x-alert type="error"/>');
    }

    /** @test */
    public function it_can_be_slotted()
    {
        session()->flash('alert', 'Form was successfully submitted.');

        $template = <<<'HTML'
            <x-alert>
                <span>Hello World</span>
                {{ $component->message() }}
            </x-alert>
            HTML;

        $expected = <<<'HTML'
            <div role="alert">
                <span>Hello World</span>
                Form was successfully submitted.
            </div>
            HTML;

        $this->assertComponentRenders($expected, $template);
    }

    /** @test */
    public function multiple_messages_can_be_used()
    {
        session()->flash('alert', [
            'Form was successfully submitted.',
            'We have sent you a confirmation email.',
        ]);

        $template = <<<'HTML'
            <x-alert>
                <span>Hello World</span>
                {{ implode(' ', $component->messages()) }}
            </x-alert>
            HTML;
        $expected = <<<'HTML'
            <div role="alert">
                <span>Hello World</span>
                Form was successfully submitted. We have sent you a confirmation email.
            </div>
            HTML;

        try {
            $this->assertComponentRenders($expected, $template);
        } catch (InvalidArgumentException|RuntimeException $e) {
        }
    }
}
