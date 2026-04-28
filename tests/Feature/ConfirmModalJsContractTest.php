<?php

namespace TakiElias\TablarKit\Tests\Feature;

use Orchestra\Testbench\TestCase;
use TakiElias\TablarKit\TablarKitServiceProvider;

/**
 * Locks the public contract of `confirm-modal.js`. We don't run the
 * script in a JSDom (heavy + flaky) — instead we assert the file
 * contains the required hooks: csrf source, click delegation, the
 * Promise API, the outcome priority chain, and 422 / non-2xx handling.
 */
class ConfirmModalJsContractTest extends TestCase
{
    private const JS = __DIR__.'/../../resources/js/plugins/confirm-modal.js';

    protected function getPackageProviders($app): array
    {
        return [TablarKitServiceProvider::class];
    }

    private function source(): string
    {
        return file_get_contents(self::JS);
    }

    public function test_file_exists(): void
    {
        $this->assertFileExists(self::JS);
    }

    public function test_exposes_window_tablar_confirm(): void
    {
        $this->assertMatchesRegularExpression(
            '/window\.tablarConfirm\s*=/',
            $this->source(),
            'Must expose window.tablarConfirm() programmatic API.',
        );
    }

    public function test_uses_csrf_meta_token(): void
    {
        $this->assertStringContainsString(
            'meta[name="csrf-token"]',
            $this->source(),
        );
    }

    public function test_targets_singleton_modal_id(): void
    {
        $this->assertStringContainsString("'tablar-kit-confirm-modal'", $this->source());
    }

    public function test_delegates_clicks_on_data_confirm_triggers(): void
    {
        $source = $this->source();

        $this->assertMatchesRegularExpression('/document\.addEventListener\(\s*[\'"]click[\'"]/', $source);
        $this->assertStringContainsString('[data-confirm]', $source);
        $this->assertStringContainsString('closest', $source);
    }

    public function test_reads_all_data_attributes(): void
    {
        $source = $this->source();

        foreach ([
            'confirmUrl',
            'confirmMethod',
            'confirmTitle',
            'confirmMessage',
            'confirmButton',
            'confirmStatus',
            'confirmEvent',
            'confirmRedirect',
            'confirmClass',
        ] as $attr) {
            $this->assertStringContainsString('dataset.'.$attr, $source, "Missing dataset.{$attr} read.");
        }

        $this->assertStringContainsString('data-confirm-no-reload', $source);
    }

    public function test_runs_outcome_priority_on_success_redirect_event_reload(): void
    {
        $source = $this->source();

        // Order matters — onSuccess must be checked first, then redirect, then event, then reload.
        $onSuccessPos = strpos($source, 'opts.onSuccess');
        $redirectPos = strpos($source, 'opts.redirect');
        $eventPos = strpos($source, 'opts.event');
        $reloadPos = strpos($source, 'opts.reload');

        $this->assertNotFalse($onSuccessPos);
        $this->assertNotFalse($redirectPos);
        $this->assertNotFalse($eventPos);
        $this->assertNotFalse($reloadPos);

        $this->assertLessThan($redirectPos, $onSuccessPos, 'onSuccess must be checked before redirect.');
        $this->assertLessThan($eventPos, $redirectPos, 'redirect must be checked before event.');
        $this->assertLessThan($reloadPos, $eventPos, 'event must be checked before reload.');
    }

    public function test_handles_422_with_field_errors(): void
    {
        $this->assertMatchesRegularExpression(
            '/result\.status\s*===\s*422/',
            $this->source(),
        );
    }

    public function test_renders_error_alert_on_non_2xx(): void
    {
        $this->assertStringContainsString('showAlert', $this->source());
        $this->assertStringContainsString('d-none', $this->source());
    }

    public function test_reenables_confirm_button_on_error(): void
    {
        $this->assertMatchesRegularExpression(
            '/confirmBtn\.disabled\s*=\s*false/',
            $this->source(),
        );
    }

    public function test_emits_lifecycle_events(): void
    {
        $source = $this->source();

        $this->assertStringContainsString("'tablar-kit:confirm:open'", $source);
        $this->assertStringContainsString("'tablar-kit:confirm:success'", $source);
        $this->assertStringContainsString("'tablar-kit:confirm:error'", $source);
    }

    public function test_warns_on_duplicate_mounts(): void
    {
        $this->assertMatchesRegularExpression(
            '/console\.warn\([^)]*Multiple/',
            $this->source(),
        );
    }
}
