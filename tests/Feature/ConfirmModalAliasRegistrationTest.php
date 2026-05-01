<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\Components\Modals\Confirm;
use TakiElias\TablarKit\Components\Modals\ConfirmModal;
use TakiElias\TablarKit\TablarKitServiceProvider;

/**
 * Locks the Blade alias registration so consumers can write
 * `<x-confirm>` and `<x-confirm-modal>` without the `tablar-kit::`
 * prefix. Drift in the config/tablar-kit.php component map would
 * silently break every consumer.
 */
class ConfirmModalAliasRegistrationTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    public function test_confirm_alias_registered(): void
    {
        $aliases = Blade::getClassComponentAliases();

        $this->assertSame(Confirm::class, $aliases['confirm'] ?? null);
    }

    public function test_confirm_modal_alias_registered(): void
    {
        $aliases = Blade::getClassComponentAliases();

        $this->assertSame(ConfirmModal::class, $aliases['confirm-modal'] ?? null);
    }

    public function test_confirm_can_be_rendered_via_short_alias(): void
    {
        $html = Blade::render('<x-confirm url="/x">Go</x-confirm>');

        $this->assertStringContainsString('data-confirm-url="/x"', $html);
    }

    public function test_confirm_modal_can_be_rendered_via_short_alias(): void
    {
        $html = Blade::render('<x-confirm-modal />');

        $this->assertStringContainsString('id="tablar-kit-confirm-modal"', $html);
    }
}
