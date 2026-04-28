<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\TablarKitServiceProvider;

/**
 * Locks the toast on/off contract: `config('tablar-kit.confirm.toast')`
 * surfaces as `data-toast-enabled="0|1"` on the modal mount, and
 * confirm-modal.js gates `toast()` on that attribute.
 */
class ConfirmModalToastConfigTest extends TestCase
{
    private const JS = __DIR__.'/../../resources/js/plugins/confirm-modal.js';

    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    public function test_default_config_enables_toast(): void
    {
        $this->assertTrue(Config::get('tablar-kit.confirm.toast'));
    }

    public function test_modal_mount_emits_data_toast_enabled_attribute_when_true(): void
    {
        Config::set('tablar-kit.confirm.toast', true);

        $html = Blade::render('<x-confirm-modal />');

        $this->assertStringContainsString('data-toast-enabled="1"', $html);
    }

    public function test_modal_mount_emits_data_toast_enabled_attribute_when_false(): void
    {
        Config::set('tablar-kit.confirm.toast', false);

        $html = Blade::render('<x-confirm-modal />');

        $this->assertStringContainsString('data-toast-enabled="0"', $html);
    }

    public function test_js_reads_toast_enabled_attribute(): void
    {
        $source = file_get_contents(self::JS);

        $this->assertStringContainsString('toastEnabled', $source);
        $this->assertStringContainsString('dataset.toastEnabled', $source);
    }

    public function test_js_short_circuits_toast_when_disabled(): void
    {
        $source = file_get_contents(self::JS);

        // toast() must early-return when the flag is off.
        $this->assertMatchesRegularExpression(
            '/toast\([^)]*\)\s*\{\s*if\s*\(\s*!\s*this\.toastEnabled\(\)\s*\)\s*return/',
            $source,
        );
    }
}
